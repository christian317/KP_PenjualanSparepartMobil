<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrasiSuksesMail;
use Illuminate\Support\Facades\Log;
use App\Models\UserPelanggan;
use Illuminate\Support\Str;

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
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
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
            if ($pelanggan->status == 0) {
                return back()->with('error', 'Akun Anda dinonaktifkan atau belum diverifikasi. H    ubungi admin.');
            }
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
            'email' => 'required|email|email:rfc,dns|unique:user_pelanggan,email',
            'password' => 'required|min:8',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string'
        ], [
            'email.email' => 'Format email tidak valid'
        ]);

        $token = Str::random(40);

        UserPelanggan::create([
            'nama' => $request->nama,
            'nama_toko' => $request->nama_toko,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'status' => '0',
            'status_mitra' => '0',
            'verify_token' => $token 
        ]);

        try {
            Mail::to($request->email)->send(new RegistrasiSuksesMail($request->nama, $token));
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email: ' . $e->getMessage());
        }

        return redirect()->route('login')->with('success', 'Registrasi berhasil. Silakan cek Inbox/Spam Gmail Anda untuk verifikasi akun!');
    }

    public function verifyEmail($token)
    {
        $user = UserPelanggan::where('verify_token', $token)->first();

        if ($user) {
            $user->update([
                'status' => '1',
                'verify_token' => null
            ]);

            return redirect()->route('login')->with('success', 'Email berhasil diverifikasi! Silakan login.');
        }

        return redirect()->route('login')->with('error', 'Link verifikasi tidak valid atau sudah kadaluarsa.');
    }

    public function logout()
    {
        Session::flush();
        return view('welcome');
    }
}