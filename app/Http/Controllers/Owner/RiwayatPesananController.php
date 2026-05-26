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

class RiwayatPesananController extends Controller
{
    function index(Request $request){
        $query = Pesanan::with(['UserPelanggan', 'items.produk', 'refund']);

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor', 'like', "%$search%")
                  ->orWhereHas('UserPelanggan', function ($qUser) use ($search) {
                      $qUser->where('nama', 'like', "%$search%")
                            ->orWhere('nama_toko', 'like', "%$search%");
                  });
            });
        }

        // FILTER STATUS
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // FILTER METODE PEMBAYARAN
        if ($request->has('metode') && $request->metode != '') {
            $query->where('metode_pembayaran', $request->metode);
        }

        // FILTER WAKTU (Bulan & Tahun)
        if ($request->bulan) {
            $query->whereMonth('tanggal', $request->bulan);
        }
        if ($request->tahun) {
            $query->whereYear('tanggal', $request->tahun);
        }

        // EKSEKUSI DATA
        $pesanan = $query->orderBy('tanggal', 'desc')->paginate(10)->withQueryString();

        // MENGHITUNG TOTAL HARGA PER PESANAN
        foreach ($pesanan as $p) {
            $p->total_harga = $p->items->sum(function ($item) {
                return $item->harga * $item->jumlah;
            });
            $p->total_item = $p->items->sum('jumlah');
        }

        $statTotal = Pesanan::count();
        $statSelesai = Pesanan::where('status', 2)->count();
        $statAktif = Pesanan::whereIn('status', [0, 1, 5])->count();
        $statBatal = Pesanan::whereIn('status', [3, 4])->count();

        return view('owner.riwayat_pesanan.index', compact('pesanan', 'statTotal', 'statSelesai', 'statAktif', 'statBatal'));
    }


    // ==============================================================================
    // FUNGSI APPROVE REFUND
    // ==============================================================================
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
        
        // PERBAIKAN DI SINI: Gunakan parameter ketiga 'public' agar tersimpan di storage/app/public/refund
        $file->storeAs('refund', $namaFile, 'public');

        DB::transaction(function () use ($nomor_pesanan, $namaFile) {
            // 1. Update status pengajuan refund menjadi 1 (Disetujui)
            PengajuanRefund::where('nomor_pesanan', $nomor_pesanan)->update([
                'status'         => 1, 
                'bukti_transfer' => $namaFile,
            ]);

            Pesanan::where('nomor', $nomor_pesanan)->update(['status' => 3]);

            // 3. Kembalikan stok ke Gudang
            $items = DetailPesanan::where('nomor_pesanan', $nomor_pesanan)->get();
            foreach ($items as $item) {
                Produk::where('id', $item->produk_id)->increment('stok', $item->jumlah);

                PergerakanStok::create([
                    'produk_id'       => $item->produk_id,
                    'tipe_pergerakan' => 0, // 0 = Masuk
                    'jumlah'          => $item->jumlah,
                    'tipe_referensi'  => 2, // 2 = Refund/Batal
                    'catatan'         => 'Refund Selesai (Disetujui) untuk pesanan ' . $nomor_pesanan
                ]);
            }
        });

        return redirect()->back()->with('toast_success', 'Refund disetujui! Bukti transfer tersimpan dan stok telah dikembalikan.');
    }

    // ==============================================================================
    // FUNGSI TOLAK REFUND
    // ==============================================================================
    public function tolak_refund(Request $request, $nomor_pesanan)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500'
        ]);

        DB::transaction(function () use ($request, $nomor_pesanan) {
            // 1. Tolak pengajuan refund (Status 2 = Ditolak)
            PengajuanRefund::where('nomor_pesanan', $nomor_pesanan)->update([
                'status'     => 2,
                'updated_at' => now()
            ]);

            $pesanan = Pesanan::where('nomor', $nomor_pesanan)->first();
            
            // 2. Tambahkan alasan penolakan ke catatan agar pembeli bisa membacanya
            $catatanLama = $pesanan->catatan ? $pesanan->catatan . " | " : "";
            $catatanBaru = $catatanLama . "[Refund Ditolak]: " . $request->alasan_penolakan;

            // 3. Kembalikan status pesanan ke 0 (Diproses Gudang)
            $pesanan->update([
                'status'  => 0,
                'catatan' => $catatanBaru
            ]);
        });

        return redirect()->back()->with('toast_success', 'Refund ditolak! Pesanan kembali diproses ke gudang.');
    }
}
