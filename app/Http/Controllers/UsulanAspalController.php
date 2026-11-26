<?php

namespace App\Http\Controllers;

use App\Models\JenisPengusul;
use App\Models\KabupatenKota;
use App\Models\Pengusul;
use App\Models\User;
use App\Models\UsulanAspal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class UsulanAspalController extends Controller
{
    /**
     * Path folder penyimpanan file Surat Permohonan Usulan Aspal
     */
    protected function getUploadPath(): string
    {
        $path = public_path('uploads/dokumen/usulan-aspal');
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0775, true, true);
        }
        return $path;
    }

    /**
     * Cek apakah user boleh menambah / mengedit / menghapus Usulan Aspal
     */
    protected function canManage(?User $user, ?UsulanAspal $usulanAspal = null): bool
    {
        if (!$user || $user->isVerifierRole() || $user->isUpt()) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isKabupatenKota()) {
            if ($usulanAspal === null) {
                return true;
            }
            $userKabKota = $user->kabupatenKota?->nama_kabupaten_kota;
            if ($userKabKota) {
                return strcasecmp($usulanAspal->kabupaten_kota, $userKabKota) === 0;
            }
            return $user->pengusul()->where('id', $usulanAspal->pengusul_id)->exists();
        }

        return false;
    }

    /**
     * Tampilkan daftar Usulan Aspal
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $query = UsulanAspal::with(['pengusul.user', 'jenisPengusul']);

        // Pembatasan cakupan untuk akun Kabupaten/Kota
        if ($user && $user->isKabupatenKota() && $user->kabupatenKota) {
            $query->where('kabupaten_kota', $user->kabupatenKota->nama_kabupaten_kota);
        } elseif ($request->filled('kabupaten_kota')) {
            $query->where('kabupaten_kota', $request->kabupaten_kota);
        }

        // Filter Jenis Pengusul
        if ($request->filled('jenis_pengusul_id')) {
            $query->where('jenis_pengusul_id', $request->jenis_pengusul_id);
        }

        // Filter Pengusul
        if ($request->filled('pengusul_id')) {
            $query->where('pengusul_id', $request->pengusul_id);
        }

        // Filter Pencarian
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('kabupaten_kota', 'like', "%{$search}%")
                    ->orWhere('keterangan_urgensi', 'like', "%{$search}%")
                    ->orWhere('upload_surat_permohonan', 'like', "%{$search}%")
                    ->orWhereHas('pengusul', function ($pq) use ($search) {
                        $pq->where('nama_pengusul', 'like', "%{$search}%");
                    })
                    ->orWhereHas('jenisPengusul', function ($jq) use ($search) {
                        $jq->where('nama_jenis_pengusul', 'like', "%{$search}%");
                    });
            });
        }

        // Urutan terbaru di atas
        $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');

        // Pagination
        $perPage = (int) $request->input('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 10;
        $usulanAspalList = $query->paginate($perPage)->withQueryString();

        // Data Master untuk dropdown filter & form modal
        $kabKotaQuery = KabupatenKota::where('void', 0)->orderBy('nama_kabupaten_kota', 'asc');
        if ($user && $user->isKabupatenKota() && $user->kabupaten_kota_id) {
            $kabKotaQuery->where('id', $user->kabupaten_kota_id);
        }
        $kabupatenKotaList = $kabKotaQuery->get();

        // Scope dropdown Pengusul & Jenis Pengusul:
        // - Hanya Administrator yang dapat melihat seluruh data Pengusul & Jenis Pengusul
        // - Seluruh user selain Administrator hanya melihat Pengusul yang terhubung ke user_id-nya beserta Jenis Pengusulnya
        if ($user && $user->isAdmin()) {
            $pengusulList = Pengusul::with('jenisPengusul')
                ->orderBy('nama_pengusul', 'asc')
                ->get();

            $jenisPengusulList = JenisPengusul::orderBy('nama_jenis_pengusul', 'asc')->get();
        } else {
            $pengusulList = Pengusul::with('jenisPengusul')
                ->where('user_id', $user?->id)
                ->orderBy('nama_pengusul', 'asc')
                ->get();

            $jenisIds = $pengusulList->pluck('jenis_pengusul_id')->filter()->unique()->values();
            $jenisPengusulList = JenisPengusul::whereIn('id', $jenisIds)
                ->orderBy('nama_jenis_pengusul', 'asc')
                ->get();
        }

        $canManage = $this->canManage($user);

        return view('usulan-aspal.index', compact(
            'usulanAspalList',
            'kabupatenKotaList',
            'jenisPengusulList',
            'pengusulList',
            'canManage'
        ));
    }

    /**
     * Simpan data Usulan Aspal baru
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if (!$this->canManage($user)) {
            return redirect()->route('usulan-aspal.index')
                ->with('error', 'Role Anda tidak diizinkan menambahkan data usulan aspal baru.');
        }

        if ($user->isKabupatenKota() && $user->kabupatenKota) {
            $request->merge(['kabupaten_kota' => $user->kabupatenKota->nama_kabupaten_kota]);
        }

        $validated = $request->validate([
            'kabupaten_kota'          => ['required', 'string', 'max:150'],
            'qty_drum'                => ['required', 'integer', 'min:1'],
            'keterangan_urgensi'      => ['required', 'string'],
            'pengusul_id'             => ['required', 'exists:pengusul,id'],
            'jenis_pengusul_id'       => ['required', 'exists:jenis_pengusul,id'],
            'upload_surat_permohonan' => ['nullable', 'file', 'mimes:pdf', 'max:25600'],
        ], [
            'kabupaten_kota.required'          => 'Kabupaten / Kota wajib dipilih.',
            'kabupaten_kota.max'               => 'Kabupaten / Kota maksimal 150 karakter.',
            'qty_drum.required'                => 'Qty (drum) wajib diisi.',
            'qty_drum.integer'                 => 'Qty (drum) harus berupa angka bulat.',
            'qty_drum.min'                     => 'Qty minimal 1 drum.',
            'keterangan_urgensi.required'      => 'Keterangan urgensi wajib diisi.',
            'pengusul_id.required'             => 'Pengusul wajib dipilih.',
            'pengusul_id.exists'               => 'Pengusul yang dipilih tidak valid.',
            'jenis_pengusul_id.required'       => 'Jenis pengusul wajib dipilih.',
            'jenis_pengusul_id.exists'         => 'Jenis pengusul yang dipilih tidak valid.',
            'upload_surat_permohonan.file'     => 'Surat permohonan harus berupa file.',
            'upload_surat_permohonan.mimes'    => 'Format surat permohonan harus berupa file PDF (.pdf).',
            'upload_surat_permohonan.max'      => 'Ukuran file surat permohonan maksimal 25 MB.',
        ]);

        if (!$user->isAdmin()) {
            $pengusul = Pengusul::where('id', $validated['pengusul_id'])
                ->where('user_id', $user->id)
                ->first();

            if (!$pengusul) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['pengusul_id' => 'Pengusul yang dipilih tidak terhubung dengan akun Anda.']);
            }

            $validated['jenis_pengusul_id'] = $pengusul->jenis_pengusul_id;
        }

        $fileName = null;
        if ($request->hasFile('upload_surat_permohonan')) {
            $file = $request->file('upload_surat_permohonan');
            $rawName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension() ?: 'pdf';
            $fileName = time() . '_surat_permohonan_' . Str::slug($rawName) . '.' . $extension;
            $file->move($this->getUploadPath(), $fileName);
        }

        UsulanAspal::create([
            'tahun'                   => (int) now()->year,
            'kabupaten_kota'          => trim($validated['kabupaten_kota']),
            'qty_drum'                => (int) $validated['qty_drum'],
            'keterangan_urgensi'      => trim($validated['keterangan_urgensi']),
            'pengusul_id'             => $validated['pengusul_id'],
            'jenis_pengusul_id'       => $validated['jenis_pengusul_id'],
            'upload_surat_permohonan' => $fileName,
        ]);

        return redirect()->route('usulan-aspal.index')
            ->with('success', 'Data usulan aspal berhasil ditambahkan.');
    }

    /**
     * Perbarui data Usulan Aspal
     */
    public function update(Request $request, UsulanAspal $usulanAspal)
    {
        $user = $request->user();

        if (!$this->canManage($user, $usulanAspal)) {
            return redirect()->route('usulan-aspal.index')
                ->with('error', 'Anda tidak memiliki hak akses untuk mengubah data usulan aspal ini.');
        }

        if ($user->isKabupatenKota() && $user->kabupatenKota) {
            $request->merge(['kabupaten_kota' => $user->kabupatenKota->nama_kabupaten_kota]);
        }

        $validated = $request->validate([
            'kabupaten_kota'          => ['required', 'string', 'max:150'],
            'qty_drum'                => ['required', 'integer', 'min:1'],
            'keterangan_urgensi'      => ['required', 'string'],
            'pengusul_id'             => ['required', 'exists:pengusul,id'],
            'jenis_pengusul_id'       => ['required', 'exists:jenis_pengusul,id'],
            'upload_surat_permohonan' => ['nullable', 'file', 'mimes:pdf', 'max:25600'],
        ], [
            'kabupaten_kota.required'          => 'Kabupaten / Kota wajib dipilih.',
            'kabupaten_kota.max'               => 'Kabupaten / Kota maksimal 150 karakter.',
            'qty_drum.required'                => 'Qty (drum) wajib diisi.',
            'qty_drum.integer'                 => 'Qty (drum) harus berupa angka bulat.',
            'qty_drum.min'                     => 'Qty minimal 1 drum.',
            'keterangan_urgensi.required'      => 'Keterangan urgensi wajib diisi.',
            'pengusul_id.required'             => 'Pengusul wajib dipilih.',
            'pengusul_id.exists'               => 'Pengusul yang dipilih tidak valid.',
            'jenis_pengusul_id.required'       => 'Jenis pengusul wajib dipilih.',
            'jenis_pengusul_id.exists'         => 'Jenis pengusul yang dipilih tidak valid.',
            'upload_surat_permohonan.file'     => 'Surat permohonan harus berupa file.',
            'upload_surat_permohonan.mimes'    => 'Format surat permohonan harus berupa file PDF (.pdf).',
            'upload_surat_permohonan.max'      => 'Ukuran file surat permohonan maksimal 25 MB.',
        ]);

        if (!$user->isAdmin()) {
            $pengusul = Pengusul::where('id', $validated['pengusul_id'])
                ->where('user_id', $user->id)
                ->first();

            if (!$pengusul) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['pengusul_id' => 'Pengusul yang dipilih tidak terhubung dengan akun Anda.']);
            }

            $validated['jenis_pengusul_id'] = $pengusul->jenis_pengusul_id;
        }

        $fileName = $usulanAspal->upload_surat_permohonan;
        if ($request->hasFile('upload_surat_permohonan')) {
            $uploadPath = $this->getUploadPath();
            if ($fileName && File::exists($uploadPath . '/' . $fileName)) {
                File::delete($uploadPath . '/' . $fileName);
            }

            $file = $request->file('upload_surat_permohonan');
            $rawName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension() ?: 'pdf';
            $fileName = time() . '_surat_permohonan_' . Str::slug($rawName) . '.' . $extension;
            $file->move($uploadPath, $fileName);
        }

        $usulanAspal->update([
            'tahun'                   => $usulanAspal->tahun ?: (int) now()->year,
            'kabupaten_kota'          => trim($validated['kabupaten_kota']),
            'qty_drum'                => (int) $validated['qty_drum'],
            'keterangan_urgensi'      => trim($validated['keterangan_urgensi']),
            'pengusul_id'             => $validated['pengusul_id'],
            'jenis_pengusul_id'       => $validated['jenis_pengusul_id'],
            'upload_surat_permohonan' => $fileName,
        ]);

        return redirect()->route('usulan-aspal.index')
            ->with('success', 'Data usulan aspal berhasil diperbarui.');
    }

    /**
     * Hapus data Usulan Aspal
     */
    public function destroy(Request $request, UsulanAspal $usulanAspal)
    {
        $user = $request->user();

        if (!$this->canManage($user, $usulanAspal)) {
            return redirect()->route('usulan-aspal.index')
                ->with('error', 'Anda tidak memiliki hak akses untuk menghapus data usulan aspal ini.');
        }

        if ($usulanAspal->upload_surat_permohonan) {
            $filePath = public_path('uploads/dokumen/usulan-aspal/' . $usulanAspal->upload_surat_permohonan);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        $usulanAspal->delete();

        return redirect()->route('usulan-aspal.index')
            ->with('success', 'Data usulan aspal berhasil dihapus.');
    }

    /**
     * Download file Surat Permohonan Usulan Aspal
     */
    public function downloadSurat(Request $request, UsulanAspal $usulanAspal)
    {
        $user = $request->user();

        if ($user && $user->isKabupatenKota() && $user->kabupatenKota) {
            if (strcasecmp($usulanAspal->kabupaten_kota, $user->kabupatenKota->nama_kabupaten_kota) !== 0) {
                return redirect()->back()
                    ->with('error', 'Anda tidak memiliki akses untuk mengunduh surat permohonan Pemerintah Daerah lain.');
            }
        }

        if (!$usulanAspal->upload_surat_permohonan) {
            return redirect()->back()->with('error', 'File surat permohonan belum diunggah untuk usulan ini.');
        }

        $filePath = public_path('uploads/dokumen/usulan-aspal/' . $usulanAspal->upload_surat_permohonan);

        if (!File::exists($filePath)) {
            return redirect()->back()->with('error', 'File surat permohonan tidak ditemukan di server.');
        }

        return response()->download($filePath, $usulanAspal->upload_surat_permohonan);
    }
}
