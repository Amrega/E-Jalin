<?php

namespace App\Http\Controllers;

use App\Models\JenisPenanganan;
use Illuminate\Http\Request;

class JenisPenangananController extends Controller
{
    /**
     * Tampilkan daftar referensi jenis penanganan
     */
    public function index(Request $request)
    {
        $query = JenisPenanganan::query();

        // Filter Pencarian Nama Penanganan
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('nama_penanganan', 'like', "%{$search}%");
        }

        // Filter Target Penanganan (jalan / jembatan)
        $targetFilter = $request->input('target_penangan') ?? $request->input('target_penanganan');
        if (!empty($targetFilter) && in_array($targetFilter, ['jalan', 'jembatan'])) {
            $query->where('target_penangan', $targetFilter);
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
        $query->orderBy('target_penangan', 'asc')->orderBy('id', 'asc');

        // Pagination
        $perPage = (int) $request->input('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 10;
        $jenisPenangananList = $query->paginate($perPage)->withQueryString();

        return view('jenis-penanganan.index', compact('jenisPenangananList'));
    }

    /**
     * Simpan data jenis penanganan baru
     */
    public function store(Request $request)
    {
        // Normalisasi input target_penangan / target_penanganan
        if (!$request->filled('target_penangan') && $request->filled('target_penanganan')) {
            $request->merge(['target_penangan' => $request->input('target_penanganan')]);
        }

        $validated = $request->validate([
            'target_penangan' => 'required|in:jalan,jembatan',
            'nama_penanganan' => 'required|string|max:255',
            'void'            => 'nullable|boolean',
        ], [
            'target_penangan.required' => 'Target penanganan (Jalan/Jembatan) wajib dipilih.',
            'target_penangan.in'       => 'Target penanganan harus berupa jalan atau jembatan.',
            'nama_penanganan.required' => 'Nama jenis penanganan wajib diisi.',
            'nama_penanganan.max'      => 'Nama jenis penanganan maksimal 255 karakter.',
        ]);

        JenisPenanganan::create([
            'target_penangan' => $validated['target_penangan'],
            'nama_penanganan' => trim($validated['nama_penanganan']),
            'void'            => $request->boolean('void', false) ? 1 : 0,
        ]);

        return redirect()->route('jenis-penanganan.index')
            ->with('success', 'Jenis penanganan baru berhasil ditambahkan.');
    }

    /**
     * Update data jenis penanganan
     */
    public function update(Request $request, JenisPenanganan $jenisPenanganan)
    {
        // Normalisasi input target_penangan / target_penanganan
        if (!$request->filled('target_penangan') && $request->filled('target_penanganan')) {
            $request->merge(['target_penangan' => $request->input('target_penanganan')]);
        }

        $validated = $request->validate([
            'target_penangan' => 'required|in:jalan,jembatan',
            'nama_penanganan' => 'required|string|max:255',
            'void'            => 'nullable|boolean',
        ], [
            'target_penangan.required' => 'Target penanganan (Jalan/Jembatan) wajib dipilih.',
            'target_penangan.in'       => 'Target penanganan harus berupa jalan atau jembatan.',
            'nama_penanganan.required' => 'Nama jenis penanganan wajib diisi.',
            'nama_penanganan.max'      => 'Nama jenis penanganan maksimal 255 karakter.',
        ]);

        $jenisPenanganan->update([
            'target_penangan' => $validated['target_penangan'],
            'nama_penanganan' => trim($validated['nama_penanganan']),
            'void'            => $request->boolean('void', false) ? 1 : 0,
        ]);

        return redirect()->route('jenis-penanganan.index')
            ->with('success', 'Data jenis penanganan berhasil diperbarui.');
    }

    /**
     * Hapus data jenis penanganan
     */
    public function destroy(JenisPenanganan $jenisPenanganan)
    {
        $nama = $jenisPenanganan->nama_penanganan;
        $jenisPenanganan->delete();

        return redirect()->route('jenis-penanganan.index')
            ->with('success', "Jenis penanganan \"{$nama}\" berhasil dihapus.");
    }

    /**
     * Ubah status void (aktif/non-aktif) jenis penanganan
     */
    public function toggleVoid(JenisPenanganan $jenisPenanganan)
    {
        $jenisPenanganan->void = !$jenisPenanganan->void;
        $jenisPenanganan->save();

        $statusText = $jenisPenanganan->void ? 'dinonaktifkan (Void)' : 'diaktifkan kembali';
        return redirect()->back()
            ->with('success', "Jenis penanganan \"{$jenisPenanganan->nama_penanganan}\" berhasil {$statusText}.");
    }
}
