<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserAdmin;
use App\Models\UserPelanggan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\LengthAwarePaginator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Sepenuhnya menggunakan Eloquent
        $pelanggan = UserPelanggan::select(
            'id', 'nama', 'nama_toko', 'email', 'telepon', 'alamat', 'status', 'status_mitra'
        )->get()->map(function ($item) {
            $item->tipe_user = 'pelanggan';
            $item->uid = 'p-' . $item->id;
            return $item;
        });

        $admin = UserAdmin::select(
            'id', 'email', 'role_id'
        )->get()->map(function ($item) {
            $item->nama = $item->email; // Alias virtual
            $item->nama_toko = '-';
            $item->telepon = '-';
            $item->alamat = '-';
            $item->status = 'aktif';
            $item->tipe_user = 'admin';
            $item->uid = 'a-' . $item->id;
            return $item;
        });

        $users = $pelanggan->concat($admin)->sortByDesc('id');

        if ($request->search) {
            $search = strtolower($request->search);
            $users = $users->filter(function ($u) use ($search) {
                return str_contains(strtolower($u->nama ?? ''), $search) ||
                    str_contains(strtolower($u->email ?? ''), $search) ||
                    str_contains(strtolower($u->nama_toko ?? ''), $search);
            });
        }

        if ($request->tipe) {
            $users = $users->where('tipe_user', $request->tipe);
        }

        if ($request->status) {
            $statusFilter = strtolower($request->status);
            $users = $users->filter(fn($u) => strtolower($u->status) == $statusFilter);
        }

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

        $totalUser = $pelanggan->count() + $admin->count();
        $userAktif = $pelanggan->where('status', '1')->count() + $admin->count();
        $userNonaktif = $pelanggan->where('status', '2')->count();

        return view('owner.user.index', [
            'users' => $usersPaginated,
            'totalUser' => $totalUser,
            'userAktif' => $userAktif,
            'userNonaktif' => $userNonaktif
        ]);
    }

    public function create()
    {
        return view('owner.user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'email'     => 'required|email',
            'password'  => 'required|min:8',
        ]);

        $tipe = $request->tipe_user ?? 'pelanggan';

        if ($tipe == 'pelanggan') {
            $request->validate([
                'nama_toko'    => 'required|string|max:255',
                'telepon'      => 'required',
                'alamat'       => 'required',
                'status'       => 'required|in:1,2',
                'status_mitra' => 'required|in:0,1',
                'email'        => 'unique:user_pelanggan,email',
            ]);

            UserPelanggan::create([
                'nama'         => $request->nama,
                'nama_toko'    => $request->nama_toko,
                'email'        => $request->email,
                'password'     => Hash::make($request->password),
                'telepon'      => $request->telepon,
                'alamat'       => $request->alamat,
                'status'       => $request->status,
                'status_mitra' => $request->status_mitra,
            ]);
        } elseif ($tipe == 'admin') {
            $request->validate([
                'role_id' => 'required|in:1,2',
                'email'   => 'unique:user_admin,email'
            ]);

            UserAdmin::create([
                'role_id'  => $request->role_id,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('owner.user.index')
            ->with('success', 'User ' . $request->nama . ' berhasil didaftarkan!');
    }

    public function edit($id, Request $request)
    {
        $tipe_user = $request->query('type');

        if ($tipe_user == 'admin') {
            $user = UserAdmin::findOrFail($id);
        } else {
            $user = UserPelanggan::findOrFail($id);
        }

        return view('owner.user.edit', compact('user', 'tipe_user'));
    }

    public function update(Request $request, $id)
    {
        $tipe_user = $request->tipe_user;

        if ($tipe_user == 'admin') {
            $request->validate([
                'email'    => 'required|email|unique:user_admin,email,' . $id,
                'role_id'  => 'required',
                'password' => 'nullable|min:8'
            ]);

            $admin = UserAdmin::findOrFail($id);
            $admin->email = $request->email;
            $admin->role_id = $request->role_id;
            if ($request->filled('password')) {
                $admin->password = Hash::make($request->password);
            }
            $admin->save();
            
        } else {
            $request->validate([
                'nama'         => 'required|string|max:255',
                'nama_toko'    => 'required|string|max:255',
                'email'        => 'required|email|unique:user_pelanggan,email,' . $id,
                'telepon'      => 'required',
                'alamat'       => 'required',
                'status_mitra' => 'required',
                'password'     => 'nullable|min:8',
            ]);

            $pelanggan = UserPelanggan::findOrFail($id);
            $pelanggan->nama = $request->nama;
            $pelanggan->nama_toko = $request->nama_toko;
            $pelanggan->email = $request->email;
            $pelanggan->telepon = $request->telepon;
            $pelanggan->alamat = $request->alamat;
            $pelanggan->status_mitra = $request->status_mitra;
            $pelanggan->status = $request->status;

            if ($request->filled('password')) {
                $pelanggan->password = Hash::make($request->password);
            }
            $pelanggan->save();
        }

        return redirect()->route('owner.user.index')->with('success', 'Data user berhasil diperbarui');
    }
}
