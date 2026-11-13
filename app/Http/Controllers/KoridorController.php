<?php

namespace App\Http\Controllers;

use App\Models\KabupatenKota;
use App\Models\Koridor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class KoridorController extends Controller
{
    /**
     * Path folder penyimpanan KML Koridor: public/uploads/kml/koridor
     */
    protected function getKmlUploadPath(): string
    {
        $path = public_path('uploads/kml/koridor');
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true, true);
        }
        return $path;
    }

    /**
     * Tampilkan daftar koridor beserta statistik dan filter
     */
    public function index(Request $request)
    {
        $query = Koridor::with('kabupatenKota');

        // Filter Pencarian
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('nama_koridor', 'like', "%{$search}%")
                  ->orWhere('nomor_koridor', 'like', "%{$search}%")
                  ->orWhereHas('kabupatenKota', function ($kq) use ($search) {
                      $kq->where('nama_kabupaten_kota', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Kabupaten / Kota
        if ($request->filled('kabupaten_kota_id')) {
            $query->where('kabupaten_kota_id', $request->kabupaten_kota_id);
        }

        // Filter Status Void
        if ($request->filled('status')) {
            if ($request->status === 'aktif') {
                $query->where('void', 0);
            } elseif ($request->status === 'void') {
                $query->where('void', 1);
            }
        }

        // Urutan
        $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');

        // Pagination
        $perPage = (int) $request->input('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 10;
        $koridors = $query->paginate($perPage)->withQueryString();

        // Data Statistik
        $totalKoridor = Koridor::count();
        $totalPanjang = Koridor::where('void', 0)->sum('panjang_koridor') ?? 0;
        $totalKabKota = Koridor::distinct('kabupaten_kota_id')->count('kabupaten_kota_id');
        $totalAktif   = Koridor::where('void', 0)->count();

        // Dropdown Daftar Kabupaten / Kota di Jawa Timur
        $kabupatenKotaList = KabupatenKota::where('void', 0)
            ->orderBy('nama_kabupaten_kota', 'asc')
            ->get();

        return view('koridor.index', compact(
            'koridors',
            'kabupatenKotaList',
            'totalKoridor',
            'totalPanjang',
            'totalKabKota',
            'totalAktif'
        ));
    }

    /**
     * Simpan data koridor baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kabupaten_kota_id' => ['required', 'exists:kabupaten_kota,id'],
            'nama_koridor'      => ['required', 'string', 'max:255'],
            'nomor_koridor'     => ['nullable', 'string', 'max:100'],
            'panjang_koridor'   => ['nullable', 'numeric', 'min:0'],
            'file_kml_koridor'  => ['nullable', 'file', 'max:25600'], // max 25MB
            'void'              => ['nullable', 'in:0,1'],
        ], [
            'kabupaten_kota_id.required' => 'Kabupaten / Kota wajib dipilih.',
            'kabupaten_kota_id.exists'   => 'Kabupaten / Kota tidak valid.',
            'nama_koridor.required'      => 'Nama koridor wajib diisi.',
            'panjang_koridor.numeric'    => 'Panjang koridor harus berupa angka.',
            'file_kml_koridor.max'       => 'Ukuran file KML maksimal 25MB.',
        ]);

        $fileName = null;
        if ($request->hasFile('file_kml_koridor')) {
            $file = $request->file('file_kml_koridor');
            $uploadPath = $this->getKmlUploadPath(); // uploads/kml/koridor

            $rawName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . Str::slug($rawName) . '.' . ($extension ?: 'kml');
            $file->move($uploadPath, $fileName);
        }

        Koridor::create([
            'kabupaten_kota_id' => $validated['kabupaten_kota_id'],
            'nama_koridor'      => $validated['nama_koridor'],
            'nomor_koridor'     => $validated['nomor_koridor'] ?? null,
            'panjang_koridor'   => $validated['panjang_koridor'] ?? null,
            'file_kml_koridor'  => $fileName,
            'void'              => $request->has('void') ? (int) $request->void : 0,
        ]);

        return redirect()->route('koridor.index')->with('success', 'Data koridor berhasil ditambahkan.');
    }

    /**
     * Update data koridor
     */
    public function update(Request $request, Koridor $koridor)
    {
        $validated = $request->validate([
            'kabupaten_kota_id' => ['required', 'exists:kabupaten_kota,id'],
            'nama_koridor'      => ['required', 'string', 'max:255'],
            'nomor_koridor'     => ['nullable', 'string', 'max:100'],
            'panjang_koridor'   => ['nullable', 'numeric', 'min:0'],
            'file_kml_koridor'  => ['nullable', 'file', 'max:25600'],
            'void'              => ['nullable', 'in:0,1'],
        ], [
            'kabupaten_kota_id.required' => 'Kabupaten / Kota wajib dipilih.',
            'kabupaten_kota_id.exists'   => 'Kabupaten / Kota tidak valid.',
            'nama_koridor.required'      => 'Nama koridor wajib diisi.',
            'panjang_koridor.numeric'    => 'Panjang koridor harus berupa angka.',
        ]);

        $fileName = $koridor->file_kml_koridor;

        // Cek jika ada upload file KML baru
        if ($request->hasFile('file_kml_koridor')) {
            $file = $request->file('file_kml_koridor');
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

        $koridor->update([
            'kabupaten_kota_id' => $validated['kabupaten_kota_id'],
            'nama_koridor'      => $validated['nama_koridor'],
            'nomor_koridor'     => $validated['nomor_koridor'] ?? null,
            'panjang_koridor'   => $validated['panjang_koridor'] ?? null,
            'file_kml_koridor'  => $fileName,
            'void'              => $request->has('void') ? (int) $request->void : 0,
        ]);

        return redirect()->route('koridor.index')->with('success', 'Data koridor berhasil diperbarui.');
    }

    /**
     * Hapus data koridor
     */
    public function destroy(Koridor $koridor)
    {
        // Hapus file kml fisik di uploads/kml/koridor jika ada
        if ($koridor->file_kml_koridor) {
            $filePath = public_path('uploads/kml/koridor/' . $koridor->file_kml_koridor);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        $koridor->delete();

        return redirect()->route('koridor.index')->with('success', 'Data koridor berhasil dihapus.');
    }

    /**
     * Toggle status Void (0 = Aktif, 1 = Void / Non-aktif)
     */
    public function toggleVoid(Koridor $koridor)
    {
        $koridor->void = $koridor->void ? 0 : 1;
        $koridor->save();

        $statusText = $koridor->void ? 'ditandai sebagai Void (Non-Aktif)' : 'diaktifkan kembali';
        return redirect()->back()->with('success', "Koridor {$koridor->nama_koridor} berhasil {$statusText}.");
    }

    /**
     * Download file KML koridor dari uploads/kml/koridor
     */
    public function downloadKml(Koridor $koridor)
    {
        if (!$koridor->file_kml_koridor) {
            return redirect()->back()->with('error', 'File KML tidak tersedia untuk koridor ini.');
        }

        $filePath = public_path('uploads/kml/koridor/' . $koridor->file_kml_koridor);

        if (!File::exists($filePath)) {
            return redirect()->back()->with('error', 'File KML fisik tidak ditemukan di server.');
        }

        return response()->download($filePath, $koridor->file_kml_koridor);
    }
}
