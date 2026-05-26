<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserPelanggan;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\Piutang;
use App\Models\Kontrabon;


class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', '0'); 
        $search = $request->search;

        $tanggal = $request->tanggal;
        $bulan = $request->has('bulan') ? $request->bulan : date('m');
        $tahun = $request->has('tahun') ? $request->tahun : date('Y');

        $applyDateFilter = function ($query, $columnDate) use ($tanggal, $bulan, $tahun) {
            if (!empty($tanggal)) {
                $query->whereDate($columnDate, $tanggal);
            } else {
                if (!empty($bulan)) $query->whereMonth($columnDate, $bulan);
                if (!empty($tahun)) $query->whereYear($columnDate, $tahun);
            }
        };

        // STATISTIK (MENGGUNAKAN KOLOM 'nomor' BUKAN 'nomor_pesanan')
        $qPesananCash = Pesanan::where('metode_pembayaran', 0);
        $applyDateFilter($qPesananCash, 'tanggal');
        $statTotalCash = Pembayaran::whereIn('nomor_pesanan', $qPesananCash->pluck('nomor'))
            ->where('status', 1)
            ->sum('nominal_pembayaran');

        $qPesananCicilan = Pesanan::where('metode_pembayaran', 1);
        $applyDateFilter($qPesananCicilan, 'tanggal');
        $statTotalCicilan = Pembayaran::whereIn('nomor_pesanan', $qPesananCicilan->pluck('nomor'))
            ->where('status', 1)
            ->sum('nominal_pembayaran');

        // ==========================================
        // TAB 0: TRANSAKSI CASH
        // ==========================================
        if ($tab == '0') {
            $queryPesanan = Pesanan::where('metode_pembayaran', 0);
            $applyDateFilter($queryPesanan, 'tanggal');

            if ($search) {
                $userIds = UserPelanggan::where('nama', 'like', '%' . $search . '%')->pluck('id');
                $queryPesanan->where(function ($q) use ($search, $userIds) {
                    $q->where('nomor', 'like', '%' . $search . '%') 
                      ->orWhereIn('user_pelanggan_id', $userIds);
                });
            }

            // Eager load relasi pesanan
            $transaksiCash = Pembayaran::with(['pesanan.UserPelanggan', 'pesanan.items.produk'])
                ->whereIn('nomor_pesanan', $queryPesanan->pluck('nomor'))
                ->where('status', 1)
                ->orderBy('id', 'desc');

            $data = $transaksiCash->paginate(10)->withQueryString();

            foreach ($data as $tc) {
                $pesanan = $tc->pesanan;
                $user = $pesanan ? $pesanan->UserPelanggan : null;

                $tc->tanggal = $pesanan ? $pesanan->tanggal : null;
                $tc->nama = $user ? $user->nama : '-';
                $tc->nama_toko = $user ? $user->nama_toko : '-';
                $tc->pesanan_status = $pesanan ? $pesanan->status : null;

                $tc->items = $pesanan ? $pesanan->items : collect();
                foreach ($tc->items as $item) {
                    $item->nama = $item->produk ? $item->produk->nama : '-';
                }
                $tc->total_belanja = $tc->items->sum(function ($i) {
                    return $i->harga * $i->jumlah;
                });
            }
        } 
        // ==========================================
        // TAB 1: HISTORI KONTRABON LUNAS
        // ==========================================
        else {
            // Eager Load Kontrabon beserta pesanan di tabel pivotnya
            $queryPiutang = Piutang::with('kontrabon.pesanan.items.produk')
                ->where('status', 1) 
                ->orderBy('tanggal_pelunasan', 'desc')
                ->orderBy('id', 'desc');

            $applyDateFilter($queryPiutang, 'tanggal_pelunasan'); 

            if ($search) {
                $userIds = UserPelanggan::where('nama', 'like', '%' . $search . '%')->pluck('id');
                // Penyesuaian ke primary key Kontrabon yang baru ('id')
                $kbIds = Kontrabon::whereIn('user_pelanggan_id', $userIds)->pluck('id');

                $queryPiutang->where(function ($q) use ($search, $kbIds) {
                    $q->where('kontrabon_id', 'like', '%' . $search . '%')
                      ->orWhereIn('kontrabon_id', $kbIds);
                });
            }

            $data = $queryPiutang->paginate(10)->withQueryString();

            foreach ($data as $p) {
                $kontrabon = $p->kontrabon;
                // Ambil User secara elegan menggunakan find
                $user = $kontrabon ? UserPelanggan::find($kontrabon->user_pelanggan_id) : null;

                $p->nama = $user ? $user->nama : '-';
                $p->nama_toko = $user ? $user->nama_toko : '-';
                $p->tanggal_mulai = $kontrabon ? $kontrabon->tanggal_mulai : null;

                $p->sudah_dibayar = $p->total_tagihan;
                $p->persentase_bayar = 100;

                // Menggunakan relasi tabel Pivot untuk menarik Pesanan!
                $p->pesanan_list = $kontrabon ? $kontrabon->pesanan : collect();
                $pesananIds = $p->pesanan_list->pluck('nomor');

                $p->histori_cicilan = Pembayaran::whereIn('nomor_pesanan', $pesananIds)
                    ->where('status', 1)
                    ->orderBy('id', 'desc')
                    ->get();

                // Mapping rincian pesanan dari Eager Loading (Tanpa Query Database di dalam loop)
                foreach ($p->pesanan_list as $psn) {
                    // Karena eager loading, kita panggil relasi $psn->items yang sudah termuat
                    foreach ($psn->items as $item) {
                        $item->nama = $item->produk ? $item->produk->nama : '-';
                    }
                    $psn->subtotal = $psn->items->sum(function ($i) {
                        return $i->harga * $i->jumlah;
                    });
                }
            }
        }

        return view('owner.transaksi.index', compact('tab', 'data', 'statTotalCash', 'statTotalCicilan'));
    }
}
