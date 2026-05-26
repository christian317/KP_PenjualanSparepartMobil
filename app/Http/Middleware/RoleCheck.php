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
        if (!Session::has('user_pelanggan_id')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $userRole = Session::get('role');
        $roleId = Session::get('role_id');

        foreach ($roles as $role) {
            if ($role == 'admin_gudang' && $userRole == 'admin' && $roleId == 1) {
                return $next($request);
            }

            if ($role == 'admin_owner' && $userRole == 'admin' && $roleId == 2) {
                return $next($request);
            }

            if ($role == 'pelanggan' && $userRole == 'pelanggan') {
                return $next($request);
            }
        }

        return redirect()->route('login')->with('error', 'Anda tidak memiliki otoritas untuk halaman ini.');
    }
}
