<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserPelanggan;
use App\Models\Pembayaran;
use App\Models\Piutang;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', '0'); 
        $search = $request->search;

        $tanggal = $request->tanggal;
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        // Jika filter bulan berganti, ini otomatis menghitung pendapatan di bulan tersebut saja
        $statTotalCash = Pembayaran::where('status', 1)
            ->whereHas('pesanan', function ($q) use ($tanggal, $bulan, $tahun) {
                $q->where('metode_pembayaran', 0)
                  ->when($tanggal, fn($q) => $q->whereDate('tanggal', $tanggal), 
                                   fn($q) => $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun));
            })->sum('nominal_pembayaran');

        $statTotalCicilan = Pembayaran::where('status', 1)
            ->whereHas('pesanan', function ($q) use ($tanggal, $bulan, $tahun) {
                $q->where('metode_pembayaran', 1)
                  ->when($tanggal, fn($q) => $q->whereDate('tanggal', $tanggal), 
                                   fn($q) => $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun));
            })->sum('nominal_pembayaran');


        // TAB 0: TRANSAKSI CASH
        if ($tab == '0') {
            $data = Pembayaran::with(['pesanan.UserPelanggan', 'pesanan.items.produk'])
                ->where('status', 1)
                ->whereHas('pesanan', function ($q) use ($search, $tanggal, $bulan, $tahun) {
                    $q->where('metode_pembayaran', 0)
                      ->when($tanggal, fn($query) => $query->whereDate('tanggal', $tanggal), 
                                       fn($query) => $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun))
                      ->when($search, function ($query) use ($search) {
                          $query->where(function ($subQ) use ($search) {
                              $subQ->where('nomor', 'like', "%{$search}%")
                                   ->orWhereHas('UserPelanggan', fn($u) => $u->where('nama', 'like', "%{$search}%"));
                          });
                      });
                })
                ->orderBy('id', 'desc')
                ->paginate(10)->withQueryString();

            foreach ($data as $tc) {
                $pesanan = $tc->pesanan;
                $user = $pesanan?->UserPelanggan;

                $tc->tanggal = $pesanan?->tanggal;
                $tc->nama = $user?->nama ?? '-';
                $tc->nama_toko = $user?->nama_toko ?? '-';
                $tc->pesanan_status = $pesanan?->status;
                
                $tc->items = $pesanan?->items ?? collect();
                foreach ($tc->items as $item) {
                    $item->nama = $item->produk?->nama ?? '-';
                }
                $tc->total_belanja = $tc->items->sum(fn($i) => $i->harga * $i->jumlah);
            }
        } 
    
        // TAB 1: HISTORI KONTRABON LUNAS
        else {
            $data = Piutang::with(['kontrabon.UserPelanggan', 'kontrabon.pesanan.items.produk'])
                ->where('status', 1)
                ->when($tanggal, fn($q) => $q->whereDate('tanggal_pelunasan', $tanggal), 
                                 fn($q) => $q->whereMonth('tanggal_pelunasan', $bulan)->whereYear('tanggal_pelunasan', $tahun))
                ->when($search, function ($q) use ($search) {
                    $q->whereHas('kontrabon', function ($query) use ($search) {
                        $query->where('id', 'like', "%{$search}%")
                              ->orWhereHas('UserPelanggan', fn($u) => $u->where('nama', 'like', "%{$search}%"));
                    });
                })
                ->orderBy('tanggal_pelunasan', 'desc')
                ->orderBy('id', 'desc')
                ->paginate(10)->withQueryString();

            foreach ($data as $p) {
                $kontrabon = $p->kontrabon;
                // Ambil relasi langsung dari Kontrabon
                $user = $kontrabon?->UserPelanggan ?? UserPelanggan::find($kontrabon?->user_pelanggan_id);

                $p->nama = $user?->nama ?? '-';
                $p->nama_toko = $user?->nama_toko ?? '-';
                $p->tanggal_mulai = $kontrabon?->tanggal_mulai;
                $p->sudah_dibayar = $p->total_tagihan;
                $p->persentase_bayar = 100;

                $p->pesanan_list = $kontrabon?->pesanan ?? collect();
                
                $p->histori_cicilan = Pembayaran::whereIn('nomor_pesanan', $p->pesanan_list->pluck('nomor'))
                    ->where('status', 1)
                    ->orderBy('id', 'desc')
                    ->get();

                foreach ($p->pesanan_list as $psn) {
                    foreach ($psn->items as $item) {
                        $item->nama = $item->produk?->nama ?? '-';
                    }
                    $psn->subtotal = $psn->items->sum(fn($i) => $i->harga * $i->jumlah);
                }
            }
        }

        return view('owner.transaksi.index', compact('tab', 'data', 'statTotalCash', 'statTotalCicilan'));
    }
}