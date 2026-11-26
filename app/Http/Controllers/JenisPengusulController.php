<?php

namespace App\Http\Controllers;

use App\Models\JenisPengusul;
use Illuminate\Http\Request;

class JenisPengusulController extends Controller
{
    /**
     * Tampilkan daftar referensi jenis pengusul
     */
    public function index(Request $request)
    {
        $query = JenisPengusul::query();

        // Filter Pencarian
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('nama_jenis_pengusul', 'like', "%{$search}%");
        }

        // Urutan data
        $query->orderBy('id', 'asc');

        // Pagination
        $perPage = (int) $request->input('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 10;
        $jenisPengusulList = $query->paginate($perPage)->withQueryString();

        return view('jenis-pengusul.index', compact('jenisPengusulList'));
    }

    /**
     * Simpan data jenis pengusul baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_jenis_pengusul' => ['required', 'string', 'max:255', 'unique:jenis_pengusul,nama_jenis_pengusul'],
        ], [
            'nama_jenis_pengusul.required' => 'Nama jenis pengusul wajib diisi.',
            'nama_jenis_pengusul.unique'   => 'Nama jenis pengusul tersebut sudah ada.',
            'nama_jenis_pengusul.max'      => 'Nama jenis pengusul maksimal 255 karakter.',
        ]);

        JenisPengusul::create([
            'nama_jenis_pengusul' => trim($validated['nama_jenis_pengusul']),
        ]);

        return redirect()->route('jenis-pengusul.index')
            ->with('success', 'Data jenis pengusul berhasil ditambahkan.');
    }

    /**
     * Update data jenis pengusul
     */
    public function update(Request $request, JenisPengusul $jenisPengusul)
    {
        $validated = $request->validate([
            'nama_jenis_pengusul' => ['required', 'string', 'max:255', 'unique:jenis_pengusul,nama_jenis_pengusul,' . $jenisPengusul->id],
        ], [
            'nama_jenis_pengusul.required' => 'Nama jenis pengusul wajib diisi.',
            'nama_jenis_pengusul.unique'   => 'Nama jenis pengusul tersebut sudah digunakan.',
            'nama_jenis_pengusul.max'      => 'Nama jenis pengusul maksimal 255 karakter.',
        ]);

        $jenisPengusul->update([
            'nama_jenis_pengusul' => trim($validated['nama_jenis_pengusul']),
        ]);

        return redirect()->route('jenis-pengusul.index')
            ->with('success', 'Data jenis pengusul berhasil diperbarui.');
    }

    /**
     * Hapus data jenis pengusul
     */
    public function destroy(JenisPengusul $jenisPengusul)
    {
        $pengusulCount = $jenisPengusul->pengusul()->count();
        if ($pengusulCount > 0) {
            return redirect()->route('jenis-pengusul.index')
                ->with('error', "Jenis pengusul \"{$jenisPengusul->nama_jenis_pengusul}\" tidak dapat dihapus karena masih digunakan oleh {$pengusulCount} data Pengusul.");
        }

        $usulanAspalCount = $jenisPengusul->usulanAspal()->count();
        if ($usulanAspalCount > 0) {
            return redirect()->route('jenis-pengusul.index')
                ->with('error', "Jenis pengusul \"{$jenisPengusul->nama_jenis_pengusul}\" tidak dapat dihapus karena masih digunakan pada {$usulanAspalCount} data Usulan Aspal.");
        }

        $nama = $jenisPengusul->nama_jenis_pengusul;
        $jenisPengusul->delete();

        return redirect()->route('jenis-pengusul.index')
            ->with('success', "Data jenis pengusul \"{$nama}\" berhasil dihapus.");
    }
}
