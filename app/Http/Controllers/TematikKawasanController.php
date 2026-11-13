<?php

namespace App\Http\Controllers;

use App\Models\TematikKawasan;
use Illuminate\Http\Request;

class TematikKawasanController extends Controller
{
    /**
     * Tampilkan daftar referensi tematik kawasan
     */
    public function index(Request $request)
    {
        $query = TematikKawasan::query();

        // Filter Pencarian
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('nama_tematik', 'like', "%{$search}%");
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
        $query->orderBy('id', 'asc');

        // Pagination
        $perPage = (int) $request->input('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 10;
        $tematikList = $query->paginate($perPage)->withQueryString();

        return view('tematik-kawasan.index', compact('tematikList'));
    }

    /**
     * Simpan data tematik kawasan baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_tematik' => ['required', 'string', 'max:255', 'unique:tematik_kawasan,nama_tematik'],
            'void'         => ['nullable', 'in:0,1'],
        ], [
            'nama_tematik.required' => 'Nama tematik kawasan wajib diisi.',
            'nama_tematik.unique'   => 'Nama tematik kawasan tersebut sudah ada.',
            'nama_tematik.max'      => 'Nama tematik kawasan maksimal 255 karakter.',
        ]);

        TematikKawasan::create([
            'nama_tematik' => trim($validated['nama_tematik']),
            'void'         => $request->has('void') ? (int) $request->void : 0,
        ]);

        return redirect()->route('tematik-kawasan.index')->with('success', 'Data tematik kawasan berhasil ditambahkan.');
    }

    /**
     * Update data tematik kawasan
     */
    public function update(Request $request, TematikKawasan $tematikKawasan)
    {
        $validated = $request->validate([
            'nama_tematik' => ['required', 'string', 'max:255', 'unique:tematik_kawasan,nama_tematik,' . $tematikKawasan->id],
            'void'         => ['nullable', 'in:0,1'],
        ], [
            'nama_tematik.required' => 'Nama tematik kawasan wajib diisi.',
            'nama_tematik.unique'   => 'Nama tematik kawasan tersebut sudah digunakan.',
            'nama_tematik.max'      => 'Nama tematik kawasan maksimal 255 karakter.',
        ]);

        $tematikKawasan->update([
            'nama_tematik' => trim($validated['nama_tematik']),
            'void'         => $request->has('void') ? (int) $request->void : 0,
        ]);

        return redirect()->route('tematik-kawasan.index')->with('success', 'Data tematik kawasan berhasil diperbarui.');
    }

    /**
     * Hapus data tematik kawasan
     */
    public function destroy(TematikKawasan $tematikKawasan)
    {
        $tematikKawasan->delete();

        return redirect()->route('tematik-kawasan.index')->with('success', 'Data tematik kawasan berhasil dihapus.');
    }

    /**
     * Toggle status Void (0 = Aktif, 1 = Void / Non-aktif)
     */
    public function toggleVoid(TematikKawasan $tematikKawasan)
    {
        $tematikKawasan->void = $tematikKawasan->void ? 0 : 1;
        $tematikKawasan->save();

        $statusText = $tematikKawasan->void ? 'ditandai sebagai Void (Non-Aktif)' : 'diaktifkan kembali';
        return redirect()->back()->with('success', "Tematik kawasan {$tematikKawasan->nama_tematik} berhasil {$statusText}.");
    }
}
