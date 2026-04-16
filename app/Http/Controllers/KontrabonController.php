<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Piutang;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KontrabonController extends Controller
{
    public function kontrabon_index(Request $request)
    {
        // 1. STATISTIK HEADER
        $statPerluApproval = Pesanan::where('status_pesanan', 5)->count();
        // Total uang yang masih nyangkut di pelanggan
        $statKontrabonAktif = Piutang::where('status', 0)->sum('sisa_tagihan'); 
        // Jumlah tagihan yang lewat jatuh tempo
        $statOverdue = Piutang::where('status', 0)->whereDate('tanggal_jatuh_tempo', '<', Carbon::now()->toDateString())->count();

        // 2. QUERY UTAMA DARI TABEL PIUTANG
        $query = Piutang::join('pesanan', 'piutang.nomor_pesanan', '=', 'pesanan.nomor_pesanan')
            ->join('user_pelanggan', 'pesanan.user_pelanggan_id', '=', 'user_pelanggan.id')
            ->select(
                'piutang.*', 
                'pesanan.tanggal_pemesanan', 
                'pesanan.status_pesanan', 
                'user_pelanggan.nama', 
                'user_pelanggan.nama_toko', 
                'user_pelanggan.limit_hutang',
                'pesanan.user_pelanggan_id'
            );

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('piutang.nomor_pesanan', 'like', '%' . $request->search . '%')
                    ->orWhere('user_pelanggan.nama', 'like', '%' . $request->search . '%');
            });
        }

        // 3. URUTAN PRIORITAS KEUANGAN
        // Prioritas 1: Perlu Approval (Status 5)
        // Prioritas 2: Tanggal Jatuh Tempo Terdekat
        $query->orderByRaw('CASE WHEN pesanan.status_pesanan = 5 THEN 0 ELSE 1 END')
            ->orderBy('piutang.status', 'asc') // Yang belum lunas (0) di atas
            ->orderBy('piutang.tanggal_jatuh_tempo', 'asc');

        // Ganti nama variabel jadi piutang agar lebih relevan dengan konteks
        $piutang = $query->paginate(10)->withQueryString();

        // 4. PERHITUNGAN TAMBAHAN PER BARIS
        foreach ($piutang as $p) {
            $p->items = DetailPesanan::join('produk', 'detail_pesanan.produk_id', '=', 'produk.kode_produk')
                ->where('nomor_pesanan_fk', $p->nomor_pesanan)
                ->get();
            
            // Hitung pemakaian limit di luar pesanan ini
            $p->terpakai_saat_ini = Piutang::join('pesanan as p2', 'piutang.nomor_pesanan', '=', 'p2.nomor_pesanan')
                ->where('p2.user_pelanggan_id', $p->user_pelanggan_id)
                ->where('piutang.status', 0)
                ->where('piutang.nomor_pesanan', '!=', $p->nomor_pesanan)
                ->sum('piutang.sisa_tagihan');

            // Logika Pembayaran / Cicilan
            $p->sudah_dibayar = $p->total_tagihan - $p->sisa_tagihan;
            $p->persentase_bayar = $p->total_tagihan > 0 ? ($p->sudah_dibayar / $p->total_tagihan) * 100 : 0;
            
            // Pengecekan Jatuh Tempo
            $p->is_overdue = Carbon::now()->startOfDay()->greaterThan(Carbon::parse($p->tanggal_jatuh_tempo)->startOfDay()) && $p->status == 0;
        }

        return view('keuangan.kontrabon.index', compact('piutang', 'statPerluApproval', 'statKontrabonAktif', 'statOverdue'));
    }

    public function approve_kontrabon($nomor_pesanan)
    {
        Pesanan::where('nomor_pesanan', $nomor_pesanan)->update(['status_pesanan' => 0]);
        return redirect()->back()->with('toast_success', 'Pesanan disetujui! Telah diteruskan ke Admin Gudang.');
    }

    public function tolak_kontrabon($nomor_pesanan)
    {
        DB::transaction(function () use ($nomor_pesanan) {
            Pesanan::where('nomor_pesanan', $nomor_pesanan)->update(['status_pesanan' => 3]);
            Piutang::where('nomor_pesanan', $nomor_pesanan)->delete();
        });
        return redirect()->back()->with('toast_success', 'Pesanan overlimit telah ditolak dan dibatalkan.');
    }
}