<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Tampilkan daftar role pengguna
     */
    public function index(Request $request)
    {
        $query = Role::withCount('users');

        // Filter Pencarian
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('nama_role', 'like', "%{$search}%");
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
        $perPage = (int) $request->input('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 10;
        $roleList = $query->paginate($perPage)->withQueryString();

        // Data Statistik
        $totalRole  = Role::count();
        $totalAktif = Role::where('void', 0)->count();
        $totalVoid  = Role::where('void', 1)->count();
        $totalUsers = User::count();

        return view('role.index', compact(
            'roleList',
            'totalRole',
            'totalAktif',
            'totalVoid',
            'totalUsers'
        ));
    }

    /**
     * Simpan data role baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_role' => ['required', 'string', 'max:100', 'unique:role,nama_role'],
            'void'      => ['nullable', 'in:0,1'],
        ], [
            'nama_role.required' => 'Nama role wajib diisi.',
            'nama_role.unique'   => 'Nama role tersebut sudah terdaftar.',
            'nama_role.max'      => 'Nama role maksimal 100 karakter.',
        ]);

        Role::create([
            'nama_role' => strtolower(trim($validated['nama_role'])),
            'void'      => $request->has('void') ? (int) $request->void : 0,
        ]);

        return redirect()->route('role.index')->with('success', 'Data role berhasil ditambahkan.');
    }

    /**
     * Update data role
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'nama_role' => ['required', 'string', 'max:100', 'unique:role,nama_role,' . $role->id],
            'void'      => ['nullable', 'in:0,1'],
        ], [
            'nama_role.required' => 'Nama role wajib diisi.',
            'nama_role.unique'   => 'Nama role tersebut sudah digunakan role lain.',
            'nama_role.max'      => 'Nama role maksimal 100 karakter.',
        ]);

        $role->update([
            'nama_role' => strtolower(trim($validated['nama_role'])),
            'void'      => $request->has('void') ? (int) $request->void : 0,
        ]);

        return redirect()->route('role.index')->with('success', 'Data role berhasil diperbarui.');
    }

    /**
     * Hapus data role
     */
    public function destroy(Role $role)
    {
        // Proteksi jika role masih digunakan oleh pengguna aktif
        $userCount = $role->users()->count();
        if ($userCount > 0) {
            return redirect()->route('role.index')->with('error', "Role '{$role->nama_role}' tidak dapat dihapus karena masih digunakan oleh {$userCount} akun pengguna.");
        }

        $role->delete();

        return redirect()->route('role.index')->with('success', 'Data role berhasil dihapus.');
    }

    /**
     * Toggle status Void (0 = Aktif, 1 = Void / Non-aktif)
     */
    public function toggleVoid(Role $role)
    {
        $role->void = $role->void ? 0 : 1;
        $role->save();

        $statusText = $role->void ? 'dinonaktifkan (Void)' : 'diaktifkan kembali';
        return redirect()->back()->with('success', "Role {$role->nama_role} berhasil {$statusText}.");
    }
}
