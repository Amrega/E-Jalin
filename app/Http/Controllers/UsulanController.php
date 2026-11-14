<?php

namespace App\Http\Controllers;

use App\Models\Jembatan;
use App\Models\JenisPenanganan;
use App\Models\KabupatenKota;
use App\Models\Koridor;
use App\Models\RuasJalan;
use App\Models\TematikKawasan;
use App\Models\Usulan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class UsulanController extends Controller
{
    /**
     * Path folder penyimpanan Dokumen Kelengkapan Usulan
     */
    protected function getDokumenUploadPath(): string
    {
        $path = public_path('uploads/dokumen/usulan');
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true, true);
        }
        return $path;
    }

    /**
     * Tampilkan daftar Usulan beserta statistik dan filter
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Usulan::with([
            'kabupatenKota',
            'tematikKawasan',
            'jenisPenanganan',
            'koridor',
            'ruasJalan',
            'jembatan',
        ]);

        // Jika akun Kabupaten/Kota, batasi hanya bisa melihat usulan miliknya sendiri
        if ($user && $user->isKabupatenKota()) {
            $query->where('kabupaten_kota_id', $user->kabupaten_kota_id);
        } elseif ($request->filled('kabupaten_kota_id')) {
            // Filter Kabupaten / Kota (untuk Admin / PU / Bappeda)
            $query->where('kabupaten_kota_id', $request->kabupaten_kota_id);
        }

        // Filter Pencarian
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('kode_usulan', 'like', "%{$search}%")
                  ->orWhere('nama_kegiatan', 'like', "%{$search}%")
                  ->orWhere('nama_pengusul', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhereHas('kabupatenKota', function ($kq) use ($search) {
                      $kq->where('nama_kabupaten_kota', 'like', "%{$search}%");
                  })
                  ->orWhereHas('ruasJalan', function ($rq) use ($search) {
                      $rq->where('nama_ruas', 'like', "%{$search}%")
                         ->orWhere('nomor_ruas', 'like', "%{$search}%");
                  })
                  ->orWhereHas('koridor', function ($cq) use ($search) {
                      $cq->where('nama_koridor', 'like', "%{$search}%");
                  })
                  ->orWhereHas('jembatan', function ($jq) use ($search) {
                      $jq->where('nama_jembatan', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Nama Pengusul
        if ($request->filled('nama_pengusul')) {
            $query->where('nama_pengusul', $request->nama_pengusul);
        }

        // Filter Tematik Kawasan
        if ($request->filled('tematik_kawasan_id')) {
            $query->where('tematik_kawasan_id', $request->tematik_kawasan_id);
        }

        // Filter Status Void (Aktif / Void)
        if ($request->filled('status')) {
            if ($request->status === 'aktif') {
                $query->where('void', 0);
            } elseif ($request->status === 'void') {
                $query->where('void', 1);
            }
        }

        // Urutan data
        $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');

        // Pagination
        $perPage = (int) $request->input('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 10;
        $usulanList = $query->paginate($perPage)->withQueryString();

        // Data Statistik (berdasarkan data aktif / void = 0, sesuai cakupan user)
        $activeQuery = Usulan::where('void', 0);
        if ($user && $user->isKabupatenKota()) {
            $activeQuery->where('kabupaten_kota_id', $user->kabupaten_kota_id);
        }

        $totalUsulan          = (clone $activeQuery)->count();
        $totalPanjangJalan    = (clone $activeQuery)->where('satuan', 'KM')->sum('panjang_penanganan') ?? 0;
        $totalPanjangJembatan = (clone $activeQuery)->where('satuan', 'M')->sum('panjang_penanganan') ?? 0;
        $totalEstimasiBiaya   = (clone $activeQuery)->sum('alokasi_usulan') ?? 0;

        // Data Master untuk Filter & Form Dropdown
        $kabKotaQuery = KabupatenKota::where('void', 0)->orderBy('nama_kabupaten_kota', 'asc');
        if ($user && $user->isKabupatenKota()) {
            $kabKotaQuery->where('id', $user->kabupaten_kota_id);
        }
        $kabupatenKotaList = $kabKotaQuery->get();

        $tematikKawasanList = TematikKawasan::where('void', 0)
            ->orderBy('id', 'asc')
            ->get();

        $jenisPenangananList = JenisPenanganan::where('void', 0)
            ->orderBy('target_penangan', 'asc')
            ->orderBy('nama_penanganan', 'asc')
            ->get();

        $koridorList = Koridor::where('void', 0)
            ->orderBy('nama_koridor', 'asc')
            ->get();

        $ruasJalanList = RuasJalan::where('void', 0)
            ->orderBy('nama_ruas', 'asc')
            ->get();

        $jembatanList = Jembatan::where('void', 0)
            ->orderBy('nama_jembatan', 'asc')
            ->get();

        $pengusulQuery = Usulan::select('nama_pengusul')->distinct()->orderBy('nama_pengusul', 'asc');
        if ($user && $user->isKabupatenKota()) {
            $pengusulQuery->where('kabupaten_kota_id', $user->kabupaten_kota_id);
        }
        $pengusulList = $pengusulQuery->pluck('nama_pengusul');

        return view('usulan', compact(
            'usulanList',
            'kabupatenKotaList',
            'tematikKawasanList',
            'jenisPenangananList',
            'koridorList',
            'ruasJalanList',
            'jembatanList',
            'pengusulList',
            'totalUsulan',
            'totalPanjangJalan',
            'totalPanjangJembatan',
            'totalEstimasiBiaya'
        ));
    }

    /**
     * Simpan data Usulan baru
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user || !$user->canManageUsulan()) {
            return redirect()->route('usulan.index')->with('error', 'Role Anda tidak diizinkan menambahkan data usulan baru (hanya dapat melihat dan melakukan verifikasi/indikasi prioritas).');
        }

        // Jika akun Kabupaten/Kota, paksa kabupaten_kota_id ke miliknya sendiri
        if ($user->isKabupatenKota()) {
            $request->merge(['kabupaten_kota_id' => $user->kabupaten_kota_id]);
        }

        $validated = $request->validate([
            'kabupaten_kota_id'        => ['required', 'exists:kabupaten_kota,id'],
            'tematik_kawasan_id'       => ['nullable', 'exists:tematik_kawasan,id'],
            'jenis_penanganan_id'      => ['nullable', 'exists:jenis_penanganan,id'],
            'koridor_id'               => ['nullable', 'exists:koridor,id'],
            'ruas_jalan_id'            => ['nullable', 'exists:ruas_jalan,id'],
            'jembatan_id'              => ['nullable', 'exists:jembatan,id'],
            'nama_pengusul'            => ['required', 'string', 'max:150'],
            'nama_kegiatan'            => ['required', 'string', 'max:255'],
            'status_ruas'              => ['required', 'in:Provinsi,Kabupaten/Kota,Non Status'],
            'panjang_penanganan'       => ['required', 'numeric', 'min:0'],
            'satuan'                   => ['required', 'in:KM,M'],
            'alokasi_usulan'           => ['required', 'numeric', 'min:0'],
            'keterangan'               => ['nullable', 'string'],
            'status_dokumen_pendukung' => ['required', 'in:SIAP,TIDAK SIAP,TIDAK PERLU'],
            'file_dokumen_pendukung'   => ['nullable', 'file', 'mimes:pdf,doc,docx,zip', 'max:25600'],
            'void'                     => ['nullable', 'in:0,1'],
        ]);

        $fileName = null;
        if ($request->hasFile('file_dokumen_pendukung')) {
            $file = $request->file('file_dokumen_pendukung');
            $uploadPath = $this->getDokumenUploadPath();

            $rawName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . Str::slug($rawName) . '.' . ($extension ?: 'pdf');
            $file->move($uploadPath, $fileName);
        }

        $nextId = (Usulan::max('id') ?? 0) + 1;
        $kodeUsulan = $request->input('kode_usulan') ?: (string) (238890 + $nextId);

        Usulan::create([
            'kode_usulan'              => $kodeUsulan,
            'kabupaten_kota_id'        => $validated['kabupaten_kota_id'],
            'tematik_kawasan_id'       => $validated['tematik_kawasan_id'] ?? null,
            'jenis_penanganan_id'      => $validated['jenis_penanganan_id'] ?? null,
            'koridor_id'               => $validated['koridor_id'] ?? null,
            'ruas_jalan_id'            => $validated['ruas_jalan_id'] ?? null,
            'jembatan_id'              => $validated['jembatan_id'] ?? null,
            'nama_pengusul'            => $validated['nama_pengusul'],
            'nama_kegiatan'            => $validated['nama_kegiatan'],
            'status_ruas'              => $validated['status_ruas'],
            'panjang_penanganan'       => $validated['panjang_penanganan'],
            'satuan'                   => $validated['satuan'],
            'alokasi_usulan'           => (int) $validated['alokasi_usulan'],
            'keterangan'               => $validated['keterangan'] ?? null,
            'status_dokumen_pendukung' => $validated['status_dokumen_pendukung'],
            'file_dokumen_pendukung'   => $fileName,
            'prioritas_pu'             => false,
            'prioritas_bappeda'        => false,
            'verifikasi_pu'            => false,
            'void'                     => $request->has('void') ? (int) $request->void : 0,
        ]);

        return redirect()->route('usulan.index')->with('success', "Usulan #{$kodeUsulan} berhasil ditambahkan.");
    }

    /**
     * Update status Indikasi Ruas Prioritas / Verifikasi sesuai Role:
     * - pu_turdal  -> prioritas_pu (Indikasi Ruas Prioritas PU)
     * - bappeda    -> prioritas_bappeda (Indikasi Ruas Prioritas Bappeda)
     * - pu_bintek  -> verifikasi_pu (Verifikasi PU)
     * - administrator -> dapat mengubah ketiganya
     */
    public function updateStatus(Request $request, Usulan $usulan)
    {
        $validated = $request->validate([
            'field' => ['required', 'in:prioritas_pu,prioritas_bappeda,verifikasi_pu'],
            'value' => ['nullable', 'boolean'],
        ]);

        $user = $request->user();
        $field = $validated['field'];

        $allowed = match ($field) {
            'prioritas_pu'      => $user && $user->canEditPrioritasPu(),
            'prioritas_bappeda' => $user && $user->canEditPrioritasBappeda(),
            'verifikasi_pu'     => $user && $user->canEditVerifikasiPu(),
            default             => false,
        };

        if (!$allowed) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses role untuk mengubah status tersebut.');
        }

        $newValue = $request->has('value')
            ? $request->boolean('value')
            : !$usulan->{$field};

        $usulan->{$field} = $newValue;
        $usulan->save();

        $labelMap = [
            'prioritas_pu'      => 'Indikasi Ruas Prioritas PU',
            'prioritas_bappeda' => 'Indikasi Ruas Prioritas Bappeda',
            'verifikasi_pu'     => 'Verifikasi PU',
        ];

        $statusSimbol = $newValue ? 'Disetujui (✓)' : 'Belum / Ditolak (✕)';

        return redirect()->back()->with('success', "Status {$labelMap[$field]} untuk Usulan #{$usulan->kode_usulan} diubah menjadi {$statusSimbol}.");
    }

    /**
     * Perbarui data Usulan
     */
    public function update(Request $request, Usulan $usulan)
    {
        $user = $request->user();

        if (!$user || !$user->canManageUsulan($usulan)) {
            if ($user && $user->isVerifierRole()) {
                return redirect()->route('usulan.index')->with('error', 'Role Anda hanya dapat melihat usulan dan mengubah status verifikasi/indikasi prioritas.');
            }
            return redirect()->route('usulan.index')->with('error', 'Anda tidak memiliki akses untuk mengedit usulan milik Pemerintah Daerah lain.');
        }

        if ($user->isKabupatenKota()) {
            $request->merge(['kabupaten_kota_id' => $user->kabupaten_kota_id]);
        }

        $validated = $request->validate([
            'kabupaten_kota_id'        => ['required', 'exists:kabupaten_kota,id'],
            'tematik_kawasan_id'       => ['nullable', 'exists:tematik_kawasan,id'],
            'jenis_penanganan_id'      => ['nullable', 'exists:jenis_penanganan,id'],
            'koridor_id'               => ['nullable', 'exists:koridor,id'],
            'ruas_jalan_id'            => ['nullable', 'exists:ruas_jalan,id'],
            'jembatan_id'              => ['nullable', 'exists:jembatan,id'],
            'nama_pengusul'            => ['required', 'string', 'max:150'],
            'nama_kegiatan'            => ['required', 'string', 'max:255'],
            'status_ruas'              => ['required', 'in:Provinsi,Kabupaten/Kota,Non Status'],
            'panjang_penanganan'       => ['required', 'numeric', 'min:0'],
            'satuan'                   => ['required', 'in:KM,M'],
            'alokasi_usulan'           => ['required', 'numeric', 'min:0'],
            'keterangan'               => ['nullable', 'string'],
            'status_dokumen_pendukung' => ['required', 'in:SIAP,TIDAK SIAP,TIDAK PERLU'],
            'file_dokumen_pendukung'   => ['nullable', 'file', 'mimes:pdf,doc,docx,zip', 'max:25600'],
            'void'                     => ['nullable', 'in:0,1'],
        ]);

        $fileName = $usulan->file_dokumen_pendukung;

        if ($request->hasFile('file_dokumen_pendukung')) {
            $file = $request->file('file_dokumen_pendukung');
            $uploadPath = $this->getDokumenUploadPath();

            if ($fileName && File::exists($uploadPath . '/' . $fileName)) {
                File::delete($uploadPath . '/' . $fileName);
            }

            $rawName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . Str::slug($rawName) . '.' . ($extension ?: 'pdf');
            $file->move($uploadPath, $fileName);
        }

        $usulan->update([
            'kabupaten_kota_id'        => $validated['kabupaten_kota_id'],
            'tematik_kawasan_id'       => $validated['tematik_kawasan_id'] ?? null,
            'jenis_penanganan_id'      => $validated['jenis_penanganan_id'] ?? null,
            'koridor_id'               => $validated['koridor_id'] ?? null,
            'ruas_jalan_id'            => $validated['ruas_jalan_id'] ?? null,
            'jembatan_id'              => $validated['jembatan_id'] ?? null,
            'nama_pengusul'            => $validated['nama_pengusul'],
            'nama_kegiatan'            => $validated['nama_kegiatan'],
            'status_ruas'              => $validated['status_ruas'],
            'panjang_penanganan'       => $validated['panjang_penanganan'],
            'satuan'                   => $validated['satuan'],
            'alokasi_usulan'           => (int) $validated['alokasi_usulan'],
            'keterangan'               => $validated['keterangan'] ?? null,
            'status_dokumen_pendukung' => $validated['status_dokumen_pendukung'],
            'file_dokumen_pendukung'   => $fileName,
            'void'                     => $request->has('void') ? (int) $request->void : $usulan->void,
        ]);

        return redirect()->route('usulan.index')->with('success', "Usulan #{$usulan->kode_usulan} berhasil diperbarui.");
    }

    /**
     * Hapus data Usulan
     */
    public function destroy(Request $request, Usulan $usulan)
    {
        $user = $request->user();

        if (!$user || !$user->canManageUsulan($usulan)) {
            return redirect()->route('usulan.index')->with('error', 'Anda tidak memiliki hak akses untuk menghapus data usulan ini.');
        }

        if ($usulan->file_dokumen_pendukung) {
            $filePath = public_path('uploads/dokumen/usulan/' . $usulan->file_dokumen_pendukung);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        $kode = $usulan->kode_usulan;
        $usulan->delete();

        return redirect()->route('usulan.index')->with('success', "Usulan #{$kode} berhasil dihapus.");
    }

    /**
     * Toggle status Void (0 = Aktif, 1 = Void)
     */
    public function toggleVoid(Request $request, Usulan $usulan)
    {
        $user = $request->user();

        if (!$user || !$user->canManageUsulan($usulan)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk mengubah status void usulan ini.');
        }

        $usulan->void = $usulan->void ? 0 : 1;
        $usulan->save();

        $statusText = $usulan->void ? 'ditandai sebagai Void (Non-Aktif)' : 'diaktifkan kembali';
        return redirect()->back()->with('success', "Usulan #{$usulan->kode_usulan} berhasil {$statusText}.");
    }

    /**
     * Download file Dokumen Pendukung / Teknis
     */
    public function downloadDokumen(Request $request, Usulan $usulan)
    {
        $user = $request->user();

        if ($user && $user->isKabupatenKota() && (int) $usulan->kabupaten_kota_id !== (int) $user->kabupaten_kota_id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengunduh dokumen usulan Pemerintah Daerah lain.');
        }

        if (!$usulan->file_dokumen_pendukung) {
            return redirect()->back()->with('error', 'File dokumen pendukung tidak tersedia untuk usulan ini.');
        }

        $filePath = public_path('uploads/dokumen/usulan/' . $usulan->file_dokumen_pendukung);

        if (!File::exists($filePath)) {
            return redirect()->back()->with('error', 'File dokumen fisik tidak ditemukan di server.');
        }

        return response()->download($filePath, $usulan->file_dokumen_pendukung);
    }
}
