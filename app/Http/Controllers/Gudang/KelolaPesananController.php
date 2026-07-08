<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\PDF;

class KelolaPesananController extends Controller
{
    public function index(Request $request)
    {
        // Infromasi
        $statDiproses = Pesanan::where('status', 0)->count();
        $statDikirim = Pesanan::where('status', 1)->count();
        $statSelesaiHariIni = Pesanan::where('status', 2)
                             ->whereDate('tanggal', Carbon::today()) 
                             ->count();

        // Ambil data pesanan
        $query = Pesanan::with(['UserPelanggan', 'items.produk'])
            ->whereIn('status', [0, 1]) 
            ->orderBy('tanggal', 'desc');

        // Filter Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor', 'LIKE', "%{$search}%")
                  ->orWhereHas('UserPelanggan', function ($qUser) use ($search) {
                      $qUser->where('nama', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filter Waktu
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        } else {
            if ($request->filled('bulan')) $query->whereMonth('tanggal', $request->bulan);
            if ($request->filled('tahun')) $query->whereYear('tanggal', $request->tahun);
        }

        // Table Pagination
        $pesanan = $query->paginate(10)->withQueryString();

        // Informasi pesanan produk
        foreach ($pesanan as $p) {
            $p->total_item = $p->items->sum('jumlah');
            
            $p->total_harga = $p->items->sum(function($item) {
                return $item->harga * $item->jumlah;
            });

            foreach ($p->items as $item) {
                $item->nama = $item->produk ? $item->produk->nama : 'Produk Tidak Ditemukan';
            }
        }

        return view('admin.pesanan.index', compact('pesanan', 'statDiproses', 'statDikirim', 'statSelesaiHariIni'));
    }

    public function update($nomor)
    {
        Pesanan::where('nomor', $nomor)->update([
            'status' => 1,
            'updated_at' => now()
        ]);
        return redirect()->back()->with('toast_success', 'Status pesanan berhasil diubah menjadi Sedang Dikirim');
    }

    public function cetak_faktur($nomor)
    {
        // Mengambil data pesanan dan isi pesanan
        $pesanan = Pesanan::with(['UserPelanggan', 'items.produk'])
            ->where('nomor', $nomor)
            ->firstOrFail();

        $items = $pesanan->items;

        // Menghitung total harga
        $total_harga = $items->sum(function ($item) {
            return $item->harga * $item->jumlah;
        });

        // Menambahkan nama produk ke setiap item
        foreach ($items as $item) {
            $item->nama_produk = $item->produk ? $item->produk->nama : '-';
        }

        // Download PDF
        $pdf = PDF::loadView('admin.pesanan.faktur', compact('pesanan', 'items', 'total_harga'));
        $pdf->setPaper('a5', 'landscape');
        
        return $pdf->download('Faktur_' . $pesanan->nomor . '.pdf');
    }

    public function cetakResi($nomor)
    {
        $pesanan = Pesanan::with(['UserPelanggan', 'items.produk'])->where('nomor', $nomor)->firstOrFail();
        $pdf = PDF::loadView('admin.pesanan.resi', compact('pesanan'));
        $pdf->setPaper('a6', 'portrait');
        
        return $pdf->download('Resi_Pengiriman_' . $pesanan->nomor . '.pdf');
    }

    public function selesai($nomor)
    {
        $pesanan = Pesanan::where('nomor', $nomor)->firstOrFail();

        $pesanan->update([
            'status' => 2,
            'updated_at' => now()
        ]);
        
        return redirect()->back()->with('toast_success', 'Pesanan ' . $nomor . ' berhasil dikonfirmasi selesai.');
    }


    public function riwayat_pesanan(Request $request)
    {
        // Informasi
        $statTotal = Pesanan::whereIn('status', [2, 3])->count();
        $statSelesai = Pesanan::where('status', 2)->count();
        $statBatal = Pesanan::whereIn('status', [3])->count();

        // Ambil data pelanggan dan pesanan
        $query = Pesanan::with(['UserPelanggan', 'items.produk', 'refund'])
            ->whereIn('status', [2, 3, 4, 6])
            ->orderBy('updated_at', 'desc');

        // Filter status
        if ($request->filled('status')) {
            if ($request->status == '2') {
                $query->where('status', 2);
            } elseif ($request->status == '3') {
                $query->where('status', 3);
            }
        }

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor', 'LIKE', "%{$search}%")
                  ->orWhereHas('UserPelanggan', function ($qUser) use ($search) {
                      $qUser->where('nama', 'LIKE', "%{$search}%")
                            ->orWhere('nama_toko', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filter waktu
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        } else {
            if ($request->filled('bulan')) $query->whereMonth('tanggal', $request->bulan);
            if ($request->filled('tahun')) $query->whereYear('tanggal', $request->tahun);
        }

        $pesanan = $query->paginate(10)->withQueryString();

        // Harga & Total Item untuk Modal
        foreach ($pesanan as $p) {
            $p->total_item = $p->items->sum('jumlah');
            $p->total_harga = $p->items->sum(function($item) {
                return $item->harga * $item->jumlah;
            });
        }

        return view('admin.riwayat_pesanan.index', compact('pesanan', 'statTotal', 'statSelesai', 'statBatal'));
    }
}
