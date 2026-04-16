<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class RoleCheck
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $userRole = Session::get('role');
        $roleId = Session::get('role_id');

        foreach ($roles as $role) {
            // Cek jika parameter adalah 'admin_gudang' (role:admin_gudang)
            if ($role == 'admin_gudang' && $userRole == 'admin' && $roleId == 1) {
                return $next($request);
            }

            // Cek jika parameter adalah 'admin_keuangan' (role:admin_keuangan)
            if ($role == 'admin_keuangan' && $userRole == 'admin' && $roleId == 2) {
                return $next($request);
            }

            // Cek untuk pelanggan biasa
            if ($role == 'pelanggan' && $userRole == 'pelanggan') {
                return $next($request);
            }
        }

        return redirect()->route('login')->with('error', 'Anda tidak memiliki otoritas untuk halaman ini.');
    }
}
