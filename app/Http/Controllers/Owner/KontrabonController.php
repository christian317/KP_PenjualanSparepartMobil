<?php

namespace App\Http\Controllers\owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Models
use App\Models\Pesanan;
use App\Models\Kontrabon;
use App\Models\Piutang;
use App\Models\Pembayaran;
use App\Models\Produk;
use App\Models\PergerakanStok;
use App\Models\UserPelanggan;

class KontrabonController extends Controller
{
    public function index(Request $request)
    {
        // Informasi Statistik Kontrabon
        $statPerluApproval = Pesanan::where('status', 5)->count();
        $statKontrabonAktif = Piutang::where('status', 0)->sum('sisa_tagihan'); 
        $statOverdue = Piutang::where('status', 0)
            ->whereDate('tanggal_jatuh_tempo', '<', Carbon::now()->toDateString())
            ->count();

        // 1. Pesanan Pending (Approval) menggunakan Eager Loading
        $pesananPending = Pesanan::with(['UserPelanggan', 'items'])
            ->where('status', 5)
            ->orderBy('tanggal', 'asc')
            ->paginate(5, ['*'], 'pending_page')
            ->withQueryString();

        foreach ($pesananPending as $pending) {
            $pending->nama = $pending->UserPelanggan ? $pending->UserPelanggan->nama : '-';
            $pending->nama_toko = $pending->UserPelanggan ? $pending->UserPelanggan->nama_toko : '-';
            
            // Relasi Kontrabon lewat tabel pivot
            $kontrabonTerkait = $pending->kontrabon->first();
            $pending->nomor_kontrabon = $kontrabonTerkait ? $kontrabonTerkait->id : '-';

            $pending->total_nilai = $pending->items->sum(function($item) {
                return $item->harga * $item->jumlah;
            });
        }

        $query = Kontrabon::with('piutang')->whereDoesntHave('piutang', function($q) {
            $q->where('status', 1); 
        });

        // FILTER PENCARIAN
        if ($request->search) {
            $search = $request->search;
            $userIds = UserPelanggan::where('nama', 'like', '%' . $search . '%')->pluck('id');

            $query->where(function ($q) use ($search, $userIds) {
                $q->where('id', 'like', '%' . $search . '%') 
                  ->orWhereIn('user_pelanggan_id', $userIds);
            });
        }

        // FILTER WAKTU 
        if (!empty($request->tanggal)) {
            $query->whereDate('tanggal_mulai', $request->tanggal);
        } else {
            if (!empty($request->bulan)) $query->whereMonth('tanggal_mulai', $request->bulan);
            if (!empty($request->tahun)) $query->whereYear('tanggal_mulai', $request->tahun);
        }

        $kontrabonList = $query->orderBy('tanggal_mulai', 'desc')->paginate(10)->withQueryString();

        foreach ($kontrabonList as $kb) {
            $user = UserPelanggan::find($kb->user_pelanggan_id);
            $piutang = $kb->piutang;
            $kb->nomor_kontrabon = $kb->id; 
            $kb->nama = $user ? $user->nama : '-';
            $kb->nama_toko = $user ? $user->nama_toko : '-';

            $kb->kb_total = $kb->total_tagihan;
            $kb->kb_status = $kb->status;

            $kb->total_tagihan_tampil = $piutang ? $piutang->total_tagihan : $kb->kb_total;
            $kb->sisa_tagihan_tampil = $piutang ? $piutang->sisa_tagihan : $kb->kb_total;
            $kb->piutang_status = $piutang ? $piutang->status : null;
            $kb->tanggal_jatuh_tempo = $piutang ? $piutang->tanggal_jatuh_tempo : null;

            $kb->sudah_dibayar = $kb->total_tagihan_tampil - $kb->sisa_tagihan_tampil;
            $kb->persentase_bayar = $kb->total_tagihan_tampil > 0 ? ($kb->sudah_dibayar / $kb->total_tagihan_tampil) * 100 : 0;
            
            $kb->is_overdue = $kb->tanggal_jatuh_tempo ? (Carbon::now()->startOfDay()->greaterThan(Carbon::parse($kb->tanggal_jatuh_tempo)->startOfDay()) && $kb->piutang_status == 0) : false;
        }

        return view('owner.kontrabon.index', compact('kontrabonList', 'pesananPending', 'statPerluApproval', 'statKontrabonAktif', 'statOverdue'));
    }

