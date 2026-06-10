<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;


class RiwayatPesananController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesanan::with(['UserPelanggan', 'items.produk'])
            ->whereIn('status', [0, 1, 2, 3]);

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor', 'like', "%$search%")
                  ->orWhereHas('UserPelanggan', function ($qUser) use ($search) {
                      $qUser->where('nama', 'like', "%$search%")
                            ->orWhere('nama_toko', 'like', "%$search%");
                  });
            });
        }

        // FILTER STATUS
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // FILTER METODE PEMBAYARAN
        if ($request->has('metode') && $request->metode != '') {
            $query->where('metode_pembayaran', $request->metode);
        }

        // FILTER TANGGAL / BULAN / TAHUN
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        } else {
            if ($request->filled('bulan')) $query->whereMonth('tanggal', $request->bulan);
            if ($request->filled('tahun')) $query->whereYear('tanggal', $request->tahun);
        }

        $pesanan = $query->orderBy('tanggal', 'desc')->paginate(10)->withQueryString();

        // MENGHITUNG TOTAL HARGA PER PESANAN
        foreach ($pesanan as $p) {
            $p->total_harga = $p->items->sum(function ($item) {
                return $item->harga * $item->jumlah;
            });
            $p->total_item = $p->items->sum('jumlah');
        }

        $statTotal   = Pesanan::whereIn('status', [0, 1, 2, 3])->count();
        $statSelesai = Pesanan::where('status', 2)->count();
        $statAktif   = Pesanan::whereIn('status', [0, 1])->count();
        $statBatal   = Pesanan::where('status', 3)->count();

        return view('owner.riwayat_pesanan.index', compact('pesanan', 'statTotal', 'statSelesai', 'statAktif', 'statBatal'));
    }
}
