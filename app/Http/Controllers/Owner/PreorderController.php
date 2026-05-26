<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;

class PreorderController extends Controller
{
    // ==============================================================================
    // FUNGSI KELOLA PRE-ORDER (PO)
    // ==============================================================================
    public function index(Request $request)
    {
        $query = Pesanan::with(['UserPelanggan', 'items.produk'])
            ->where('status', 6)
            ->orderBy('tanggal', 'asc');

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

        $pesananPO = $query->paginate(10)->withQueryString();

        // 3. Kalkulasi Status Kesiapan Stok & Total Harga
        $statTotalPO = Pesanan::where('status', 6)->count();
        $statSiapProses = 0;
        $statMenunggu = 0;

        // Hitung statistik global untuk kartu di atas
        $allPO = Pesanan::with('items.produk')->where('status', 6)->get();
        foreach ($allPO as $po) {
            $ready = true;
            foreach ($po->items as $it) {
                if ($it->produk && $it->produk->stok < $it->jumlah) {
                    $ready = false; break;
                }
            }
            if ($ready) $statSiapProses++;
            else $statMenunggu++;
        }

        // Terapkan penanda (flag) is_ready untuk setiap item di tabel pagination
        foreach ($pesananPO as $p) {
            $stokMencukupi = true;
            foreach ($p->items as $item) {
                if ($item->produk && $item->produk->stok < $item->jumlah) {
                    $stokMencukupi = false;
                    break;
                }
            }
            $p->is_ready = $stokMencukupi;
            
            $p->total_harga = $p->items->sum(function($item) {
                return $item->harga * $item->jumlah;
            });
        }

        return view('owner.preorder.index', compact('pesananPO', 'statTotalPO', 'statSiapProses', 'statMenunggu'));
    }

    public function update($nomor)
    {
        Pesanan::where('nomor', $nomor)->update([
            'status' => 0, // Ubah ke Status 0 (Diproses Gudang)
            'updated_at' => now()
        ]);
        
        return redirect()->back()->with('toast_success', 'Stok mencukupi! Pesanan PO telah diteruskan ke Gudang.');
    }
}
