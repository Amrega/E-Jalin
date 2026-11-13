<?php

namespace App\Http\Controllers;

use App\Models\KabupatenKota;
use App\Models\Koridor;
use App\Models\RuasJalan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class RuasJalanController extends Controller
{
    /**
     * Path folder penyimpanan KML Ruas Jalan: public/uploads/kml/jalan
     */
    protected function getKmlUploadPath(): string
    {
        $path = public_path('uploads/kml/jalan');
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true, true);
        }
        return $path;
    }

    /**
     * Tampilkan daftar ruas jalan beserta statistik dan filter
     */
    public function index(Request $request)
    {
        $query = RuasJalan::with(['kabupatenKota', 'koridor']);

        // Filter Pencarian (Nama Ruas, Nomor Ruas, Nama Kab/Kota, Nama Koridor)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('nama_ruas', 'like', "%{$search}%")
                  ->orWhere('nomor_ruas', 'like', "%{$search}%")
                  ->orWhereHas('kabupatenKota', function ($kq) use ($search) {
                      $kq->where('nama_kabupaten_kota', 'like', "%{$search}%");
                  })
                  ->orWhereHas('koridor', function ($cq) use ($search) {
                      $cq->where('nama_koridor', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Kabupaten / Kota
        if ($request->filled('kabupaten_kota_id')) {
            $query->where('kabupaten_kota_id', $request->kabupaten_kota_id);
        }

        // Filter Koridor (bisa spesifik ID atau "none" untuk Non-Koridor)
        if ($request->filled('koridor_id')) {
            if ($request->koridor_id === 'none') {
                $query->whereNull('koridor_id');
            } else {
                $query->where('koridor_id', $request->koridor_id);
            }
        }

        // Filter Status Jalan (Kabupaten/Kota, Non Status)
        if ($request->filled('status_jalan')) {
            $query->where('status_jalan', $request->status_jalan);
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
        $ruasJalanList = $query->paginate($perPage)->withQueryString();

        // Data Statistik
        $totalRuas    = RuasJalan::count();
        $totalPanjang = RuasJalan::where('void', 0)->sum('panjang_ruas') ?? 0;
        $totalKabKota = RuasJalan::distinct('kabupaten_kota_id')->count('kabupaten_kota_id');
        $totalAktif   = RuasJalan::where('void', 0)->count();

        // Dropdown Daftar Kabupaten / Kota di Jawa Timur
        $kabupatenKotaList = KabupatenKota::where('void', 0)
            ->orderBy('nama_kabupaten_kota', 'asc')
            ->get();

        // Dropdown Koridor untuk filter bar (disaring jika kab/kota tertentu dipilih)
        $koridorFilterQuery = Koridor::where('void', 0);
        if ($request->filled('kabupaten_kota_id')) {
            $koridorFilterQuery->where('kabupaten_kota_id', $request->kabupaten_kota_id);
        }
        $koridorList = $koridorFilterQuery->orderBy('nama_koridor', 'asc')->get();

        // Seluruh Koridor aktif untuk kebutuhan dinamis di Modal Tambah & Edit
        $allKoridorList = Koridor::where('void', 0)
            ->select('id', 'kabupaten_kota_id', 'nama_koridor', 'nomor_koridor')
            ->orderBy('nama_koridor', 'asc')
            ->get();

        return view('ruas.index', compact(
            'ruasJalanList',
            'kabupatenKotaList',
            'koridorList',
            'allKoridorList',
            'totalRuas',
            'totalPanjang',
            'totalKabKota',
            'totalAktif'
        ));
    }

    /**
     * Simpan data ruas jalan baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kabupaten_kota_id' => ['required', 'exists:kabupaten_kota,id'],
            'koridor_id'        => ['nullable', 'exists:koridor,id'],
            'nama_ruas'         => ['required', 'string', 'max:255'],
            'nomor_ruas'        => ['nullable', 'string', 'max:100'],
            'panjang_ruas'      => ['nullable', 'numeric', 'min:0'],
            'status_jalan'      => ['required', 'in:Kabupaten/Kota,Non Status'],
            'file_kml_ruas'     => ['nullable', 'file', 'max:25600'], // max 25MB
            'void'              => ['nullable', 'in:0,1'],
        ], [
            'kabupaten_kota_id.required' => 'Kabupaten / Kota wajib dipilih.',
            'kabupaten_kota_id.exists'   => 'Kabupaten / Kota tidak valid.',
            'koridor_id.exists'          => 'Koridor yang dipilih tidak valid.',
            'nama_ruas.required'         => 'Nama ruas jalan wajib diisi.',
            'status_jalan.required'      => 'Status jalan wajib dipilih.',
            'status_jalan.in'            => 'Status jalan harus "Kabupaten/Kota" atau "Non Status".',
            'panjang_ruas.numeric'       => 'Panjang ruas harus berupa angka desimal.',
            'file_kml_ruas.max'          => 'Ukuran file KML maksimal 25MB.',
        ]);

        $fileName = null;
        if ($request->hasFile('file_kml_ruas')) {
            $file = $request->file('file_kml_ruas');
            $uploadPath = $this->getKmlUploadPath(); // uploads/kml/jalan

            $rawName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . Str::slug($rawName) . '.' . ($extension ?: 'kml');
            $file->move($uploadPath, $fileName);
        }

        RuasJalan::create([
            'kabupaten_kota_id' => $validated['kabupaten_kota_id'],
            'koridor_id'        => $validated['koridor_id'] ?? null,
            'nama_ruas'         => $validated['nama_ruas'],
            'nomor_ruas'        => $validated['nomor_ruas'] ?? null,
            'panjang_ruas'      => $validated['panjang_ruas'] ?? null,
            'status_jalan'      => $validated['status_jalan'],
            'file_kml_ruas'     => $fileName,
            'void'              => $request->has('void') ? (int) $request->void : 0,
        ]);

        return redirect()->route('ruas.index')->with('success', 'Data ruas jalan berhasil ditambahkan.');
    }

    /**
     * Update data ruas jalan
     */
    public function update(Request $request, RuasJalan $ruas)
    {
        $validated = $request->validate([
            'kabupaten_kota_id' => ['required', 'exists:kabupaten_kota,id'],
            'koridor_id'        => ['nullable', 'exists:koridor,id'],
            'nama_ruas'         => ['required', 'string', 'max:255'],
            'nomor_ruas'        => ['nullable', 'string', 'max:100'],
            'panjang_ruas'      => ['nullable', 'numeric', 'min:0'],
            'status_jalan'      => ['required', 'in:Kabupaten/Kota,Non Status'],
            'file_kml_ruas'     => ['nullable', 'file', 'max:25600'],
            'void'              => ['nullable', 'in:0,1'],
        ], [
            'kabupaten_kota_id.required' => 'Kabupaten / Kota wajib dipilih.',
            'kabupaten_kota_id.exists'   => 'Kabupaten / Kota tidak valid.',
            'koridor_id.exists'          => 'Koridor yang dipilih tidak valid.',
            'nama_ruas.required'         => 'Nama ruas jalan wajib diisi.',
            'status_jalan.required'      => 'Status jalan wajib dipilih.',
            'status_jalan.in'            => 'Status jalan harus "Kabupaten/Kota" atau "Non Status".',
            'panjang_ruas.numeric'       => 'Panjang ruas harus berupa angka.',
            'file_kml_ruas.max'          => 'Ukuran file KML maksimal 25MB.',
        ]);

        $fileName = $ruas->file_kml_ruas;

        // Cek jika ada upload file KML baru
        if ($request->hasFile('file_kml_ruas')) {
            $file = $request->file('file_kml_ruas');
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

        $ruas->update([
            'kabupaten_kota_id' => $validated['kabupaten_kota_id'],
            'koridor_id'        => $validated['koridor_id'] ?? null,
            'nama_ruas'         => $validated['nama_ruas'],
            'nomor_ruas'        => $validated['nomor_ruas'] ?? null,
            'panjang_ruas'      => $validated['panjang_ruas'] ?? null,
            'status_jalan'      => $validated['status_jalan'],
            'file_kml_ruas'     => $fileName,
            'void'              => $request->has('void') ? (int) $request->void : 0,
        ]);

        return redirect()->route('ruas.index')->with('success', 'Data ruas jalan berhasil diperbarui.');
    }

    /**
     * Hapus data ruas jalan
     */
    public function destroy(RuasJalan $ruas)
    {
        // Hapus file kml fisik di uploads/kml/jalan jika ada
        if ($ruas->file_kml_ruas) {
            $filePath = public_path('uploads/kml/jalan/' . $ruas->file_kml_ruas);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        $ruas->delete();

        return redirect()->route('ruas.index')->with('success', 'Data ruas jalan berhasil dihapus.');
    }

    /**
     * Toggle status Void (0 = Aktif, 1 = Void / Non-aktif)
     */
    public function toggleVoid(RuasJalan $ruas)
    {
        $ruas->void = $ruas->void ? 0 : 1;
        $ruas->save();

        $statusText = $ruas->void ? 'ditandai sebagai Void (Non-Aktif)' : 'diaktifkan kembali';
        return redirect()->back()->with('success', "Ruas {$ruas->nama_ruas} berhasil {$statusText}.");
    }

    /**
     * Download file KML ruas dari uploads/kml/jalan
     */
    public function downloadKml(RuasJalan $ruas)
    {
        if (!$ruas->file_kml_ruas) {
            return redirect()->back()->with('error', 'File KML tidak tersedia untuk ruas jalan ini.');
        }

        $filePath = public_path('uploads/kml/jalan/' . $ruas->file_kml_ruas);

        if (!File::exists($filePath)) {
            return redirect()->back()->with('error', 'File KML fisik tidak ditemukan di server.');
        }

        return response()->download($filePath, $ruas->file_kml_ruas);
    }
}
