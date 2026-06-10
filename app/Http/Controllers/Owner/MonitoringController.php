<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Pembelian;

class MonitoringController extends Controller
{
public function index_produk(Request $request)
    {
        $query = Produk::with(['brand', 'kategori']);

        // Filter
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                    ->orWhere('id', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }
        if ($request->stok == 'menipis') {
            $query->whereColumn('stok', '<=', 'min_stok');
        }
        if ($request->stok == 'ok') {
            $query->whereColumn('stok', '>', 'min_stok');
        }

        $produk = $query->paginate(10)->withQueryString();

        // Data Statistik Atas
        $totalProduk = Produk::count();
        $produkAktif = Produk::where('status', 1)->count();
        $stokMenipis = Produk::whereColumn('stok', '<=', 'min_stok')->where('stok', '>', 0)->count();
        $stokHabis   = Produk::where('stok', '<=', 0)->count();
        
        $kategori = Kategori::all();

        return view('owner.monitoring_produk.index', compact('produk', 'totalProduk', 'produkAktif', 'stokMenipis', 'stokHabis', 'kategori'));
    }

    public function index_pembelian(Request $request)
    {
        $query = Pembelian::with(['details.produk']);

        // Filter search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'LIKE', "%$search%")
                    ->orWhere('nama_supplier', 'LIKE', "%$search%");
            });
        }

        // Filter waktu
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        } else {
            if ($request->filled('bulan')) $query->whereMonth('tanggal', $request->bulan);
            if ($request->filled('tahun')) $query->whereYear('tanggal', $request->tahun);
        }

        $pembelian = $query->orderBy('tanggal', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('owner.monitoring_pembelian.index', compact('pembelian'));
    }
}
