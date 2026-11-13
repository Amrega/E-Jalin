<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use App\Models\UptDinas;
use Illuminate\Http\Request;

class UptDinasController extends Controller
{
    /**
     * Tampilkan daftar referensi UPT PJJ
     */
    public function index(Request $request)
    {
        $query = UptDinas::with(['provinsi', 'kabupatenKota' => function ($q) {
            $q->orderBy('nama_kabupaten_kota', 'asc');
        }])->withCount('kabupatenKota');

        // Filter Pencarian
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('nama_upt', 'like', "%{$search}%");
        }

        // Filter Status Void (Aktif / Void)
        if ($request->filled('status')) {
            if ($request->status === 'aktif') {
                $query->where('void', 0);
            } elseif ($request->status === 'void') {
                $query->where('void', 1);
            }
        }

        // Urutan
        $query->orderBy('id', 'asc');

        // Pagination
        $perPage = (int) $request->input('per_page', 15);
        $perPage = in_array($perPage, [10, 15, 25, 50, 100]) ? $perPage : 15;
        $uptList = $query->paginate($perPage)->withQueryString();

        // Data referensi provinsi
        $provinsiList = Provinsi::where('void', 0)->orderBy('nama_provinsi')->get();
        if ($provinsiList->isEmpty()) {
            $provinsiList = Provinsi::orderBy('nama_provinsi')->get();
        }

        return view('upt-dinas.index', compact('uptList', 'provinsiList'));
    }

    /**
     * Simpan data UPT PJJ baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_upt'    => 'required|string|max:150|unique:upt_dinas,nama_upt',
            'provinsi_id' => 'required|exists:provinsi,id',
            'void'        => 'nullable|boolean',
        ], [
            'nama_upt.required'    => 'Nama UPT PJJ wajib diisi.',
            'nama_upt.unique'      => 'Nama UPT PJJ tersebut sudah terdaftar.',
            'nama_upt.max'         => 'Nama UPT PJJ maksimal 150 karakter.',
            'provinsi_id.required' => 'Provinsi wajib dipilih.',
            'provinsi_id.exists'   => 'Provinsi yang dipilih tidak valid.',
        ]);

        UptDinas::create([
            'nama_upt'    => trim($validated['nama_upt']),
            'provinsi_id' => $validated['provinsi_id'],
            'void'        => $request->boolean('void', false) ? 1 : 0,
        ]);

        return redirect()->route('upt-dinas.index')
            ->with('success', "UPT PJJ \"{$validated['nama_upt']}\" berhasil ditambahkan.");
    }

    /**
     * Update data UPT PJJ
     */
    public function update(Request $request, UptDinas $uptDinas)
    {
        $validated = $request->validate([
            'nama_upt'    => 'required|string|max:150|unique:upt_dinas,nama_upt,' . $uptDinas->id,
            'provinsi_id' => 'required|exists:provinsi,id',
            'void'        => 'nullable|boolean',
        ], [
            'nama_upt.required'    => 'Nama UPT PJJ wajib diisi.',
            'nama_upt.unique'      => 'Nama UPT PJJ tersebut sudah terdaftar.',
            'nama_upt.max'         => 'Nama UPT PJJ maksimal 150 karakter.',
            'provinsi_id.required' => 'Provinsi wajib dipilih.',
            'provinsi_id.exists'   => 'Provinsi yang dipilih tidak valid.',
        ]);

        $uptDinas->update([
            'nama_upt'    => trim($validated['nama_upt']),
            'provinsi_id' => $validated['provinsi_id'],
            'void'        => $request->boolean('void', false) ? 1 : 0,
        ]);

        return redirect()->route('upt-dinas.index')
            ->with('success', "Data UPT PJJ \"{$uptDinas->nama_upt}\" berhasil diperbarui.");
    }

    /**
     * Hapus data UPT PJJ
     */
    public function destroy(UptDinas $uptDinas)
    {
        $kabupatenCount = $uptDinas->kabupatenKota()->count();

        if ($kabupatenCount > 0) {
            $msg = "UPT PJJ \"{$uptDinas->nama_upt}\" tidak dapat dihapus karena masih menaungi {$kabupatenCount} Kabupaten / Kota. Silakan gunakan opsi Non-Aktifkan (Void) jika UPT ini tidak aktif lagi.";
            return redirect()->route('upt-dinas.index')->with('error', $msg);
        }

        $nama = $uptDinas->nama_upt;
        $uptDinas->delete();

        return redirect()->route('upt-dinas.index')
            ->with('success', "UPT PJJ \"{$nama}\" berhasil dihapus.");
    }

    /**
     * Ubah status void (aktif/non-aktif)
     */
    public function toggleVoid(UptDinas $uptDinas)
    {
        $uptDinas->void = !$uptDinas->void;
        $uptDinas->save();

        $statusText = $uptDinas->void ? 'dinonaktifkan (Void)' : 'diaktifkan kembali';
        return redirect()->back()
            ->with('success', "Status UPT PJJ \"{$uptDinas->nama_upt}\" berhasil {$statusText}.");
    }
}