    public function detail($id) // Parameter diubah menjadi id
    {
        $kontrabon = Kontrabon::with('piutang')->findOrFail($id);
        $user = UserPelanggan::find($kontrabon->user_pelanggan_id);
        
        $kontrabon->nomor_kontrabon = $kontrabon->id;
        $kontrabon->nama = $user ? $user->nama : '-';
        $kontrabon->nama_toko = $user ? $user->nama_toko : '-';
        $kontrabon->telepon = $user ? $user->telepon : '-';

        $piutang = $kontrabon->piutang;

        // Ambil SEMUA pesanan melalui TABEL PIVOT
        $pesananList = $kontrabon->pesanan()
            ->with('items.produk')
            ->where('pesanan.status', '!=', 3)
            ->orderBy('pesanan.tanggal', 'desc')
            ->get();
            
        foreach ($pesananList as $pesanan) {
            foreach ($pesanan->items as $item) {
                $item->nama_produk = $item->produk ? $item->produk->nama : '-';
            }
            $pesanan->total_harga = $pesanan->items->sum(function($item) {
                return $item->harga * $item->jumlah;
            });
        }

        $pesananIds = $pesananList->pluck('nomor');
        $histori_cicilan = Pembayaran::whereIn('nomor_pesanan', $pesananIds)
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->get();

        $total = $piutang->total_tagihan ?? $kontrabon->total_tagihan;
        $sisa = $piutang->sisa_tagihan ?? $kontrabon->total_tagihan;
        $dibayar = $total - $sisa;
        $persen = $total > 0 ? ($dibayar / $total) * 100 : 0;
        $totalHutangKeseluruhan = Piutang::whereHas('kontrabon', function ($query) use ($kontrabon) {
            $query->where('user_pelanggan_id', $kontrabon->user_pelanggan_id);
        })->sum('sisa_tagihan');

        return view('owner.kontrabon.detail', compact('kontrabon', 'piutang', 'pesananList', 'histori_cicilan', 'total', 'sisa', 'dibayar', 'persen', 'totalHutangKeseluruhan'));
    }

    public function terbitkan($id)
    {
        $kontrabon = Kontrabon::findOrFail($id);
        $adaPesananPending = $kontrabon->pesanan()->where('pesanan.status', 5)->exists();

        if ($adaPesananPending) {
            return redirect()->back()->withErrors('Gagal menerbitkan piutang! Selesaikan approval semua pesanan pada kontrabon ini terlebih dahulu.');
        }

        DB::transaction(function () use ($kontrabon) {
            $kontrabon->update([
                'status' => 1,
                'tanggal_selesai' => now() 
            ]);

            // Cek di tabel piutang dengan kontrabon_id
            $piutang_exist = Piutang::where('kontrabon_id', $kontrabon->id)->exists();

            if (!$piutang_exist) {
                Piutang::create([
                    'kontrabon_id' => $kontrabon->id,
                    'tanggal_jatuh_tempo' => now()->addMonths(3),
                    'total_tagihan' => $kontrabon->total_tagihan,
                    'sisa_tagihan' => $kontrabon->total_tagihan,
                    'status' => 0,
                ]);
            }
        });

        return redirect()->back()->with('toast_success', 'Buku Kontrabon berhasil ditutup dan Piutang telah diterbitkan ke pelanggan.');
    }

    public function approve($nomor)
    {
        // Ambil pesanan beserta relasinya
        $pesanan = Pesanan::with(['items.produk', 'kontrabon'])->where('nomor', $nomor)->firstOrFail();
        
        // total tagihan baru
        $totalBayar = $pesanan->items->sum(fn($item) => $item->harga * $item->jumlah);
        $kontrabon = $pesanan->kontrabon->first();
        if ($kontrabon) {
            $kontrabon->increment('total_tagihan', $totalBayar);
        }

        // cek status setelah approval
        $isPreOrder = 0;

        foreach ($pesanan->items as $item) {
            if ($item->produk->preorder == 1 || $item->produk->stok < 0) {
                $isPreOrder = 1;
                break;
            }
        }

        if ($isPreOrder == 1) {
            $pesanan->update([
                'status' => 6
            ]);
        } else {
            $pesanan->update([
                'status' => 0
            ]);
        }

        return redirect()->back()->with('toast_success', 'Kontrabon disetujui! Pesanan dilanjutkan ke proses selanjutnya.');
    }

    public function tolak($nomor_pesanan)
    {
        $pesanan = Pesanan::with(['items'])->where('nomor', $nomor_pesanan)->firstOrFail();
        
        $pesanan->update(['status' => 3]); 

        foreach ($pesanan->items as $item) {
            Produk::where('id', $item->produk_id)->increment('stok', $item->jumlah);
            
            PergerakanStok::create([
                'produk_id'       => $item->produk_id,
                'tipe_pergerakan' => 0,
                'jumlah'          => $item->jumlah,
                'tipe_referensi'  => 2, 
                'catatan'         => 'Ditolak Keuangan: ' . $nomor_pesanan
            ]);
        }

        return redirect()->back()->with('toast_success', 'Pesanan ditolak. Stok dikembalikan otomatis ke gudang.');
    }
}