<?php

namespace App\Http\Controllers;

use App\Models\JenisPengusul;
use App\Models\Pengusul;
use App\Models\User;
use Illuminate\Http\Request;

class PengusulController extends Controller
{
    /**
     * Tampilkan daftar master pengusul
     */
    public function index(Request $request)
    {
        $query = Pengusul::with(['jenisPengusul', 'user.role']);

        // Filter Pencarian (Nama Pengusul, Jenis Pengusul, atau Akun Pengguna)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('nama_pengusul', 'like', "%{$search}%")
                    ->orWhereHas('jenisPengusul', function ($jq) use ($search) {
                        $jq->where('nama_jenis_pengusul', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('username', 'like', "%{$search}%");
                    });
            });
        }

        // Filter Jenis Pengusul
        if ($request->filled('jenis_pengusul_id')) {
            $query->where('jenis_pengusul_id', $request->jenis_pengusul_id);
        }

        // Urutan data
        $query->orderBy('id', 'asc');

        // Pagination
        $perPage = (int) $request->input('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 10;
        $pengusulList = $query->paginate($perPage)->withQueryString();

        // Data master untuk dropdown filter & modal form
        $jenisPengusulList = JenisPengusul::orderBy('nama_jenis_pengusul', 'asc')->get();

        $userList = User::with('role')
            ->where('void', 0)
            ->orderBy('name', 'asc')
            ->get();
        if ($userList->isEmpty()) {
            $userList = User::with('role')->orderBy('name', 'asc')->get();
        }

        return view('pengusul.index', compact(
            'pengusulList',
            'jenisPengusulList',
            'userList'
        ));
    }

    /**
     * Simpan data pengusul baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pengusul'     => ['required', 'string', 'max:255', 'unique:pengusul,nama_pengusul'],
            'jenis_pengusul_id' => ['required', 'exists:jenis_pengusul,id'],
            'user_id'           => ['required', 'exists:users,id'],
        ], [
            'nama_pengusul.required'     => 'Nama pengusul wajib diisi.',
            'nama_pengusul.unique'       => 'Nama pengusul tersebut sudah ada.',
            'nama_pengusul.max'          => 'Nama pengusul maksimal 255 karakter.',
            'jenis_pengusul_id.required' => 'Jenis pengusul wajib dipilih.',
            'jenis_pengusul_id.exists'   => 'Jenis pengusul yang dipilih tidak valid.',
            'user_id.required'           => 'User wajib dipilih.',
            'user_id.exists'             => 'User yang dipilih tidak valid.',
        ]);

        Pengusul::create([
            'nama_pengusul'     => trim($validated['nama_pengusul']),
            'jenis_pengusul_id' => $validated['jenis_pengusul_id'],
            'user_id'           => $validated['user_id'],
        ]);

        return redirect()->route('pengusul.index')
            ->with('success', 'Data pengusul berhasil ditambahkan.');
    }

    /**
     * Update data pengusul
     */
    public function update(Request $request, Pengusul $pengusul)
    {
        $validated = $request->validate([
            'nama_pengusul'     => ['required', 'string', 'max:255', 'unique:pengusul,nama_pengusul,' . $pengusul->id],
            'jenis_pengusul_id' => ['required', 'exists:jenis_pengusul,id'],
            'user_id'           => ['required', 'exists:users,id'],
        ], [
            'nama_pengusul.required'     => 'Nama pengusul wajib diisi.',
            'nama_pengusul.unique'       => 'Nama pengusul tersebut sudah digunakan.',
            'nama_pengusul.max'          => 'Nama pengusul maksimal 255 karakter.',
            'jenis_pengusul_id.required' => 'Jenis pengusul wajib dipilih.',
            'jenis_pengusul_id.exists'   => 'Jenis pengusul yang dipilih tidak valid.',
            'user_id.required'           => 'User wajib dipilih.',
            'user_id.exists'             => 'User yang dipilih tidak valid.',
        ]);

        $pengusul->update([
            'nama_pengusul'     => trim($validated['nama_pengusul']),
            'jenis_pengusul_id' => $validated['jenis_pengusul_id'],
            'user_id'           => $validated['user_id'],
        ]);

        return redirect()->route('pengusul.index')
            ->with('success', 'Data pengusul berhasil diperbarui.');
    }

    /**
     * Hapus data pengusul
     */
    public function destroy(Pengusul $pengusul)
    {
        $nama = $pengusul->nama_pengusul;
        $pengusul->delete();

        return redirect()->route('pengusul.index')
            ->with('success', "Data pengusul \"{$nama}\" berhasil dihapus.");
    }
}
