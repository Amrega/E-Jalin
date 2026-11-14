<?php

namespace App\Http\Controllers;

use App\Models\KabupatenKota;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Tampilkan daftar akun pengguna
     */
    public function index(Request $request)
    {
        $query = User::with(['role', 'kabupatenKota']);

        // Filter Pencarian (Nama, Username, Email, atau Role)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('role', function ($rq) use ($search) {
                      $rq->where('nama_role', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Role
        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
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
        $userList = $query->paginate($perPage)->withQueryString();

        // Data Statistik
        $totalUsers = User::count();
        $totalAktif = User::where('void', 0)->count();
        $totalVoid  = User::where('void', 1)->count();
        $totalRoles = Role::where('void', 0)->count();

        // Dropdown Role & Kabupaten/Kota
        $roleList = Role::where('void', 0)
            ->orderBy('nama_role', 'asc')
            ->get();

        $kabupatenKotaList = KabupatenKota::where('void', 0)
            ->orderBy('nama_kabupaten_kota', 'asc')
            ->get();

        return view('user.index', compact(
            'userList',
            'roleList',
            'kabupatenKotaList',
            'totalUsers',
            'totalAktif',
            'totalVoid',
            'totalRoles'
        ));
    }

    /**
     * Simpan data pengguna baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'username'          => ['required', 'string', 'max:100', 'unique:users,username'],
            'email'             => ['required', 'email', 'max:255', 'unique:users,email'],
            'role_id'           => ['required', 'exists:role,id'],
            'kabupaten_kota_id' => ['nullable', 'exists:kabupaten_kota,id'],
            'password'          => ['required', 'string', 'min:6'],
            'void'              => ['nullable', 'in:0,1'],
        ], [
            'name.required'     => 'Nama lengkap pengguna wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique'   => 'Username tersebut sudah digunakan.',
            'email.required'    => 'Alamat email wajib diisi.',
            'email.unique'      => 'Email tersebut sudah terdaftar.',
            'role_id.required'  => 'Role hak akses wajib dipilih.',
            'role_id.exists'    => 'Role yang dipilih tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal terdiri dari 6 karakter.',
        ]);

        User::create([
            'name'              => $validated['name'],
            'username'          => strtolower(trim($validated['username'])),
            'email'             => strtolower(trim($validated['email'])),
            'role_id'           => $validated['role_id'],
            'kabupaten_kota_id' => $validated['kabupaten_kota_id'] ?? null,
            'password'          => Hash::make($validated['password']),
            'void'              => $request->has('void') ? (int) $request->void : 0,
        ]);

        return redirect()->route('user.index')->with('success', 'Akun pengguna berhasil ditambahkan.');
    }

    /**
     * Update data pengguna
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'username'          => ['required', 'string', 'max:100', 'unique:users,username,' . $user->id],
            'email'             => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role_id'           => ['required', 'exists:role,id'],
            'kabupaten_kota_id' => ['nullable', 'exists:kabupaten_kota,id'],
            'password'          => ['nullable', 'string', 'min:6'],
            'void'              => ['nullable', 'in:0,1'],
        ], [
            'name.required'     => 'Nama lengkap pengguna wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique'   => 'Username tersebut sudah digunakan akun lain.',
            'email.required'    => 'Alamat email wajib diisi.',
            'email.unique'      => 'Email tersebut sudah terdaftar akun lain.',
            'role_id.required'  => 'Role hak akses wajib dipilih.',
            'password.min'      => 'Password baru minimal 6 karakter.',
        ]);

        $updateData = [
            'name'              => $validated['name'],
            'username'          => strtolower(trim($validated['username'])),
            'email'             => strtolower(trim($validated['email'])),
            'role_id'           => $validated['role_id'],
            'kabupaten_kota_id' => $validated['kabupaten_kota_id'] ?? null,
            'void'              => $request->has('void') ? (int) $request->void : 0,
        ];

        // Jika password diisi, enkripsi dan simpan yang baru
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('user.index')->with('success', 'Data akun pengguna berhasil diperbarui.');
    }

    /**
     * Hapus akun pengguna
     */
    public function destroy(User $user)
    {
        // Proteksi 1: Tidak boleh menghapus akun yang sedang dipakai login
        if (Auth::id() === $user->id) {
            return redirect()->route('user.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif digunakan.');
        }

        // Proteksi 2: Tidak boleh menghapus akun administrator utama (id = 1 atau username 'admin')
        if ($user->id === 1 || $user->username === 'admin') {
            return redirect()->route('user.index')->with('error', 'Akun Administrator Utama tidak boleh dihapus dari sistem.');
        }

        $user->delete();

        return redirect()->route('user.index')->with('success', 'Akun pengguna berhasil dihapus.');
    }

    /**
     * Toggle status Void (0 = Aktif, 1 = Void / Non-aktif)
     */
    public function toggleVoid(User $user)
    {
        // Proteksi tidak boleh menonaktifkan akun sendiri
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        // Proteksi tidak boleh menonaktifkan admin utama
        if ($user->id === 1 || $user->username === 'admin') {
            return redirect()->back()->with('error', 'Akun Administrator Utama tidak boleh dinonaktifkan.');
        }

        $user->void = $user->void ? 0 : 1;
        $user->save();

        $statusText = $user->void ? 'dinonaktifkan (Void)' : 'diaktifkan kembali';
        return redirect()->back()->with('success', "Akun {$user->name} berhasil {$statusText}.");
    }
}
