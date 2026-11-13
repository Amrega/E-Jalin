<?php

namespace App\Http\Controllers;

use App\Models\Jembatan;
use App\Models\JenisPenanganan;
use App\Models\KabupatenKota;
use App\Models\Koridor;
use App\Models\RuasJalan;
use App\Models\TematikKawasan;
use App\Models\UsulanInpres;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class UsulanInpresController extends Controller
{
    /**
     * Path folder penyimpanan Dokumen Kelengkapan Usulan Inpres
     */
    protected function getDokumenUploadPath(): string
    {
        $path = public_path('uploads/dokumen/usulan-inpres');
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true, true);
        }
        return $path;
    }

    /**
     * Tampilkan daftar Usulan Inpres beserta statistik dan filter
     */
    public function index(Request $request)
    {
        $query = UsulanInpres::with([
            'kabupatenKota',
            'tematikKawasan',
            'jenisPenanganan',
            'koridor',
            'ruasJalan',
            'jembatan',
        ]);

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

        // Filter Kabupaten / Kota
        if ($request->filled('kabupaten_kota_id')) {
            $query->where('kabupaten_kota_id', $request->kabupaten_kota_id);
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

        // Data Statistik (berdasarkan data aktif / void = 0)
        $activeQuery = UsulanInpres::where('void', 0);
        $totalUsulan          = (clone $activeQuery)->count();
        $totalPanjangJalan    = (clone $activeQuery)->where('satuan', 'KM')->sum('panjang_penanganan') ?? 0;
        $totalPanjangJembatan = (clone $activeQuery)->where('satuan', 'M')->sum('panjang_penanganan') ?? 0;
        $totalEstimasiBiaya   = (clone $activeQuery)->sum('alokasi_usulan') ?? 0;

        // Data Master untuk Filter & Form Dropdown
        $kabupatenKotaList = KabupatenKota::where('void', 0)
            ->orderBy('nama_kabupaten_kota', 'asc')
            ->get();

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

        $pengusulList = UsulanInpres::select('nama_pengusul')
            ->distinct()
            ->orderBy('nama_pengusul', 'asc')
            ->pluck('nama_pengusul');

        return view('usulan-inpres', compact(
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
     * Simpan data Usulan Inpres baru
     */
    public function store(Request $request)
    {
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
            'prioritas_pu'             => ['nullable', 'boolean'],
            'prioritas_bappeda'        => ['nullable', 'boolean'],
            'verifikasi_pu'            => ['nullable', 'boolean'],
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

        $nextId = (UsulanInpres::max('id') ?? 0) + 1;
        $kodeUsulan = $request->input('kode_usulan') ?: (string) (238890 + $nextId);

        UsulanInpres::create([
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
            'prioritas_pu'             => $request->boolean('prioritas_pu', true),
            'prioritas_bappeda'        => $request->boolean('prioritas_bappeda', false),
            'verifikasi_pu'            => $request->boolean('verifikasi_pu', true),
            'void'                     => $request->has('void') ? (int) $request->void : 0,
        ]);

        return redirect()->route('usulan-inpres.index')->with('success', "Usulan Inpres #{$kodeUsulan} berhasil ditambahkan.");
    }

    /**
     * Perbarui data Usulan Inpres
     */
    public function update(Request $request, UsulanInpres $usulan_inpres)
    {
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
            'prioritas_pu'             => ['nullable', 'boolean'],
            'prioritas_bappeda'        => ['nullable', 'boolean'],
            'verifikasi_pu'            => ['nullable', 'boolean'],
            'void'                     => ['nullable', 'in:0,1'],
        ]);

        $fileName = $usulan_inpres->file_dokumen_pendukung;

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

        $usulan_inpres->update([
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
            'prioritas_pu'             => $request->boolean('prioritas_pu'),
            'prioritas_bappeda'        => $request->boolean('prioritas_bappeda'),
            'verifikasi_pu'            => $request->boolean('verifikasi_pu'),
            'void'                     => $request->has('void') ? (int) $request->void : $usulan_inpres->void,
        ]);

        return redirect()->route('usulan-inpres.index')->with('success', "Usulan Inpres #{$usulan_inpres->kode_usulan} berhasil diperbarui.");
    }

    /**
     * Hapus data Usulan Inpres
     */
    public function destroy(UsulanInpres $usulan_inpres)
    {
        if ($usulan_inpres->file_dokumen_pendukung) {
            $filePath = public_path('uploads/dokumen/usulan-inpres/' . $usulan_inpres->file_dokumen_pendukung);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        $kode = $usulan_inpres->kode_usulan;
        $usulan_inpres->delete();

        return redirect()->route('usulan-inpres.index')->with('success', "Usulan Inpres #{$kode} berhasil dihapus.");
    }

    /**
     * Toggle status Void (0 = Aktif, 1 = Void)
     */
    public function toggleVoid(UsulanInpres $usulan_inpres)
    {
        $usulan_inpres->void = $usulan_inpres->void ? 0 : 1;
        $usulan_inpres->save();

        $statusText = $usulan_inpres->void ? 'ditandai sebagai Void (Non-Aktif)' : 'diaktifkan kembali';
        return redirect()->back()->with('success', "Usulan #{$usulan_inpres->kode_usulan} berhasil {$statusText}.");
    }

    /**
     * Download file Dokumen Pendukung / Teknis
     */
    public function downloadDokumen(UsulanInpres $usulan_inpres)
    {
        if (!$usulan_inpres->file_dokumen_pendukung) {
            return redirect()->back()->with('error', 'File dokumen pendukung tidak tersedia untuk usulan ini.');
        }

        $filePath = public_path('uploads/dokumen/usulan-inpres/' . $usulan_inpres->file_dokumen_pendukung);

        if (!File::exists($filePath)) {
            return redirect()->back()->with('error', 'File dokumen fisik tidak ditemukan di server.');
        }

        return response()->download($filePath, $usulan_inpres->file_dokumen_pendukung);
    }
}
