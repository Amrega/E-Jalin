<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Contoh penggunaan di route:
     * ->middleware('role:administrator')
     * ->middleware('role:administrator,pu_turdal,bappeda,pu_bintek,user')
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Normalisasi daftar role (mendukung pemisahan koma atau parameter terpisah)
        $allowedRoles = [];
        foreach ($roles as $roleGroup) {
            foreach (explode(',', $roleGroup) as $role) {
                $trimmed = strtolower(trim($role));
                if ($trimmed !== '') {
                    $allowedRoles[] = $trimmed;
                }
            }
        }

        // Jika 'user' atau 'kabupaten_kota' diizinkan, samakan keduanya untuk akun Pemda Kab/Kota
        if (in_array('user', $allowedRoles, true) || in_array('kabupaten_kota', $allowedRoles, true)) {
            $allowedRoles[] = 'user';
            $allowedRoles[] = 'kabupaten_kota';
            $allowedRoles[] = 'pemda';
        }

        $allowedRoles = array_values(array_unique($allowedRoles));

        if ($user->hasRole($allowedRoles)) {
            return $next($request);
        }

        // Jika pengguna tidak memiliki akses, arahkan kembali dengan pesan error
        return redirect()->route('usulan.index')->with('error', 'Akses ditolak: Role Anda tidak memiliki izin untuk mengakses halaman tersebut.');
    }
}
