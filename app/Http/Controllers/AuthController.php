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
        // =========================
        // 1. CEK ADMIN
        // =========================
        $admin = DB::table('user_admin')
            ->where('email', $request->email)
            ->first();

        if ($admin && Hash::check($request->password, $admin->password)) {

            Session::put('user_id', $admin->id);
            Session::put('role', 'admin');
            Session::put('role_id', $admin->role_id);

            // redirect sesuai role admin
            if ($admin->role_id == 1) {
                return redirect()->route('admin.index');
            } elseif ($admin->role_id == 2) {
                return redirect()->route('keuangan.index');
            }
        }

        // =========================
        // 2. CEK PELANGGAN
        // =========================
        $pelanggan = DB::table('user_pelanggan')
            ->where('email', $request->email)
            ->first();

        if ($pelanggan && Hash::check($request->password, $pelanggan->password)) {

            Session::put('user_id', $pelanggan->id);
            Session::put('role', 'pelanggan');
            Session::put('nama', $pelanggan->nama);
            Session::put('status_bengkel', $pelanggan->status_bengkel);

            return redirect()->route('pelanggan.index');
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

        // 1. Simpan ke Database
        DB::table('user_pelanggan')->insert([
            'nama' => $request->nama,
            'nama_toko' => $request->nama_toko,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'status' => '1',
            'status_bengkel' => '0',
            'limit_hutang' => '0'
        ]);

        // 2. KIRIM EMAIL NOTIFIKASI
        // Perintah ini akan mengirim email ke alamat yang baru saja didaftarkan
        try {
            Mail::to($request->email)->send(new RegistrasiSuksesMail($request->nama));
        } catch (\Exception $e) {
            // Jika email gagal terkirim (misal karena internet mati/settingan salah), 
            // registrasi tetap berhasil tapi munculin error di log, bukan di layar user.
            Log::error('Gagal mengirim email: ' . $e->getMessage());
        }

        return redirect()->route('login')->with('success', 'Registrasi berhasil. Silakan cek email Anda!');
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login');
    }
}