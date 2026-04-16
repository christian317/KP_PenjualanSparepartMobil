<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserPelanggan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\UserAdmin;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Produk;
use App\Models\PergerakanStok;
use App\Models\Piutang;
use App\Models\Pembayaran;

class KeuanganController extends Controller
{
    public function index()
    {
        return view('keuangan.index');
    }

    public function user_index(Request $request)
    {
        $pelanggan = UserPelanggan::select(
            'id',
            'nama',
            'nama_toko',
            'email',
            'telepon',
            'alamat',
            'status',
            'status_bengkel'
        )->get()->map(function ($item) {
            $item->tipe_user = 'pelanggan';
            $item->uid = 'p-' . $item->id;
            return $item;
        });

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

        $users = $pelanggan->concat($admin);

        $users = $users->sortByDesc('id');

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

        return view('keuangan.user.index', [
            'users' => $usersPaginated,
            'totalUser' => $totalUser,
            'userAktif' => $userAktif,
            'userNonaktif' => $userNonaktif
        ]);
    }

    public function user_create()
    {
        return view('keuangan.user.create');
    }

    public function user_store(Request $request)
    {
        // VALIDASI DASAR
        $request->validate([
            'nama'      => 'required|string|max:255',
            'email'     => 'required|email',
            'password'  => 'required|min:8',
        ]);

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
                'email' => 'unique:user_pelanggan,email',
                'limit_hutang' => 'nullable|numeric'
            ]);

            // LOGIKA LIMIT: Jika reguler paksa jadi 0, jika mitra ambil inputan admin
            $limitHutang = $request->status_bengkel == 1 ? ($request->limit_hutang ?? 0) : 0;

            UserPelanggan::create([
                'nama'      => $request->nama,
                'nama_toko' => $request->nama_toko,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'telepon'   => $request->telepon,
                'alamat'    => $request->alamat,
                'status'    => $request->status,
                'status_bengkel' => $request->status_bengkel,
                'limit_hutang'   => $request->limit_hutang
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

        return redirect()->route('keuangan.user.index')
            ->with('success', 'User ' . $request->nama . ' berhasil didaftarkan!');
    }

    public function user_edit($id, Request $request)
    {
        $tipe_user = $request->query('type');

        if ($tipe_user == 'admin') {
            $user = DB::table('user_admin')->where('id', $id)->first();
        } else {
            $user = DB::table('user_pelanggan')->where('id', $id)->first();
        }

        return view('keuangan.user.edit', compact('user', 'tipe_user'));
    }

    public function user_update(Request $request, $id)
    {
        $tipe_user = $request->tipe_user;

        if ($tipe_user == 'admin') {
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

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            DB::table('user_admin')->where('id', $id)->update($data);
        } else {
            $request->validate([
                'nama' => 'required|string|max:255',
                'nama_toko' => 'required|string|max:255',
                'email' => 'required|email|unique:user_pelanggan,email,' . $id,
                'telepon' => 'required',
                'alamat' => 'required',
                'status_bengkel' => 'required',
                'password' => 'nullable|min:8',
                'limit_hutang' => 'nullable|numeric' // Validasi tambahan
            ]);

            $limitHutang = $request->status_bengkel == 1 ? ($request->limit_hutang ?? 0) : 0;

            $data = [
                'nama' => $request->nama,
                'nama_toko' => $request->nama_toko,
                'email' => $request->email,
                'telepon' => $request->telepon,
                'alamat' => $request->alamat,
                'status_bengkel' => $request->status_bengkel,
                'status' => $request->status,
                'limit_hutang' => $request->limit_hutang // Simpan ke database
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            DB::table('user_pelanggan')->where('id', $id)->update($data);
        }

        return redirect()->route('keuangan.user.index')->with('success', 'Data user berhasil diperbarui');
    }

    public function refund_index()
    {
        $refunds = DB::table('pengajuan_refund')
            ->join('pesanan', 'pengajuan_refund.nomor_pesanan', '=', 'pesanan.nomor_pesanan')
            ->join('user_pelanggan', 'pesanan.user_pelanggan_id', '=', 'user_pelanggan.id')
            ->select('pengajuan_refund.*', 'pesanan.status_pesanan', 'pesanan.tanggal_pemesanan', 'user_pelanggan.nama', 'user_pelanggan.nama_toko')
            ->orderBy('pengajuan_refund.created_at', 'desc')
            ->get();

        return view('keuangan.refund.index', compact('refunds'));
    }

    public function refund_selesai(Request $request, $nomor_pesanan)
    {
        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'bukti_transfer.required' => 'Bukti transfer wajib diunggah!',
            'bukti_transfer.image' => 'File harus berupa gambar.',
            'bukti_transfer.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'bukti_transfer.max' => 'Ukuran gambar maksimal 2MB.'
        ]);

        $file = $request->file('bukti_transfer');
        $namaFile = 'refund_' . $nomor_pesanan . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/refund', $namaFile);

        DB::transaction(function () use ($nomor_pesanan, $namaFile) {

            DB::table('pengajuan_refund')
                ->where('nomor_pesanan', $nomor_pesanan)
                ->update([
                    'status_refund' => 1,
                    'bukti_transfer' => $namaFile,
                    'updated_at' => now()
                ]);

            Pesanan::where('nomor_pesanan', $nomor_pesanan)->update(['status_pesanan' => 3]);

            $items = DetailPesanan::where('nomor_pesanan_fk', $nomor_pesanan)->get();
            foreach ($items as $item) {
                Produk::where('kode_produk', $item->produk_id)->increment('stok_produk', $item->jumlah);

                PergerakanStok::create([
                    'produk_id' => $item->produk_id,
                    'tipe_pergerakan' => 0, // ✅ UBAH JADI ANGKA: 0 = Masuk
                    'jumlah' => $item->jumlah,
                    'tipe_referensi' => 2, // ✅ UBAH JADI ANGKA: 2 = Refund/Return
                    'catatan' => 'Refund Selesai untuk pesanan ' . $nomor_pesanan
                ]);
            }
        });

        return redirect()->back()->with('success', 'Refund selesai! Pesanan dibatalkan, bukti transfer berhasil disimpan, dan stok telah dikembalikan.');
    }

    
    public function transaksi_index(Request $request)
    {
        $tab = $request->query('tab', 'cash'); // Default tab adalah cash
        $search = $request->search;

        // Setup filter default: Bulan dan Tahun saat ini jika tidak ada input
        $tanggal = $request->tanggal;
        $bulan = $request->has('bulan') ? $request->bulan : date('m');
        $tahun = $request->has('tahun') ? $request->tahun : date('Y');

        // Fungsi bantuan (Closure) untuk menerapkan filter tanggal/bulan/tahun secara dinamis
        $applyFilter = function ($query, $columnDate) use ($tanggal, $bulan, $tahun) {
            if (!empty($tanggal)) {
                $query->whereDate($columnDate, $tanggal);
            } else {
                if (!empty($bulan)) $query->whereMonth($columnDate, $bulan);
                if (!empty($tahun)) $query->whereYear($columnDate, $tahun);
            }
        };

        // 1. Total Uang Cash Masuk (Metode Pesanan = 0, Status Bayar = 1)
        $qStatCash = Pembayaran::join('pesanan', 'pembayaran.nomor_pesanan_id', '=', 'pesanan.nomor_pesanan')
            ->where('pesanan.metode_pembayaran', 0)
            ->where('pembayaran.status', 1);
        $applyFilter($qStatCash, 'pesanan.tanggal_pemesanan'); 
        $statTotalCash = $qStatCash->sum('pembayaran.nominal_pembayaran');

        // 2. Total Setoran Cicilan Masuk (Metode Pesanan = 1, Status Bayar = 1)
        $qStatCicilan = Pembayaran::join('pesanan', 'pembayaran.nomor_pesanan_id', '=', 'pesanan.nomor_pesanan')
            ->where('pesanan.metode_pembayaran', 1)
            ->where('pembayaran.status', 1);
        // ✅ DIPERBAIKI: Menggunakan tanggal_pemesanan karena tabel pembayaran tidak pakai timestamps
        $applyFilter($qStatCicilan, 'pesanan.tanggal_pemesanan'); 
        $statTotalCicilan = $qStatCicilan->sum('pembayaran.nominal_pembayaran');


        if ($tab == '0') {
            // QUERY DATA PEMBAYARAN CASH
            $transaksi = Pembayaran::join('pesanan', 'pembayaran.nomor_pesanan_id', '=', 'pesanan.nomor_pesanan')
                ->join('user_pelanggan', 'pesanan.user_pelanggan_id', '=', 'user_pelanggan.id')
                ->select(
                    'pembayaran.*',
                    'pesanan.tanggal_pemesanan',
                    'pesanan.nomor_pesanan',
                    'user_pelanggan.nama',
                    'user_pelanggan.nama_toko'
                )
                ->where('pesanan.metode_pembayaran', 0)
                ->orderBy('pembayaran.id', 'desc');

            $applyFilter($transaksi, 'pesanan.tanggal_pemesanan');

            if ($search) {
                $transaksi->where(function ($q) use ($search) {
                    $q->where('pesanan.nomor_pesanan', 'like', '%' . $search . '%')
                        ->orWhere('user_pelanggan.nama', 'like', '%' . $search . '%');
                });
            }

            $data = $transaksi->paginate(10)->withQueryString();

            foreach ($data as $tc) {
                $tc->items = DetailPesanan::join('produk', 'detail_pesanan.produk_id', '=', 'produk.kode_produk')
                    ->where('nomor_pesanan_fk', $tc->nomor_pesanan_id)
                    ->get();
                $tc->total_belanja = $tc->items->sum(function ($i) {
                    return $i->harga * $i->jumlah;
                });
            }
        } else {
            // QUERY DATA KONTRABON
            $piutang = Piutang::join('pesanan', 'piutang.nomor_pesanan', '=', 'pesanan.nomor_pesanan')
                ->join('user_pelanggan', 'pesanan.user_pelanggan_id', '=', 'user_pelanggan.id')
                ->select(
                    'piutang.*',
                    'pesanan.tanggal_pemesanan',
                    'user_pelanggan.nama',
                    'user_pelanggan.nama_toko'
                )
                ->orderBy('piutang.status', 'asc')
                ->orderBy('piutang.id', 'desc');

            $applyFilter($piutang, 'pesanan.tanggal_pemesanan');

            if ($search) {
                $piutang->where(function ($q) use ($search) {
                    $q->where('piutang.nomor_pesanan', 'like', '%' . $search . '%')
                        ->orWhere('user_pelanggan.nama', 'like', '%' . $search . '%');
                });
            }

            $data = $piutang->paginate(10)->withQueryString();

            foreach ($data as $p) {
                $p->sudah_dibayar = $p->total_tagihan - $p->sisa_tagihan;
                $p->persentase_bayar = $p->total_tagihan > 0 ? ($p->sudah_dibayar / $p->total_tagihan) * 100 : 0;

                $p->histori_cicilan = Pembayaran::where('nomor_pesanan_id', $p->nomor_pesanan)
                    ->where('status', 1)
                    ->orderBy('id', 'desc')
                    ->get();

                $p->items = DetailPesanan::join('produk', 'detail_pesanan.produk_id', '=', 'produk.kode_produk')
                    ->where('nomor_pesanan_fk', $p->nomor_pesanan)
                    ->get();
            }
        }

        return view('keuangan.transaksi.index', compact('tab', 'data', 'statTotalCash', 'statTotalCicilan'));
    }   
}
