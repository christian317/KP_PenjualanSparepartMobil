<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Produk;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $statDiproses = Pesanan::where('status', 0)->count();
        $statDikirim = Pesanan::where('status', 1)->count();
        
        $statSelesai = Pesanan::where('status', 2)
            ->whereMonth('tanggal', date('m'))
            ->whereYear('tanggal', date('Y'))
            ->count();

        $statStokKritis = Produk::whereColumn('stok', '<=', 'min_stok')->count();

        $pesananAktif = Pesanan::with(['UserPelanggan', 'items'])
            ->whereIn('status', [0])
            ->orderBy('tanggal', 'asc')
            ->paginate(5, ['*'], 'pesanan_page')->withQueryString();

        $produkKritis = Produk::with('kategori')
            ->whereColumn('stok', '<=', 'min_stok')
            ->orderBy('stok', 'asc')
            ->paginate(5, ['*'], 'stok_page')->withQueryString();

        return view('admin.index', compact(
            'statDiproses', 'statDikirim', 'statSelesai', 'statStokKritis',
            'pesananAktif', 'produkKritis'
        ));
    }
}