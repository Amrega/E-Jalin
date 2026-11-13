<?php

namespace App\Http\Controllers;

use App\Models\KabupatenKota;
use App\Models\Jembatan;
use App\Models\RuasJalan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class JembatanController extends Controller
{
    /**
     * Path folder penyimpanan KML Jembatan: public/uploads/kml/jembatan
     */
    protected function getKmlUploadPath(): string
    {
        $path = public_path('uploads/kml/jembatan');
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true, true);
        }
        return $path;
    }

    /**
     * Tampilkan daftar jembatan beserta statistik dan filter
     */
    public function index(Request $request)
    {
        $query = Jembatan::with(['ruasJalan.kabupatenKota', 'ruasJalan.koridor']);

        // Filter Pencarian (Nama Jembatan, Nomor Jembatan, Nama Ruas, Kab/Kota)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('nama_jembatan', 'like', "%{$search}%")
                  ->orWhere('nomor_jembatan', 'like', "%{$search}%")
                  ->orWhereHas('ruasJalan', function ($rq) use ($search) {
                      $rq->where('nama_ruas', 'like', "%{$search}%")
                         ->orWhere('nomor_ruas', 'like', "%{$search}%")
                         ->orWhereHas('kabupatenKota', function ($kq) use ($search) {
                             $kq->where('nama_kabupaten_kota', 'like', "%{$search}%");
                         });
                  });
            });
        }

        // Filter Kabupaten / Kota (melalui relasi ruasJalan)
        if ($request->filled('kabupaten_kota_id')) {
            $query->whereHas('ruasJalan', function ($rq) use ($request) {
                $rq->where('kabupaten_kota_id', $request->kabupaten_kota_id);
            });
        }

        // Filter Ruas Jalan spesifik
        if ($request->filled('ruas_jalan_id')) {
            $query->where('ruas_jalan_id', $request->ruas_jalan_id);
        }

        // Filter Status Jembatan (Kabupaten/Kota, Non Status)
        if ($request->filled('status_jembatan')) {
            $query->where('status_jembatan', $request->status_jembatan);
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
        $jembatanList = $query->paginate($perPage)->withQueryString();

        // Data Statistik
        $totalJembatan = Jembatan::count();
        $totalPanjang  = Jembatan::where('void', 0)->sum('panjang_jembatan') ?? 0;
        $totalRuas     = Jembatan::distinct('ruas_jalan_id')->count('ruas_jalan_id');
        $totalAktif    = Jembatan::where('void', 0)->count();

        // Dropdown Daftar Kabupaten / Kota di Jawa Timur
        $kabupatenKotaList = KabupatenKota::where('void', 0)
            ->orderBy('nama_kabupaten_kota', 'asc')
            ->get();

        // Dropdown Ruas Jalan untuk filter bar
        $ruasFilterQuery = RuasJalan::where('void', 0);
        if ($request->filled('kabupaten_kota_id')) {
            $ruasFilterQuery->where('kabupaten_kota_id', $request->kabupaten_kota_id);
        }
        $ruasJalanList = $ruasFilterQuery->orderBy('nama_ruas', 'asc')->get();

        // Seluruh Ruas Jalan aktif untuk kebutuhan dependent dropdown dinamis di Modal Tambah & Edit
        $allRuasJalanList = RuasJalan::where('void', 0)
            ->select('id', 'kabupaten_kota_id', 'nama_ruas', 'nomor_ruas')
            ->orderBy('nama_ruas', 'asc')
            ->get();

        return view('jembatan.index', compact(
            'jembatanList',
            'kabupatenKotaList',
            'ruasJalanList',
            'allRuasJalanList',
            'totalJembatan',
            'totalPanjang',
            'totalRuas',
            'totalAktif'
        ));
    }

    /**
     * Simpan data jembatan baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ruas_jalan_id'    => ['required', 'exists:ruas_jalan,id'],
            'nama_jembatan'    => ['required', 'string', 'max:255'],
            'nomor_jembatan'   => ['nullable', 'string', 'max:100'],
            'panjang_jembatan' => ['nullable', 'numeric', 'min:0'],
            'status_jembatan'  => ['required', 'in:Kabupaten/Kota,Non Status'],
            'file_kml_jembatan'=> ['nullable', 'file', 'max:25600'], // max 25MB
            'void'             => ['nullable', 'in:0,1'],
        ], [
            'ruas_jalan_id.required'    => 'Ruas jalan wajib dipilih.',
            'ruas_jalan_id.exists'      => 'Ruas jalan yang dipilih tidak valid.',
            'nama_jembatan.required'    => 'Nama jembatan wajib diisi.',
            'status_jembatan.required'  => 'Status jembatan wajib dipilih.',
            'status_jembatan.in'        => 'Status jembatan harus "Kabupaten/Kota" atau "Non Status".',
            'panjang_jembatan.numeric'  => 'Panjang jembatan harus berupa angka desimal.',
            'file_kml_jembatan.max'     => 'Ukuran file KML jembatan maksimal 25MB.',
        ]);

        $fileName = null;
        if ($request->hasFile('file_kml_jembatan')) {
            $file = $request->file('file_kml_jembatan');
            $uploadPath = $this->getKmlUploadPath(); // uploads/kml/jembatan

            $rawName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . Str::slug($rawName) . '.' . ($extension ?: 'kml');
            $file->move($uploadPath, $fileName);
        }

        Jembatan::create([
            'ruas_jalan_id'    => $validated['ruas_jalan_id'],
            'nama_jembatan'    => $validated['nama_jembatan'],
            'nomor_jembatan'   => $validated['nomor_jembatan'] ?? null,
            'panjang_jembatan' => $validated['panjang_jembatan'] ?? null,
            'status_jembatan'  => $validated['status_jembatan'],
            'file_kml_jembatan'=> $fileName,
            'void'             => $request->has('void') ? (int) $request->void : 0,
        ]);

        return redirect()->route('jembatan.index')->with('success', 'Data jembatan berhasil ditambahkan.');
    }

    /**
     * Update data jembatan
     */
    public function update(Request $request, Jembatan $jembatan)
    {
        $validated = $request->validate([
            'ruas_jalan_id'    => ['required', 'exists:ruas_jalan,id'],
            'nama_jembatan'    => ['required', 'string', 'max:255'],
            'nomor_jembatan'   => ['nullable', 'string', 'max:100'],
            'panjang_jembatan' => ['nullable', 'numeric', 'min:0'],
            'status_jembatan'  => ['required', 'in:Kabupaten/Kota,Non Status'],
            'file_kml_jembatan'=> ['nullable', 'file', 'max:25600'],
            'void'             => ['nullable', 'in:0,1'],
        ], [
            'ruas_jalan_id.required'    => 'Ruas jalan wajib dipilih.',
            'ruas_jalan_id.exists'      => 'Ruas jalan yang dipilih tidak valid.',
            'nama_jembatan.required'    => 'Nama jembatan wajib diisi.',
            'status_jembatan.required'  => 'Status jembatan wajib dipilih.',
            'status_jembatan.in'        => 'Status jembatan harus "Kabupaten/Kota" atau "Non Status".',
            'panjang_jembatan.numeric'  => 'Panjang jembatan harus berupa angka.',
            'file_kml_jembatan.max'     => 'Ukuran file KML jembatan maksimal 25MB.',
        ]);

        $fileName = $jembatan->file_kml_jembatan;

        // Cek jika ada upload file KML baru
        if ($request->hasFile('file_kml_jembatan')) {
            $file = $request->file('file_kml_jembatan');
            $uploadPath = $this->getKmlUploadPath();

            // Hapus file lama jika ada
            if ($fileName && File::exists($uploadPath . '/' . $fileName)) {
                File::delete($uploadPath . '/' . $fileName);
            }

            $rawName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . Str::slug($rawName) . '.' . ($extension ?: 'kml');
            $file->move($uploadPath, $fileName);
        }

        $jembatan->update([
            'ruas_jalan_id'    => $validated['ruas_jalan_id'],
            'nama_jembatan'    => $validated['nama_jembatan'],
            'nomor_jembatan'   => $validated['nomor_jembatan'] ?? null,
            'panjang_jembatan' => $validated['panjang_jembatan'] ?? null,
            'status_jembatan'  => $validated['status_jembatan'],
            'file_kml_jembatan'=> $fileName,
            'void'             => $request->has('void') ? (int) $request->void : 0,
        ]);

        return redirect()->route('jembatan.index')->with('success', 'Data jembatan berhasil diperbarui.');
    }

    /**
     * Hapus data jembatan
     */
    public function destroy(Jembatan $jembatan)
    {
        // Hapus file kml fisik di uploads/kml/jembatan jika ada
        if ($jembatan->file_kml_jembatan) {
            $filePath = public_path('uploads/kml/jembatan/' . $jembatan->file_kml_jembatan);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        $jembatan->delete();

        return redirect()->route('jembatan.index')->with('success', 'Data jembatan berhasil dihapus.');
    }

    /**
     * Toggle status Void (0 = Aktif, 1 = Void / Non-aktif)
     */
    public function toggleVoid(Jembatan $jembatan)
    {
        $jembatan->void = $jembatan->void ? 0 : 1;
        $jembatan->save();

        $statusText = $jembatan->void ? 'ditandai sebagai Void (Non-Aktif)' : 'diaktifkan kembali';
        return redirect()->back()->with('success', "Jembatan {$jembatan->nama_jembatan} berhasil {$statusText}.");
    }

    /**
     * Download file KML jembatan dari uploads/kml/jembatan
     */
    public function downloadKml(Jembatan $jembatan)
    {
        if (!$jembatan->file_kml_jembatan) {
            return redirect()->back()->with('error', 'File KML tidak tersedia untuk jembatan ini.');
        }

        $filePath = public_path('uploads/kml/jembatan/' . $jembatan->file_kml_jembatan);

        if (!File::exists($filePath)) {
            return redirect()->back()->with('error', 'File KML fisik tidak ditemukan di server.');
        }

        return response()->download($filePath, $jembatan->file_kml_jembatan);
    }
}
