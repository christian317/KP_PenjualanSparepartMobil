<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function loginPage()
    {
        return view('auth.login');
    }

    public function registerPage()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {

        $user = DB::table('user')
            ->where('email', $request->email)
            ->first();

        if (!$user) {
            return back()->with('error', 'Email tidak ditemukan');
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Password salah!');
        }

        Session::put('user_id', $user->id);
        Session::put('role_id', $user->role_id);
        Session::put('nama', $user->nama);

        if ($user->role_id == 1) {
            return redirect()->route('admin.index');
        } elseif ($user->role_id == 2) {
            return redirect()->route('keuangan.index');
        } elseif ($user->role_id == 3) {
            return redirect()->route('pelanggan.index');
        }
    }

    public function register(Request $request)
    {

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|min:8',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string'
        ]);

        DB::table('user')->insert([
            'role_id' => 3, // pelanggan
            'nama' => $request->nama,
            'email' => $request->email,
            'nama_toko' => $request->nama_toko,
            'password' => Hash::make($request->password),
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'status' => 'aktif'
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil');
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login');
    }
}
