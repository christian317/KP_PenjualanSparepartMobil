<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Midtrans\Config;
use Midtrans\Snap;

// Models
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Kontrabon;
use App\Models\Piutang;
use App\Models\Pembayaran;
use App\Models\UserPelanggan;

class PembayaranKontrabonController extends Controller
{
    public function index_pembayaran_kontrabon(Request $request)
    {
        $userId = Session::get('user_pelanggan_id');
        $user = UserPelanggan::find($userId);

        // Ambil data piutang pelanggan
        $piutang = Piutang::with('kontrabon')
            ->whereHas('kontrabon', function ($q) use ($userId) {
                $q->where('user_pelanggan_id', $userId)
                  ->where('status', 1);
            })
            ->orderBy('status', 'asc') // Belum Lunas prioritas atas
            ->orderBy('tanggal_jatuh_tempo', 'asc') // Jatuh tempo terdekat
            ->paginate(10);

        // 3. Modifikasi data virtual Piutang untuk disajikan ke View
        foreach ($piutang as $p) {
            // Langsung ambil dari relasi
            $p->tanggal_mulai = $p->kontrabon ? $p->kontrabon->tanggal_mulai : null;
            $p->tanggal_selesai = $p->kontrabon ? $p->kontrabon->tanggal_selesai : null;

            // Kalkulasi Angka Pembayaran
            $p->sudah_dibayar = $p->total_tagihan - $p->sisa_tagihan;
            $p->persentase = $p->total_tagihan > 0 ? ($p->sudah_dibayar / $p->total_tagihan) * 100 : 0;
            
            // Pengecekan Overdue (Telat Bayar)
            $p->is_overdue = false;
            if ($p->tanggal_jatuh_tempo) {
                $p->is_overdue = Carbon::now()->startOfDay()->greaterThan(Carbon::parse($p->tanggal_jatuh_tempo)->startOfDay()) && $p->status == 0;
            }
        }

        return view('pelanggan.pembayaran_kontrabon.index', compact('piutang', 'user'));
    }

    public function daftar_tagihan_kontrabon($kontrabonId) 
    {
        $userId = Session::get('user_pelanggan_id');
        
        // 1. Ambil Kontrabon & Piutang 
        $kontrabon = Kontrabon::where('id', $kontrabonId)
            ->where('user_pelanggan_id', $userId)
            ->firstOrFail();
            
        $piutang = Piutang::where('kontrabon_id', $kontrabonId)->firstOrFail();

        // jika sudah lunas
        if ($piutang->status == 1 || $piutang->sisa_tagihan <= 0) {
            return redirect()->back()->with('toast_success', 'Tagihan ini sudah lunas!');
        }

        // Kalkulasi Progress Pembayaran
        $sudahDibayar = $piutang->total_tagihan - $piutang->sisa_tagihan;
        $persentase = $piutang->total_tagihan > 0 ? ($sudahDibayar / $piutang->total_tagihan) * 100 : 0;

        // Pengecekan Keterlambatan Pembayaran
        $tanggalJatuhTempo = Carbon::parse($piutang->tanggal_jatuh_tempo);
        $sekarang = Carbon::now();
        $isOverdue = $sekarang->startOfDay()->greaterThan($tanggalJatuhTempo->startOfDay());

        // Hitung sudah bulan ke berapa (Perbaikan bug: pakai tanggal_mulai karena updated_at tidak ada di tabel)
        $bulanKe = (int) Carbon::parse($kontrabon->tanggal_mulai)->diffInMonths($sekarang) + 1;

        // 4. Ambil Rincian Pesanan yang Tergabung VIA RELASI PIVOT
        $pesananList = $kontrabon->pesanan()
            ->where('pesanan.status', '!=', 3) // Abaikan yang dibatalkan (tambahkan 'pesanan.' agar tidak ambigu)
            ->orderBy('pesanan.tanggal', 'desc')
            ->get();

        foreach ($pesananList as $pesanan) {
            // Tarik item sekaligus memuat data produk untuk efisiensi
            $pesanan->items = DetailPesanan::with('produk')->where('nomor_pesanan', $pesanan->nomor)->get();
            
            foreach ($pesanan->items as $item) {
                // Mapping data produk dari relasi
                $item->nama_produk = $item->produk ? $item->produk->nama : '-';
                $item->gambar_produk = $item->produk ? $item->produk->gambar : null; 
            }
                
            $pesanan->total_harga = $pesanan->items->sum(function($item) {
                return $item->harga * $item->jumlah;
            });
        }

        // 5. Tarik Histori Cicilan
        $pesananIds = $pesananList->pluck('nomor');
        $histori_cicilan = Pembayaran::whereIn('nomor_pesanan', $pesananIds)
            ->where('status', 1)
            ->orderBy('id', 'desc') 
            ->get();

        return view('pelanggan.pembayaran_kontrabon.detail', compact(
            'kontrabon', 'piutang', 'pesananList', 'sudahDibayar', 'persentase', 'isOverdue', 'bulanKe', 'histori_cicilan'
        ));
    }

    public function proses_bayar_kontrabon(Request $request, $kontrabonId)
    {
        $request->validate([
            'nominal_bayar' => 'required|numeric|min:10000'
        ], [
            'nominal_bayar.min' => 'Minimal pembayaran cicilan adalah Rp 10.000'
        ]);

        $piutang = Piutang::where('kontrabon_id', $kontrabonId)->firstOrFail();
        $kontrabon = Kontrabon::findOrFail($kontrabonId);
        
        // Mencegah bayar melebihi sisa hutang
        $nominalBayar = $request->nominal_bayar > $piutang->sisa_tagihan ? $piutang->sisa_tagihan : $request->nominal_bayar;
        
        $midtransOrderId = $kontrabonId . '-PAY-' . strtoupper(Str::random(6));

        // Ambil salah satu pesanan untuk diikat transaksinya VIA RELASI PIVOT
        $pesanan = $kontrabon->pesanan()->first();
        
        if (!$pesanan) {
            return redirect()->back()->withErrors('Gagal memproses pembayaran: Tidak ada data pesanan pada kontrabon ini.');
        }

        // Catat sebagai antrean pembayaran
        Pembayaran::create([
            'nomor_pesanan' => $pesanan->nomor,
            'pesanan_id_midtrans' => $midtransOrderId, 
            'nominal_pembayaran' => $nominalBayar,
            'status' => 0 
        ]);

        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $user = UserPelanggan::find(Session::get('user_pelanggan_id'));

        $params = [
            'transaction_details' => ['order_id' => $midtransOrderId, 'gross_amount' => (int) $nominalBayar],
            'customer_details' => [
                'first_name' => Session::get('nama'),
                'email' => $user->email,
            ],
            'callbacks' => ['finish' => url('/pelanggan/tagihan')] 
        ];

        $snapToken = Snap::getSnapToken($params);
        
        return view('pelanggan.checkout.pembayaran', [
            'snapToken' => $snapToken,
            'nomorPesanan' => $midtransOrderId,
            'totalBayar' => $nominalBayar 
        ])->with('toast_success', 'Pembayaran cicilan berhasil dibayar. Terima kasih!');
    }
}