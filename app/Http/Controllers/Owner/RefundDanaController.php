<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Produk;
use App\Models\PengajuanRefund;
use App\Models\PergerakanStok;
use Illuminate\Support\Facades\DB;

class RefundDanaController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesanan::with(['UserPelanggan', 'items.produk', 'refund'])
            ->where(function($q) {
                $q->where('status', 4)->orWhereHas('refund');
            });

        // 1. Filter Pencarian No Pesanan / Nama Pelanggan
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor', 'like', "%{$search}%")
                  ->orWhereHas('UserPelanggan', fn($u) => $u->where('nama', 'like', "%{$search}%"));
            });
        }

        // 2. Filter Status Tahapan Pengajuan Refund
        if ($request->has('status') && $request->status != '') {
            $query->whereHas('refund', fn($q) => $q->where('status', $request->status));
        }

        // 3. Filter Waktu (Tanggal / Bulan / Tahun)
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        } else {
            if ($request->filled('bulan')) $query->whereMonth('tanggal', $request->bulan);
            if ($request->filled('tahun')) $query->whereYear('tanggal', $request->tahun);
        }

        // Eksekusi Pagination
        $pesanan = $query->orderBy('tanggal', 'desc')->paginate(10)->withQueryString();

        foreach ($pesanan as $p) {
            $p->total_harga = $p->items->sum(fn($item) => $item->harga * $item->jumlah);
        }

        // Data Statistik Khusus Refund
        $statMenunggu = Pesanan::where('status', 4)->whereHas('refund', fn($q) => $q->where('status', 0))->count();
        $statDisetujui = PengajuanRefund::where('status', 1)->count();
        $statDitolak   = PengajuanRefund::where('status', 2)->count();
        $statTotal     = PengajuanRefund::count();

        return view('owner.refund_dana.index', compact('pesanan', 'statMenunggu', 'statDisetujui', 'statDitolak', 'statTotal'));
    }

    public function approve_refund(Request $request, $nomor_pesanan)
    {
        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'bukti_transfer.required' => 'Bukti transfer wajib diunggah!',
            'bukti_transfer.image'    => 'File harus berupa gambar.',
            'bukti_transfer.mimes'    => 'Format gambar harus jpeg, png, atau jpg.',
            'bukti_transfer.max'      => 'Ukuran gambar maksimal 2MB.'
        ]);

        $file = $request->file('bukti_transfer');
        $namaFile = 'refund_' . $nomor_pesanan . '_' . time() . '.' . $file->getClientOriginalExtension();
        
        $file->storeAs('refund', $namaFile, 'public');

        DB::transaction(function () use ($nomor_pesanan, $namaFile) {
            PengajuanRefund::where('nomor_pesanan', $nomor_pesanan)->update([
                'status'         => 1, 
                'bukti_transfer' => $namaFile,
            ]);

            // Ubah status pesanan menjadi 3 
            Pesanan::where('nomor', $nomor_pesanan)->update(['status' => 3]);

            $items = DetailPesanan::where('nomor_pesanan', $nomor_pesanan)->get();
            foreach ($items as $item) {
                Produk::where('id', $item->produk_id)->increment('stok', $item->jumlah);

                PergerakanStok::create([
                    'produk_id'       => $item->produk_id,
                    'tipe_pergerakan' => 0, 
                    'jumlah'          => $item->jumlah,
                    'tipe_referensi'  => 2, 
                    'catatan'         => 'Refund Selesai (Disetujui) untuk pesanan ' . $nomor_pesanan
                ]);
            }
        });

        return redirect()->back()->with('toast_success', 'Refund disetujui! Bukti transfer tersimpan dan stok telah dikembalikan.');
    }

    public function tolak_refund(Request $request, $nomor_pesanan)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500'
        ]);

        DB::transaction(function () use ($request, $nomor_pesanan) {
            PengajuanRefund::where('nomor_pesanan', $nomor_pesanan)->update([
                'status'     => 2,
            ]);

            $pesanan = Pesanan::where('nomor', $nomor_pesanan)->first();
            
            $catatanLama = $pesanan->catatan ? $pesanan->catatan . " | " : "";
            $catatanBaru = $catatanLama . "[Refund Ditolak]: " . $request->alasan_penolakan;

            $pesanan->update([
                'status'  => 0,
                'catatan' => $catatanBaru
            ]);
        });

        return redirect()->back()->with('toast_success', 'Refund ditolak! Pesanan kembali diproses ke gudang.');
    }
}
