<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrasiSuksesMail;
use Illuminate\Support\Facades\Log;

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
        // CEK ADMIN
        $admin = DB::table('user_admin')
            ->where('email', $request->email)
            ->first();

        if ($admin && Hash::check($request->password, $admin->password)) {

            Session::put('user_pelanggan_id', $admin->id);
            Session::put('role', 'admin');
            Session::put('role_id', $admin->role_id);

            // redirect sesuai role admin
            if ($admin->role_id == 1) {
                return redirect()->route('admin.index')->with('toast_success', 'Login berhasil!');
            } elseif ($admin->role_id == 2) {
                return redirect()->route('owner.index')->with('toast_success', 'Login berhasil!');
            }
        }

        // CEK PELANGGAN
        $pelanggan = DB::table('user_pelanggan')
            ->where('email', $request->email)
            ->first();

        if ($pelanggan && Hash::check($request->password, $pelanggan->password)) {

            Session::put('user_pelanggan_id', $pelanggan->id);
            Session::put('role', 'pelanggan');
            Session::put('nama', $pelanggan->nama);
            Session::put('status_mitra', $pelanggan->status_mitra);

            return redirect()->route('pelanggan.index')->with('toast_success', 'Login berhasil!');
        }

        return back()->with('error', 'Email atau password salah!');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nama_toko' => 'nullable|string|max:255',
            'email' => 'required|email|unique:user_pelanggan,email',
            'password' => 'required|min:8',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string'
        ]);

        DB::table('user_pelanggan')->insert([
            'nama' => $request->nama,
            'nama_toko' => $request->nama_toko,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'status' => '1',
            'status_mitra' => '0'
        ]);

        // KIRIM EMAIL NOTIFIKASI
        try {
            Mail::to($request->email)->send(new RegistrasiSuksesMail($request->nama));
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email: ' . $e->getMessage());
        }

        return redirect()->route('login')->with('success', 'Registrasi berhasil. Silakan cek email Anda!');
    }

    public function logout()
    {
        Session::flush();
        return view('welcome');
    }
}