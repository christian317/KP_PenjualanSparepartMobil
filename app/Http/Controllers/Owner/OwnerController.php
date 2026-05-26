<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Models
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Produk;
use App\Models\PergerakanStok;
use App\Models\PengajuanRefund;

class OwnerController extends Controller
{
    public function index()
    {
        return view('owner.index');
    }

    public function refund_index()
    {
        // Refactoring: Menggunakan Eager Loading tanpa Join manual DB
        $refunds = PengajuanRefund::with('pesanan.UserPelanggan')
            ->orderBy('created_at', 'desc')
            ->get();

        // Mapping manual agar kompatibel dengan view blade kamu
        foreach ($refunds as $refund) {
            $pesanan = $refund->pesanan;
            $user = $pesanan ? $pesanan->UserPelanggan : null;

            $refund->status_pesanan = $pesanan ? $pesanan->status : null; // Ubah nama variabel agar tidak ambigu
            $refund->tanggal = $pesanan ? $pesanan->tanggal : null;
            $refund->nama = $user ? $user->nama : '-';
            $refund->nama_toko = $user ? $user->nama_toko : '-';
        }

        return view('owner.refund.index', compact('refunds'));
    }

    public function refund_selesai(Request $request, $nomor_pesanan)
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
        $file->storeAs('public/refund', $namaFile);

        DB::transaction(function () use ($nomor_pesanan, $namaFile) {
            PengajuanRefund::where('nomor_pesanan', $nomor_pesanan)->update([
                'status'         => 1,
                'bukti_transfer' => $namaFile,
                'updated_at'     => now()
            ]);

            // PERBAIKAN: Penyesuaian nama kolom Primary Key Pesanan
            Pesanan::where('nomor', $nomor_pesanan)->update(['status' => 3]);

            $items = DetailPesanan::where('nomor_pesanan', $nomor_pesanan)->get();
            foreach ($items as $item) {
                // PERBAIKAN BUG: menggunakan $item->produk_id BUKAN $item->id
                Produk::where('id', $item->produk_id)->increment('stok', $item->jumlah);

                PergerakanStok::create([
                    'produk_id'       => $item->produk_id,
                    'tipe_pergerakan' => 0, 
                    'jumlah'          => $item->jumlah,
                    'tipe_referensi'  => 2,
                    'catatan'         => 'Refund Selesai untuk pesanan ' . $nomor_pesanan
                ]);
            }
        });

        return redirect()->back()->with('success', 'Refund selesai! Pesanan dibatalkan, bukti transfer berhasil disimpan, dan stok telah dikembalikan.');
    }
}