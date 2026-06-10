<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

// Models
use App\Models\Pesanan;
use App\Models\PengajuanRefund;
use App\Models\Piutang;
use App\Models\Produk;
use App\Models\PergerakanStok;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $userId = Session::get('user_pelanggan_id');
        if (!$userId) return redirect()->route('login')->with('error', 'Sesi habis, silakan login kembali.');
        

        // Eager Load relasi items.produk, refund, dan kontrabon untuk menghindari N+1 Query
        $pesanan = Pesanan::with(['items.produk', 'refund', 'kontrabon'])
            ->where('user_pelanggan_id', $userId)
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        foreach ($pesanan as $p) {
            foreach ($p->items as $item) {
                $item->nama = $item->produk ? $item->produk->nama : '-';
                $item->gambar = $item->produk ? $item->produk->gambar : null;
                $item->unit = $item->produk ? $item->produk->unit : null;
            }

            // Tentukan dataStatus untuk filter Tab
            $p->dataStatus = 'diproses';
            if ($p->status == 1) $p->dataStatus = 'dikirim';
            if ($p->status == 2) $p->dataStatus = 'selesai';
            if ($p->status == 3) $p->dataStatus = 'batal';
            if ($p->status == 4) $p->dataStatus = 'refund-dana';
            if ($p->status == 5) $p->dataStatus = 'kontrabon';
            if ($p->status == 6) $p->dataStatus = 'preorder';

            // Ambil data kontrabon langsung dari relasi Pivot tanpa DB::table
            $p->kontrabon_data = null;
            if ($p->metode_pembayaran == 1 && $p->kontrabon->isNotEmpty()) {
                $p->kontrabon_data = $p->kontrabon->first();
            }
        }

        $countSemua = Pesanan::where('user_pelanggan_id', $userId)->count();
        $countDiproses = Pesanan::where('user_pelanggan_id', $userId)->where('status', 0)->count();
        $countDikirim = Pesanan::where('user_pelanggan_id', $userId)->where('status', 1)->count();
        $countSelesai = Pesanan::where('user_pelanggan_id', $userId)->where('status', 2)->count();
        $countBatal = Pesanan::where('user_pelanggan_id', $userId)->where('status', 3)->count();
        $countRefundDana = Pesanan::where('user_pelanggan_id', $userId)->where('status', 4)->count();
        $countKontrabon = Pesanan::where('user_pelanggan_id', $userId)->where('status', 5)->count();
        $countPreorder = Pesanan::where('user_pelanggan_id', $userId)->where('status', 6)->count();
        return view('pelanggan.riwayat.index', compact(
            'pesanan', 'countSemua', 'countDiproses', 'countDikirim', 
            'countSelesai', 'countBatal', 'countRefundDana', 'countKontrabon', 'countPreorder'
        ));
    }

    public function detail_pesanan($nomor_pesanan)
    {
        $userId = Session::get('user_pelanggan_id');
        if (!$userId){
             return redirect()->route('login')->with('error', 'Sesi habis, silakan login kembali.');
        };

        // Tarik pesanan beserta semua relasinya
        $pesanan = Pesanan::with(['items.produk', 'refund', 'kontrabon'])
            ->where('nomor', $nomor_pesanan)
            ->where('user_pelanggan_id', $userId)
            ->firstOrFail();

        $items = $pesanan->items;

        foreach ($items as $item) {
            $item->nama = $item->produk ? $item->produk->nama : '-';
            $item->gambar = $item->produk ? $item->produk->gambar : null;
            $item->unit = $item->produk ? $item->produk->unit : null;
        }

        $totalHarga = $items->sum(function ($item) {
            return $item->harga * $item->jumlah;
        });

        // Ekstrak relasi ke variabel untuk disajikan ke view
        $refund = $pesanan->refund;
        $kontrabon = ($pesanan->metode_pembayaran == 1) ? $pesanan->kontrabon->first() : null;

        return view('pelanggan.riwayat.detail_pesanan', compact('pesanan', 'items', 'totalHarga', 'refund', 'kontrabon'));
    }

    public function cancel_pesanan(Request $request, $nomor_pesanan)
    {
        $userId = Session::get('user_pelanggan_id');

        DB::transaction(function () use ($request, $nomor_pesanan, $userId) {
            // Eager load items dan kontrabon untuk proses stok dan pemotongan tagihan
            $pesanan = Pesanan::with(['items', 'kontrabon'])
                ->where('nomor', $nomor_pesanan)
                ->where('user_pelanggan_id', $userId)
                ->whereIn('status', [0, 5])
                ->firstOrFail();

            // pembayaran transfer
            if ($pesanan->metode_pembayaran == 0) {
                $request->validate([
                    'alasan_pembatalan' => 'required',
                    'nama_bank' => 'required',
                    'nomor_rekening' => 'required',
                    'atas_nama' => 'required',
                ]);

                $pesanan->update([
                    'status' => 4,
                    'updated_at' => Carbon::now()
                ]);

                PengajuanRefund::create([
                    'nomor_pesanan' => $nomor_pesanan,
                    'nama_bank' => $request->nama_bank,
                    'nomor_rekening' => $request->nomor_rekening,
                    'atas_nama' => $request->atas_nama,
                    'alasan_pembatalan' => $request->alasan_pembatalan,
                    'status' => 0,
                    'bukti_transfer' => '-'
                ]);
            } 

            // pembayaran kontrabon
            else {
                $request->validate(['alasan_pembatalan' => 'required']);

                $pesanan->update([
                    'status' => 3,
                    'updated_at' => Carbon::now()
                ]);

                PengajuanRefund::create([
                    'nomor_pesanan' => $nomor_pesanan,
                    'nama_bank' => '-',
                    'nomor_rekening' => 0,
                    'atas_nama' => '-',
                    'alasan_pembatalan' => $request->alasan_pembatalan,
                    'status' => 1, 
                    'bukti_transfer' => '-'
                ]);

                // kembalikan stok dan hutang
                $totalHargaPesananBatal = 0;

                foreach ($pesanan->items as $item) {
                    $totalHargaPesananBatal += ($item->harga * $item->jumlah);

                    Produk::where('id', $item->produk_id)->increment('stok', $item->jumlah);
                    
                    PergerakanStok::create([
                        'produk_id' => $item->produk_id,
                        'tipe_pergerakan' => 0, // 0 = Masuk
                        'jumlah' => $item->jumlah,
                        'tipe_referensi' => 2, // 2 = Pembatalan Pesanan
                        'catatan' => 'Pembatalan Pesanan Kontrabon ' . $nomor_pesanan
                    ]);
                }

                // Ambil kontrabon dari relasi PIVOT
                $kontrabon = $pesanan->kontrabon->first();
                
                if ($kontrabon) {
                    $kontrabon->decrement('total_tagihan', $totalHargaPesananBatal);
                    
                    // Ambil piutang berdasarkan ID kontrabon
                    $piutang = Piutang::where('kontrabon_id', $kontrabon->id)->first();
                    if ($piutang) {
                        $piutang->decrement('total_tagihan', $totalHargaPesananBatal);
                        $piutang->decrement('sisa_tagihan', $totalHargaPesananBatal);
                    }
                }
            }
        });

        return redirect()->route('pelanggan.riwayat.index', ['tab' => 'batal'])->with('toast_success', 'Pesanan berhasil dibatalkan.');
    }

    public function konfirmasi_diterima($nomor_pesanan)
    {
        $userId = Session::get('user_pelanggan_id');
        if (!$userId) return redirect()->route('login')->with('error', 'Sesi habis, silakan login kembali.');

        $pesanan = Pesanan::where('nomor', $nomor_pesanan)
            ->where('user_pelanggan_id', $userId)
            ->where('status', 1)
            ->firstOrFail();

        $pesanan->update([
            'status' => 2,
            'updated_at' => Carbon::now() 
        ]);

        return redirect()->back()->with('toast_success', 'Pesanan telah diterima dan selesai. Terima kasih telah berbelanja!');
    }
}