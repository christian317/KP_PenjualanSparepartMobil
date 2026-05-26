<?php

namespace App\Http\Controllers\pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

// Models
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Brand;
use App\Models\JenisMobil;
use App\Models\Keranjang;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Piutang;
use App\Models\Pembayaran;
use App\Models\PergerakanStok;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $kategori = Kategori::all();
        $brand = Brand::all();
        $jenis_mobil = JenisMobil::orderBy('merk', 'asc')->get();

        $query = Produk::with(['kategori', 'brand', 'jenisMobil'])->where('status', 1);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%$search%")
                  ->orWhere('id', 'LIKE', "%$search%");
            });
        }

        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->has('brand') && $request->brand != '') {
            $query->where('brand_id', $request->brand);
        }

        if ($request->has('mobil') && $request->mobil != '') {
            $query->whereHas('jenisMobil', function ($q) use ($request) {
                $q->where('jenis_mobil.id', $request->mobil);
            });
        }

        switch ($request->sort) {
            case 'az':
                $query->orderBy('nama', 'asc');
                break;
            case 'cheap':
                $query->orderBy('harga', 'asc');
                break;
            case 'expensive':
                $query->orderBy('harga', 'desc');
                break;
            default:
                $query->orderBy('id', 'desc');
                break;
        }

        $produk = $query->paginate(10)->appends($request->all());
        
        $totalHutang = 0;

        if (Session::get('status_mitra') == 1) {
            $userId = Session::get('user_pelanggan_id');
            
            $totalHutang = Piutang::whereHas('kontrabon', function ($q) use ($userId) {
                $q->where('user_pelanggan_id', $userId);
            })->where('status', 0)->sum('sisa_tagihan');
        }

        return view('pelanggan.index', compact('produk', 'kategori', 'brand', 'jenis_mobil', 'totalHutang'));
    }

    public function detail_produk($id)
    {
        $item = Produk::with(['kategori', 'brand', 'jenisMobil'])->findOrFail($id);
        $produkTerkait = Produk::where('kategori_id', $item->kategori_id)
            ->where('id', '!=', $item->id)
            ->where('status', 1)
            ->limit(4)
            ->get();

        return view('pelanggan.produk_detail', compact('item', 'produkTerkait'));
    }

    public function midtransCallback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key && in_array($request->transaction_status, ['capture', 'settlement'])) {
            DB::transaction(function () use ($request) {
                $pembayaran = Pembayaran::where('pesanan_id_midtrans', $request->order_id)->first();

                if ($pembayaran && $pembayaran->status == 0) {
                    $pembayaran->update(['status' => 1]);

                    $nomorPesananAsli = $pembayaran->nomor_pesanan;
                    
                    // PERBAIKAN: Ambil pesanan sekaligus relasi kontrabonnya dari tabel pivot
                    $pesanan = Pesanan::with('kontrabon')->where('nomor', $nomorPesananAsli)->first();

                    $piutang = null;
                    if ($pesanan && $pesanan->kontrabon->isNotEmpty()) {
                        // Ambil ID kontrabon dari pivot pertama yang berelasi, lalu cari piutangnya
                        $kontrabonTerkait = $pesanan->kontrabon->first();
                        $piutang = Piutang::where('kontrabon_id', $kontrabonTerkait->id)->first();
                    }

                    // Logika Pengurangan Piutang
                    if ($piutang) {
                        $piutang->decrement('sisa_tagihan', $pembayaran->nominal_pembayaran);
                        
                        if ($piutang->fresh()->sisa_tagihan <= 0) {
                            $piutang->update([
                                'sisa_tagihan' => 0,
                                'status' => 1,
                                'tanggal_pelunasan' => now()
                            ]);
                        }
                    }

                    // Logika Update Status Pembayaran Pesanan
                    if ($pesanan && ($pesanan->metode_pembayaran == 0 || ($piutang && $piutang->fresh()->status == 1))) {
                        $pesanan->update(['status_pembayaran' => 1]);
                    }

                    // Logika Pengurangan Stok jika pembayaran Cash (0)
                    if ($pesanan && $pesanan->metode_pembayaran == 0) {
                        $items = DetailPesanan::where('nomor_pesanan', $nomorPesananAsli)->get();
                        $produkIds = $items->pluck('produk_id');

                        foreach ($items as $item) {
                            Produk::where('id', $item->produk_id)->decrement('stok', $item->jumlah);

                            PergerakanStok::create([
                                'produk_id' => $item->produk_id,
                                'tipe_pergerakan' => 1,
                                'jumlah' => $item->jumlah,
                                'tipe_referensi' => 1,
                                'catatan' => 'Pesanan Cash ' . $nomorPesananAsli
                            ]);
                        }
                        
                        Keranjang::where('user_pelanggan_id', $pesanan->user_pelanggan_id)
                                 ->whereIn('produk_id', $produkIds)
                                 ->delete();
                    }
                }
            });
        }
    }
}