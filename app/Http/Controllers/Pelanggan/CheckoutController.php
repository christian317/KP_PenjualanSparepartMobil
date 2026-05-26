<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\DetailKontrabon;
use App\Models\Produk;
use App\Models\Keranjang;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Pembayaran;
use App\Models\Kontrabon;
use App\Models\PergerakanStok;
use App\Models\UserPelanggan;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $userId = Session::get('user_pelanggan_id');
        $user = UserPelanggan::find($userId);
        $produkTerpilih = $request->produk_terpilih;

        if (!$produkTerpilih) {
            return redirect()->route('pelanggan.pesanan.keranjang')->with('error', 'Pilih minimal satu produk.');
        }

        $keranjang = Keranjang::with(['produk.brand'])
            ->where('user_pelanggan_id', $userId)
            ->whereIn('produk_id', $produkTerpilih)
            ->get();

        if ($keranjang->isEmpty()) {
            return redirect()->route('pelanggan.pesanan.keranjang')->with('error', 'Keranjang kosong');
        }

        return view('pelanggan.checkout.checkout', compact('user', 'keranjang', 'produkTerpilih'));
    }

    public function proses_checkout(Request $request)
    {
        $userId = Session::get('user_pelanggan_id');
        $user = UserPelanggan::find($userId);

        $metode = $request->pay;
        $produkTerpilih = $request->produk_terpilih;
        $catatan = $request->catatan;

        if (!$produkTerpilih || !is_array($produkTerpilih)) {
            return redirect()->route('pelanggan.pesanan.keranjang')->with('error', 'Data produk hilang.');
        }

        $keranjang = Keranjang::with('produk')
            ->where('user_pelanggan_id', $userId)
            ->whereIn('produk_id', $produkTerpilih)
            ->get();

        if ($keranjang->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang kosong');
        }


        // Cek produk preorder
        $isPreOrder = 0;
        foreach ($keranjang as $item) {
            if ($item->produk->preorder == 1 || $item->produk->stok < $item->jumlah) {
                $isPreOrder = 1;
                break;
            }
        }

        $totalBayar = $keranjang->sum(function ($i) {
            return $i->produk->harga * $i->jumlah;
        });

        $nomorPesanan = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        $metodeInt = ($metode == 'kontrabon' || $metode == 1) ? 1 : 0;
        $kontrabonId = null;

        // metode cash
        $statusAwal = 0; 
        // metode kontrabon
        if ($metodeInt == 1) {
            $statusAwal = 5;
        } else {
            // preorder
            if ($isPreOrder) {
                $statusAwal = 6;
            }
        }

        // Metode Kontrabon
        if ($metodeInt == 1) {
            $kontrabonAktif = Kontrabon::where('user_pelanggan_id', $userId)
                ->where('status', 0)
                ->where('tanggal_selesai', '>=', now())
                ->first();

            if ($kontrabonAktif) {
                $kontrabonId = $kontrabonAktif->id;
                $kontrabonAktif->total_tagihan = $kontrabonAktif->total_tagihan + $totalBayar;
                $kontrabonAktif->save();
            } else {
                $kontrabonId = 'KB-' . date('Ymd') . '-' . strtoupper(Str::random(6));
                Kontrabon::create([
                    'id'                => $kontrabonId,
                    'user_pelanggan_id' => $userId,
                    'tanggal_mulai'     => now(),
                    'tanggal_selesai'   => now()->addDays(7),
                    'total_tagihan'     => $totalBayar,
                    'status'            => 0,
                ]);
            }
        }

        // Create pesanan
        Pesanan::create([
            'nomor'             => $nomorPesanan,
            'user_pelanggan_id' => $userId,
            'user_admin_id'     => 1,
            'tanggal'           => now(),
            'metode_pembayaran' => $metodeInt,
            'status_pembayaran' => 0,
            'status'            => $statusAwal,
            'catatan'           => $catatan,
        ]);

        // Create detail kontrabon
        if ($metodeInt == 1 && $kontrabonId != null) {
            DetailKontrabon::create([
                'kontrabon_id'  => $kontrabonId,
                'nomor_pesanan' => $nomorPesanan
            ]);
        }

        // Create detail pesanan
        foreach ($keranjang as $item) {
            DetailPesanan::create([
                'nomor_pesanan' => $nomorPesanan,
                'produk_id'     => $item->produk_id,
                'jumlah'        => $item->jumlah,
                'harga'         => $item->produk->harga,
            ]);
        }

        // Proses kontrabon akhir
        if ($metodeInt == 1) {
            foreach ($keranjang as $item) {
                // Pengurangan stok (Jika PO, stok akan menjadi minus sebagai penanda jumlah barang yang harus di-restok Owner)
                if ($item->produk->preorder == 0) {
                    $item->produk->decrement('stok', $item->jumlah);
                }

                PergerakanStok::create([
                    'produk_id'       => $item->produk_id,
                    'tipe_pergerakan' => 1, 
                    'jumlah'          => $item->jumlah,
                    'tipe_referensi'  => 1,
                    'catatan'         => 'Reservasi Kontrabon (Menunggu Approval) ' . $nomorPesanan
                ]);
            }

            // Hapus item keranjang
            Keranjang::where('user_pelanggan_id', $userId)
                ->whereIn('produk_id', $produkTerpilih)
                ->delete();

            return redirect()->route('pelanggan.index')->with('toast_success', 'Pesanan Kontrabon berhasil dibuat. Menunggu persetujuan Admin Keuangan.');
        }

        
        // Proses pemabayaran (cash)
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id'     => $nomorPesanan,
                'gross_amount' => (int) $totalBayar
            ],
            'customer_details' => [
                'first_name' => Session::get('nama'),
                'email'      => $user->email,
                'phone'      => $user->telepon,
            ],
            'callbacks' => [
                'finish' => url('/pelanggan/index')
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        // Create pembayaran
        Pembayaran::create([
            'nomor_pesanan'       => $nomorPesanan,
            'pesanan_id_midtrans' => $nomorPesanan,
            'nominal_pembayaran'  => $totalBayar,
            'status'              => 0,
        ]);

        return view('pelanggan.checkout.pembayaran', compact('snapToken', 'nomorPesanan', 'totalBayar'));
    }
}
