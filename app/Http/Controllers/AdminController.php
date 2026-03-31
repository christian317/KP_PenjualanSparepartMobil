<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserPelanggan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\UserAdmin;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function user_index(Request $request)
    {
        // 1. Ambil Data Pelanggan
        $pelanggan = UserPelanggan::select(
            'id',
            'nama',
            'nama_toko',
            'email',
            'telepon',
            'alamat',
            'status'
        )->get()->map(function ($item) {
            $item->tipe_user = 'pelanggan';
            // Tambahkan prefix pada ID agar tidak bentrok dengan ID Admin saat merge
            $item->uid = 'p-' . $item->id;
            return $item;
        });

        // 2. Ambil Data Admin
        $admin = UserAdmin::select(
            'id',
            DB::raw('email as nama'),
            DB::raw('"-" as nama_toko'),
            'email',
            DB::raw('"-" as telepon'),
            DB::raw('"-" as alamat'),
            DB::raw('"aktif" as status')
        )->get()->map(function ($item) {
            $item->tipe_user = 'admin';
            $item->uid = 'a-' . $item->id;
            return $item;
        });

        // 3. Gunakan CONCAT agar tidak menimpa ID yang sama
        $users = $pelanggan->concat($admin);

        // 4. Urutkan berdasarkan ID Pelanggan terbaru (jika tipe pelanggan)
        $users = $users->sortByDesc('id');

        // 5. Filter Pencarian
        if ($request->search) {
            $search = strtolower($request->search);
            $users = $users->filter(function ($u) use ($search) {
                return str_contains(strtolower($u->nama ?? ''), $search) ||
                    str_contains(strtolower($u->email ?? ''), $search) ||
                    str_contains(strtolower($u->nama_toko ?? ''), $search);
            });
        }

        // 6. Filter Tipe & Status
        if ($request->tipe) {
            $users = $users->where('tipe_user', $request->tipe);
        }

        if ($request->status) {
            $statusFilter = strtolower($request->status);
            $users = $users->filter(fn($u) => strtolower($u->status) == $statusFilter);
        }

        // 7. Pagination Manual
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $currentItems = $users->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $usersPaginated = new LengthAwarePaginator(
            $currentItems,
            $users->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Statistik
        $totalUser = $pelanggan->count() + $admin->count();
        $userAktif = $pelanggan->where('status', '1')->count() + $admin->count();
        $userNonaktif = $pelanggan->where('status', '2')->count();

        return view('admin.user.index', [
            'users' => $usersPaginated,
            'totalUser' => $totalUser,
            'userAktif' => $userAktif,
            'userNonaktif' => $userNonaktif
        ]);
    }
    public function user_create()
    {
        return view('admin.user.create');
    }

    public function user_store(Request $request)
    {
        // VALIDASI DASAR
        $request->validate([
            'nama'      => 'required|string|max:255',
            'email'     => 'required|email',
            'password'  => 'required|min:8',
        ]);

        // DEFAULT kalau tidak ada field tipe_user (biar tidak ubah form)
        $tipe = $request->tipe_user ?? 'pelanggan';

        // =========================
        // PELANGGAN
        // =========================
        if ($tipe == 'pelanggan') {

            $request->validate([
                'nama_toko' => 'required|string|max:255',
                'telepon'   => 'required',
                'alamat'    => 'required',
                'status'    => 'required|in:1,2',
                'status_bengkel' => 'required|in:0,1',
                'email' => 'unique:user_pelanggan,email'
            ]);

            UserPelanggan::create([
                'nama'      => $request->nama,
                'nama_toko' => $request->nama_toko,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'telepon'   => $request->telepon,
                'alamat'    => $request->alamat,
                'status'    => $request->status,
                'status_bengkel' => $request->status_bengkel,
            ]);
        }

        // =========================
        // ADMIN
        // =========================
        elseif ($tipe == 'admin') {

            $request->validate([
                'role_id' => 'required|in:1,2',
                'email' => 'unique:user_admin,email'
            ]);

            DB::table('user_admin')->insert([
                'role_id' => $request->role_id,
                'email'   => $request->email,
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('admin.user.index')
            ->with('success', 'User ' . $request->nama . ' berhasil didaftarkan!');
    }

    public function user_edit($id, Request $request)
    {
        // Ambil tipe user dari query string (misal: ?type=admin atau ?type=pelanggan)
        $tipe_user = $request->query('type');

        if ($tipe_user == 'admin') {
            $user = DB::table('user_admin')->where('id', $id)->first();
        } else {
            $user = DB::table('user_pelanggan')->where('id', $id)->first();
        }

        return view('admin.user.edit', compact('user', 'tipe_user'));
    }

    // Memproses Update Data
    public function user_update(Request $request, $id)
    {
        $tipe_user = $request->tipe_user;

        if ($tipe_user == 'admin') {
            // Validasi Admin
            $request->validate([
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:user_admin,email,' . $id,
                'role_id' => 'required',
                'password' => 'nullable|min:8'
            ]);

            $data = [
                'email' => $request->email,
                'role_id' => $request->role_id,
            ];

            // Jika password diisi, maka update password
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            DB::table('user_admin')->where('id', $id)->update($data);

        } else {
            // Validasi Pelanggan
            $request->validate([
                'nama' => 'required|string|max:255',
                'nama_toko' => 'required|string|max:255',
                'email' => 'required|email|unique:user_pelanggan,email,' . $id,
                'telepon' => 'required',
                'alamat' => 'required',
                'status_bengkel' => 'required',
                'password' => 'nullable|min:8'
            ]);

            $data = [
                'nama' => $request->nama,
                'nama_toko' => $request->nama_toko,
                'email' => $request->email,
                'telepon' => $request->telepon,
                'alamat' => $request->alamat,
                'status_bengkel' => $request->status_bengkel,
                'status' => $request->status
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            DB::table('user_pelanggan')->where('id', $id)->update($data);
        }

        return redirect()->route('admin.user.index')->with('success', 'Data user berhasil diperbarui');
    }
}
