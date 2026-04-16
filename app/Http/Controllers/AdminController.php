<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function kelola_pesanan(Request $request)
    {
        $statDiproses = Pesanan::where('status_pesanan', 0)->count();
        $statDikirim = Pesanan::where('status_pesanan', 1)->count();
        $statSelesaiHariIni = Pesanan::where('status_pesanan', 2)
                             ->whereDate('tanggal_pemesanan', Carbon::today()) 
                             ->count();

        $query = Pesanan::join('user_pelanggan', 'pesanan.user_pelanggan_id', '=', 'user_pelanggan.id')
            ->select('pesanan.*', 'user_pelanggan.nama', 'user_pelanggan.nama_toko')
            ->orderBy('pesanan.tanggal_pemesanan', 'desc');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('pesanan.nomor_pesanan', 'LIKE', "%$search%")
                  ->orWhere('user_pelanggan.nama', 'LIKE', "%$search%");
            });
        }

        if ($request->has('metode') && $request->metode != '') {
            $query->where('pesanan.metode_pembayaran', $request->metode);
        }

        $pesanan = $query->paginate(10)->appends($request->all());

        foreach ($pesanan as $p) {
            $p->items = DetailPesanan::join('produk', 'detail_pesanan.produk_id', '=', 'produk.kode_produk')
                ->where('detail_pesanan.nomor_pesanan_fk', $p->nomor_pesanan)
                ->select('detail_pesanan.*', 'produk.nama_produk')
                ->get();

            $p->total_harga = $p->items->sum(function ($item) {
                return $item->harga * $item->jumlah;
            });
        }

        return view('admin.pesanan.index', compact('pesanan', 'statDiproses', 'statDikirim', 'statSelesaiHariIni'));
    }

    public function kirim_pesanan($nomor_pesanan)
    {
        Pesanan::where('nomor_pesanan', $nomor_pesanan)->update(['status_pesanan' => 1]);
        return redirect()->back()->with('toast_success', 'Status pesanan berhasil diubah menjadi Sedang Dikirim 🚚');
    }
}
