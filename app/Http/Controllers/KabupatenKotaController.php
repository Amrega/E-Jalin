<?php

namespace App\Http\Controllers;

use App\Models\KabupatenKota;
use App\Models\Provinsi;
use App\Models\UptDinas;
use Illuminate\Http\Request;

class KabupatenKotaController extends Controller
{
    /**
     * Tampilkan daftar referensi Kabupaten / Kota
     */
    public function index(Request $request)
    {
        $query = KabupatenKota::with(['provinsi', 'uptDinas'])
            ->withCount(['ruasJalan', 'koridor']);

        // Filter Pencarian Nama Kabupaten / Kota
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('nama_kabupaten_kota', 'like', "%{$search}%");
        }

        // Filter UPT PJJ
        if ($request->filled('upt_id')) {
            if ($request->upt_id === 'none') {
                $query->whereNull('upt_id');
            } else {
                $query->where('upt_id', $request->upt_id);
            }
        }

        // Filter Status Void (Aktif / Void)
        if ($request->filled('status')) {
            if ($request->status === 'aktif') {
                $query->where('void', 0);
            } elseif ($request->status === 'void') {
                $query->where('void', 1);
            }
        }

        // Urutan default alfabetis nama kabupaten/kota
        $query->orderBy('nama_kabupaten_kota', 'asc');

        // Pagination
        $perPage = (int) $request->input('per_page', 25);
        $perPage = in_array($perPage, [10, 25, 38, 50, 100]) ? $perPage : 25;
        $kabupatenKotaList = $query->paginate($perPage)->withQueryString();

        // Data master untuk opsi filter & form modal
        $provinsiList = Provinsi::where('void', 0)->orderBy('nama_provinsi')->get();
        if ($provinsiList->isEmpty()) {
            $provinsiList = Provinsi::orderBy('nama_provinsi')->get();
        }

        $uptList = UptDinas::where('void', 0)->orderBy('nama_upt')->get();
        if ($uptList->isEmpty()) {
            $uptList = UptDinas::orderBy('nama_upt')->get();
        }

        return view('kabupaten-kota.index', compact('kabupatenKotaList', 'provinsiList', 'uptList'));
    }

    /**
     * Simpan data Kabupaten / Kota baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kabupaten_kota' => 'required|string|max:100|unique:kabupaten_kota,nama_kabupaten_kota',
            'provinsi_id'         => 'required|exists:provinsi,id',
            'upt_id'              => 'nullable|exists:upt_dinas,id',
            'void'                => 'nullable|boolean',
        ], [
            'nama_kabupaten_kota.required' => 'Nama Kabupaten / Kota wajib diisi.',
            'nama_kabupaten_kota.unique'   => 'Nama Kabupaten / Kota tersebut sudah terdaftar.',
            'nama_kabupaten_kota.max'      => 'Nama Kabupaten / Kota maksimal 100 karakter.',
            'provinsi_id.required'         => 'Provinsi wajib dipilih.',
            'provinsi_id.exists'           => 'Provinsi yang dipilih tidak valid.',
            'upt_id.exists'                => 'UPT PJJ yang dipilih tidak valid.',
        ]);

        KabupatenKota::create([
            'nama_kabupaten_kota' => trim($validated['nama_kabupaten_kota']),
            'provinsi_id'         => $validated['provinsi_id'],
            'upt_id'              => $validated['upt_id'] ?: null,
            'void'                => $request->boolean('void', false) ? 1 : 0,
        ]);

        return redirect()->route('kabupaten-kota.index')
            ->with('success', "Kabupaten / Kota \"{$validated['nama_kabupaten_kota']}\" berhasil ditambahkan.");
    }

    /**
     * Update data Kabupaten / Kota
     */
    public function update(Request $request, KabupatenKota $kabupatenKota)
    {
        $validated = $request->validate([
            'nama_kabupaten_kota' => 'required|string|max:100|unique:kabupaten_kota,nama_kabupaten_kota,' . $kabupatenKota->id,
            'provinsi_id'         => 'required|exists:provinsi,id',
            'upt_id'              => 'nullable|exists:upt_dinas,id',
            'void'                => 'nullable|boolean',
        ], [
            'nama_kabupaten_kota.required' => 'Nama Kabupaten / Kota wajib diisi.',
            'nama_kabupaten_kota.unique'   => 'Nama Kabupaten / Kota tersebut sudah terdaftar.',
            'nama_kabupaten_kota.max'      => 'Nama Kabupaten / Kota maksimal 100 karakter.',
            'provinsi_id.required'         => 'Provinsi wajib dipilih.',
            'provinsi_id.exists'           => 'Provinsi yang dipilih tidak valid.',
            'upt_id.exists'                => 'UPT PJJ yang dipilih tidak valid.',
        ]);

        $kabupatenKota->update([
            'nama_kabupaten_kota' => trim($validated['nama_kabupaten_kota']),
            'provinsi_id'         => $validated['provinsi_id'],
            'upt_id'              => $validated['upt_id'] ?: null,
            'void'                => $request->boolean('void', false) ? 1 : 0,
        ]);

        return redirect()->route('kabupaten-kota.index')
            ->with('success', "Data Kabupaten / Kota \"{$kabupatenKota->nama_kabupaten_kota}\" berhasil diperbarui.");
    }

    /**
     * Hapus data Kabupaten / Kota
     */
    public function destroy(KabupatenKota $kabupatenKota)
    {
        $ruasCount = $kabupatenKota->ruasJalan()->count();
        $koridorCount = $kabupatenKota->koridor()->count();

        if ($ruasCount > 0 || $koridorCount > 0) {
            $msg = "Kabupaten / Kota \"{$kabupatenKota->nama_kabupaten_kota}\" tidak dapat dihapus karena masih terhubung dengan {$ruasCount} data Ruas Jalan dan {$koridorCount} data Koridor. Silakan gunakan fitur Non-Aktifkan (Void) jika wilayah ini tidak digunakan.";
            return redirect()->route('kabupaten-kota.index')->with('error', $msg);
        }

        $nama = $kabupatenKota->nama_kabupaten_kota;
        $kabupatenKota->delete();

        return redirect()->route('kabupaten-kota.index')
            ->with('success', "Kabupaten / Kota \"{$nama}\" berhasil dihapus.");
    }

    /**
     * Ubah status void (aktif/non-aktif)
     */
    public function toggleVoid(KabupatenKota $kabupatenKota)
    {
        $kabupatenKota->void = !$kabupatenKota->void;
        $kabupatenKota->save();

        $statusText = $kabupatenKota->void ? 'dinonaktifkan (Void)' : 'diaktifkan kembali';
        return redirect()->back()
            ->with('success', "Status Kabupaten / Kota \"{$kabupatenKota->nama_kabupaten_kota}\" berhasil {$statusText}.");
    }
}
