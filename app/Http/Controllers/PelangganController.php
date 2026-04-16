<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\Keranjang;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Piutang;
use App\Models\Pembayaran;
use App\Models\PergerakanStok;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\PengajuanRefund;
use Carbon\Carbon;
use App\Models\JenisMobil;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $kategori = Kategori::all();
        $brand = Brand::all();

        // 1. Ambil data Jenis Mobil untuk ditaruh di Dropdown
        $jenis_mobil = JenisMobil::orderBy('merk_mobil', 'asc')->get();

        // 2. Query Produk
        $query = Produk::with(['kategori', 'brand', 'jenisMobil'])->where('status_produk', 1);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_produk', 'LIKE', "%$search%")
                    ->orWhere('kode_produk', 'LIKE', "%$search%");
            });
        }

        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->has('brand') && $request->brand != '') {
            $query->where('brand_id', $request->brand);
        }

        // 3. Logika Filter Jenis Mobil
        if ($request->has('mobil') && $request->mobil != '') {
            $query->whereHas('jenisMobil', function ($q) use ($request) {
                $q->where('jenis_mobil.id', $request->mobil);
            });
        }

        switch ($request->sort) {
            case 'az':
                $query->orderBy('nama_produk', 'asc');
                break;
            case 'cheap':
                $query->orderBy('harga', 'asc');
                break;
            case 'expensive':
                $query->orderBy('harga', 'desc');
                break;
            default:
                $query->orderBy('kode_produk', 'desc');
                break;
        }

        $produk = $query->paginate(12)->appends($request->all());

        $limitHutang = 0;
        $terpakai = 0;
        $sisaLimit = 0;
        $persentase = 0;
        $progressColor = 'bg-success';

        if (Session::get('status_bengkel') == 1) {
            $userId = Session::get('user_id');
            $user = DB::table('user_pelanggan')->where('id', $userId)->first();
            $limitHutang = $user ? $user->limit_hutang : 0;

            // Hitung total hutang dari pesanan yang belum lunas (status 0)
            $terpakai = DB::table('piutang')
                ->join('pesanan', 'piutang.nomor_pesanan', '=', 'pesanan.nomor_pesanan')
                ->where('pesanan.user_pelanggan_id', $userId)
                ->where('piutang.status', 0)
                ->sum('piutang.sisa_tagihan');

            $sisaLimit = $limitHutang - $terpakai;
            $persentase = $limitHutang > 0 ? ($terpakai / $limitHutang) * 100 : 0;

            if ($persentase > 85) {
                $progressColor = 'bg-danger';
            } elseif ($persentase > 60) {
                $progressColor = 'bg-warning';
            }
        }

        return view('pelanggan.index', compact('produk', 'kategori', 'brand', 'jenis_mobil', 'limitHutang', 'terpakai', 'sisaLimit', 'persentase', 'progressColor'
        ));
    }

    public function detail_produk($id)
    {
        $item = Produk::with(['kategori', 'brand'])->findOrFail($id);

        $produkTerkait = Produk::where('kategori_id', $item->kategori_id)
            ->where('kode_produk', '!=', $item->kode_produk)
            ->where('status_produk', 1)
            ->limit(4)
            ->get();

        return view('pelanggan.produk_detail', compact('item', 'produkTerkait'));
    }

    public function keranjang()
    {
        $userId = Session::get('user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $cart = Keranjang::with(['produk.brand'])
            ->where('user_id', $userId)
            ->get();

        return view('pelanggan.pesanan.keranjang', compact('cart'));
    }

    public function tambah_ke_keranjang(Request $request)
    {
        $kodeProduk = $request->id;
        $userId = Session::get('user_id');
        $jumlahInput = $request->input('jumlah', 1);

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Silakan login untuk belanja');
        }

        $produk = Produk::findOrFail($kodeProduk);
        if ($produk->stok_produk < $jumlahInput) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi');
        }

        $exists = Keranjang::where('user_id', $userId)
            ->where('produk_id', $kodeProduk)
            ->first();

        if ($exists) {
            $exists->increment('jumlah', $jumlahInput);
        } else {
            Keranjang::create([
                'user_id'   => $userId,
                'produk_id' => $kodeProduk,
                'jumlah'    => $jumlahInput
            ]);
        }

        return redirect()->back()->with('toast_success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update_keranjang(Request $request)
    {
        $userId = Session::get('user_id');
        $kodeProduk = $request->id;
        $type = $request->type;

        $query = Keranjang::where('user_id', $userId)
            ->where('produk_id', $kodeProduk);

        if ($type == 'plus') {
            $query->increment('jumlah');
        } elseif ($type == 'minus') {
            $current = $query->first();
            if ($current && $current->jumlah > 1) {
                $query->decrement('jumlah');
            }
        }

        return redirect()->route('pelanggan.pesanan.keranjang');
    }

    public function hapus_keranjang(Request $request)
    {
        $userId = Session::get('user_id');

        Keranjang::where('user_id', $userId)
            ->where('produk_id', $request->id)
            ->delete();

        return redirect()->route('pelanggan.pesanan.keranjang');
    }

    public function checkout(Request $request)
    {
        $userId = Session::get('user_id');
        $user = DB::table('user_pelanggan')->where('id', $userId)->first();

        $produkTerpilih = $request->produk_terpilih;

        if (!$produkTerpilih) {
            return redirect()->route('pelanggan.pesanan.keranjang')->with('error', 'Pilih minimal satu produk untuk di-checkout.');
        }

        $keranjang = Keranjang::with(['produk.brand'])
            ->where('user_id', $userId)
            ->whereIn('produk_id', $produkTerpilih)
            ->get();

        if ($keranjang->isEmpty()) {
            return redirect()->route('pelanggan.pesanan.keranjang')->with('error', 'Keranjang kosong');
        }

        return view('pelanggan.pesanan.checkout', compact('user', 'keranjang', 'produkTerpilih'));
    }

    public function proses_checkout(Request $request)
    {
        $userId = Session::get('user_id');
        $metode = $request->pay;
        $produkTerpilih = $request->produk_terpilih;
        $catatan = $request->catatan;

        if (!$produkTerpilih || !is_array($produkTerpilih)) {
            return redirect()->route('pelanggan.pesanan.keranjang')
                ->with('error', 'Data produk hilang, silakan centang ulang.');
        }

        $keranjang = DB::table('keranjang')
            ->join('produk', 'keranjang.produk_id', '=', 'produk.kode_produk')
            ->where('user_id', $userId)
            ->whereIn('keranjang.produk_id', $produkTerpilih)
            ->select('keranjang.*', 'produk.harga', 'produk.nama_produk')
            ->get();

        if ($keranjang->isEmpty()) return redirect()->back()->with('error', 'Keranjang kosong');

        $totalBayar = $keranjang->sum(function ($i) {
            return $i->harga * $i->jumlah;
        });

        $nomorPesanan = 'INV-' . time();
        $metodeInt = ($metode == 'kontrabon' || $metode == 1) ? 1 : 0;

        // =======================================================
        // LOGIKA PENGECEKAN LIMIT HUTANG (AUTO-APPROVE / HOLD)
        // =======================================================
        $user = DB::table('user_pelanggan')->where('id', $userId)->first();
        $terpakai = DB::table('piutang')
            ->join('pesanan', 'piutang.nomor_pesanan', '=', 'pesanan.nomor_pesanan')
            ->where('pesanan.user_pelanggan_id', $userId)
            ->where('piutang.status', 0)
            ->sum('piutang.sisa_tagihan');

        $sisaLimit = $user->limit_hutang - $terpakai;
        $statusAwal = 0;

        // Jika metode kontrabon (1) DAN total belanja melebihi sisa limit
        if ($metodeInt == 1 && $totalBayar > $sisaLimit) {
            $statusAwal = 5; // 5 = Menunggu Approval Keuangan
        }
        // =======================================================

        return DB::transaction(function () use ($userId, $nomorPesanan, $totalBayar, $keranjang, $metodeInt, $catatan, $produkTerpilih, $statusAwal) {

            Pesanan::create([
                'nomor_pesanan' => $nomorPesanan,
                'user_pelanggan_id' => $userId,
                'user_admin' => 1, // Wajib null di awal
                'tanggal_pemesanan' => now(),
                'metode_pembayaran' => $metodeInt,
                'status_pembayaran' => 0,
                'status_pesanan' => $statusAwal,
                'catatan' => $catatan,
            ]);

            foreach ($keranjang as $item) {
                DetailPesanan::create([
                    'nomor_pesanan_fk' => $nomorPesanan,
                    'produk_id' => $item->produk_id,
                    'jumlah' => $item->jumlah,
                    'harga' => $item->harga,
                ]);
            }

            if ($metodeInt == 1) {
                Piutang::create([
                    'nomor_pesanan' => $nomorPesanan,
                    'tanggal_jatuh_tempo' => now()->addMonths(3),
                    'total_tagihan' => $totalBayar,
                    'sisa_tagihan' => $totalBayar,
                    'status' => 0,
                ]);

                // ==============================================================
                // TAMBAHAN BARU: LOGIKA PENGURANGAN STOK LANGSUNG DI AWAL
                // ==============================================================
                foreach ($keranjang as $item) {
                    Produk::where('kode_produk', $item->produk_id)
                        ->decrement('stok_produk', $item->jumlah);

                    PergerakanStok::create([
                        'produk_id' => $item->produk_id,
                        'tipe_pergerakan' => 1, // 1 = Keluar
                        'jumlah' => $item->jumlah,
                        'tipe_referensi' => 1, // 1 = Penjualan
                        'catatan' => 'Pesanan Kontrabon ' . $nomorPesanan
                    ]);
                }
                // ==============================================================

                Keranjang::where('user_id', $userId)->whereIn('produk_id', $produkTerpilih)->delete();

                // Berikan notifikasi yang berbeda tergantung status pesanan
                if ($statusAwal == 5) {
                    return redirect()->route('pelanggan.index')->with('toast_success', 'Pesanan melebihi limit. Menunggu persetujuan Keuangan.');
                }
                return redirect()->route('pelanggan.index')->with('toast_success', 'Pesanan kontrabon berhasil diproses!');
            }

            // Proses pembayaran Cash (Midtrans) jika metode = 0
            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $nomorPesanan,
                    'gross_amount' => (int)$totalBayar,
                ],
                'customer_details' => [
                    'first_name' => Session::get('nama'),
                    'email' => DB::table('user_pelanggan')->where('id', $userId)->value('email'),
                    'phone' => DB::table('user_pelanggan')->where('id', $userId)->value('telepon'),
                ],
                'callbacks' => [
                    'finish' => url('/pelanggan/index')
                ],
                'notification_url' => 'https://chondriosomal-transitively-marjorie.ngrok-free.dev/midtrans/callback'
            ];

            $snapToken = Snap::getSnapToken($params);

            Pembayaran::create([
                'nomor_pesanan_id' => $nomorPesanan,
                'pesanan_id_midtrans' => $nomorPesanan,
                'nominal_pembayaran' => $totalBayar,
                'status' => 0,
            ]);

            return view('pelanggan.pesanan.pembayaran', compact('snapToken', 'nomorPesanan', 'totalBayar'));
        });
    }

    public function midtransCallback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                DB::transaction(function () use ($request) {
                    
                    // 1. CARI DATA PEMBAYARAN BERDASARKAN ID MIDTRANS UNIK (Contoh: INV-123-PAY-999)
                    $pembayaran = Pembayaran::where('pesanan_id_midtrans', $request->order_id)->first();
                    
                    // Pastikan data pembayaran ada dan belum diproses sebelumnya
                    if ($pembayaran && $pembayaran->status == 0) {
                        
                        // Tandai pembayaran ini BERHASIL
                        $pembayaran->update([
                            'status' => 1,
                        ]);

                        // Ambil Nomor Pesanan Asli (Contoh: INV-123)
                        $nomorPesananAsli = $pembayaran->nomor_pesanan_id;
                        $pesanan = Pesanan::where('nomor_pesanan', $nomorPesananAsli)->first();

                        // ==============================================================
                        // 2. LOGIKA UNTUK PEMBAYARAN CICILAN KONTRABON
                        // ==============================================================
                        $piutang = Piutang::where('nomor_pesanan', $nomorPesananAsli)->first();
                        
                        if ($piutang) {
                            // Kurangi sisa hutang dengan nominal yang baru saja ditransfer
                            $piutang->decrement('sisa_tagihan', $pembayaran->nominal_pembayaran);

                            // Refresh data piutang untuk mendapatkan angka sisa tagihan terbaru
                            $piutang->refresh();

                            // Cek apakah hutangnya sudah lunas (sisa <= 0)
                            if ($piutang->sisa_tagihan <= 0) {
                                $piutang->update([
                                    'sisa_tagihan' => 0,
                                    'status' => 1, // 1 = Piutang Lunas
                                    'tanggal_pelunasan' => now()
                                ]);
                            }
                        }

                        // ==============================================================
                        // 3. UPDATE STATUS PESANAN UTAMA
                        // ==============================================================
                        // Pesanan dianggap LUNAS jika:
                        // A. Metode bayarnya Cash (0)
                        // B. Metode bayarnya Kontrabon, dan Piutangnya sudah berstatus Lunas (1)
                        if ($pesanan && ($pesanan->metode_pembayaran == 0 || ($piutang && $piutang->status == 1))) {
                            $pesanan->update([
                                'status_pembayaran' => 1 // 1 = Lunas Total
                            ]);
                        }

                        // ==============================================================
                        // 4. LOGIKA PENGURANGAN STOK (HANYA UNTUK CASH)
                        // ==============================================================
                        // PERINGATAN: Untuk kontrabon, stok JANGAN dikurangi di sini agar tidak dobel.
                        // Stok kontrabon sebaiknya dikurangi saat Admin Gudang menekan tombol "Kirim Barang".
                        if ($pesanan && $pesanan->metode_pembayaran == 0) {
                            
                            $items = DetailPesanan::where('nomor_pesanan_fk', $nomorPesananAsli)->get();
                            $produkIds = $items->pluck('produk_id');

                            foreach ($items as $item) {
                                Produk::where('kode_produk', $item->produk_id)
                                    ->decrement('stok_produk', $item->jumlah);

                                PergerakanStok::create([
                                    'produk_id' => $item->produk_id,
                                    'tipe_pergerakan' => 1, // 1 = Keluar
                                    'jumlah' => $item->jumlah,
                                    'tipe_referensi' => 1, // 1 = Penjualan
                                    'catatan' => 'Pesanan Cash ' . $nomorPesananAsli
                                ]);
                            }

                            // Hapus keranjang belanja pelanggan
                            if ($pesanan) {
                                Keranjang::where('user_id', $pesanan->user_pelanggan_id)
                                    ->whereIn('produk_id', $produkIds)
                                    ->delete();
                            }
                        }

                    }
                });
            }
        }
    }

    public function index_pesanan()
    {
        $userId = Session::get('user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Sesi habis, silakan login kembali.');
        }

        $pesanan = Pesanan::where('user_pelanggan_id', $userId)
            ->orderBy('tanggal_pemesanan', 'desc')
            ->paginate(10);


        foreach ($pesanan as $p) {
            $p->items = DetailPesanan::join('produk', 'detail_pesanan.produk_id', '=', 'produk.kode_produk')
                ->where('nomor_pesanan_fk', $p->nomor_pesanan)
                ->select('detail_pesanan.*', 'produk.nama_produk', 'produk.gambar', 'produk.unit')
                ->get();
        }

        return view('pelanggan.pesanan.index', compact('pesanan'));
    }

    public function detail_pesanan($nomor_pesanan)
    {
        $userId = Session::get('user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Sesi habis, silakan login kembali.');
        }

        $pesanan = Pesanan::where('nomor_pesanan', $nomor_pesanan)
            ->where('user_pelanggan_id', $userId)
            ->firstOrFail();

        // Ambil rincian item
        $items = DetailPesanan::join('produk', 'detail_pesanan.produk_id', '=', 'produk.kode_produk')
            ->where('nomor_pesanan_fk', $nomor_pesanan)
            ->select('detail_pesanan.*', 'produk.nama_produk', 'produk.gambar', 'produk.unit')
            ->get();

        // total belanja
        $totalHarga = $items->sum(function ($item) {
            return $item->harga * $item->jumlah;
        });

        // Cek apakah ada data refund
        $refund = null;
        if (in_array($pesanan->status_pesanan, [3, 4])) {
            $refund = DB::table('pengajuan_refund')->where('nomor_pesanan', $nomor_pesanan)->first();
        }

        return view('pelanggan.pesanan.detail_pesanan', compact('pesanan', 'items', 'totalHarga', 'refund'));
    }

    public function cancel_pesanan(Request $request, $nomor_pesanan)
    {
        $request->validate([
            'alasan_pembatalan' => 'required',
            'nama_bank' => 'required',
            'nomor_rekening' => 'required',
            'atas_nama' => 'required',
        ]);

        $userId = Session::get('user_id');

        DB::transaction(function () use ($request, $nomor_pesanan, $userId) {
            $pesanan = Pesanan::where('nomor_pesanan', $nomor_pesanan)
                ->where('user_pelanggan_id', $userId)
                ->where('status_pesanan', 0)
                ->firstOrFail();

            $pesanan->update(['status_pesanan' => 4]);

            PengajuanRefund::create([
                'nomor_pesanan' => $nomor_pesanan,
                'nama_bank' => $request->nama_bank,
                'nomor_rekening' => $request->nomor_rekening,
                'atas_nama' => $request->atas_nama,
                'alasan_pembatalan' => $request->alasan_pembatalan,
                'status_refund' => 0
            ]);
        });

        return redirect()->route('pelanggan.pesanan.index', ['tab' => 'batal'])
            ->with('toast_success', 'Pengajuan refund berhasil dikirim. Silakan tunggu proses dari Admin Keuangan.');
    }

    // =========================================================================
    // HALAMAN DAFTAR TAGIHAN KONTRABON (KHUSUS PELANGGAN)
    // =========================================================================
    public function daftar_tagihan_kontrabon(Request $request)
    {
        $userId = Session::get('user_id');

        // Pastikan yang mengakses adalah pelanggan Mitra (status_bengkel = 1)
        if (Session::get('status_bengkel') != 1) {
            return redirect()->route('pelanggan.index')->with('error', 'Halaman ini khusus untuk Pelanggan Mitra.');
        }

        // Ambil data user untuk melihat limit hutang
        $user = DB::table('user_pelanggan')->where('id', $userId)->first();

        // Tarik data piutang milik pelanggan ini
        $piutang = Piutang::join('pesanan', 'piutang.nomor_pesanan', '=', 'pesanan.nomor_pesanan')
            ->select('piutang.*', 'pesanan.tanggal_pemesanan')
            ->where('pesanan.user_pelanggan_id', $userId)
            ->orderBy('piutang.status', 'asc') // Yang belum lunas (0) taruh di atas
            ->orderBy('piutang.tanggal_jatuh_tempo', 'asc')
            ->paginate(10);

        // Hitung total hutang berjalan saat ini
        $totalHutangBerjalan = Piutang::join('pesanan', 'piutang.nomor_pesanan', '=', 'pesanan.nomor_pesanan')
            ->where('pesanan.user_pelanggan_id', $userId)
            ->where('piutang.status', 0)
            ->sum('piutang.sisa_tagihan');

        $sisaLimit = $user->limit_hutang - $totalHutangBerjalan;

        // Kalkulasi progress bayar per baris piutang
        foreach ($piutang as $p) {
            $p->sudah_dibayar = $p->total_tagihan - $p->sisa_tagihan;
            $p->persentase = $p->total_tagihan > 0 ? ($p->sudah_dibayar / $p->total_tagihan) * 100 : 0;
            
            // Cek apakah overdue
            $p->is_overdue = Carbon::now()->startOfDay()->greaterThan(\Carbon\Carbon::parse($p->tanggal_jatuh_tempo)->startOfDay()) && $p->status == 0;
        }

        return view('pelanggan.pembayaran_kontrabon.index', compact('piutang', 'user', 'totalHutangBerjalan', 'sisaLimit'));
    }

    // Fungsi untuk menampilkan halaman bayar cicilan pelanggan
    public function index_pembayaran_kontrabon($nomor_pesanan)
    {
        $userId = Session::get('user_id');
        $piutang = Piutang::where('nomor_pesanan', $nomor_pesanan)->firstOrFail();
        
        // Jika sudah lunas, kembalikan
        if ($piutang->status == 1 || $piutang->sisa_tagihan <= 0) {
            return redirect()->back()->with('toast_success', 'Tagihan ini sudah lunas!');
        }

        // Hitung nilai 1x cicilan (Total / 3). Dibulatkan ke bawah agar tidak ada desimal.
        $satuCicilan = floor($piutang->total_tagihan / 3);
        $sisaTagihan = $piutang->sisa_tagihan;

        return view('pelanggan.pembayaran_kontrabon.detail', compact('piutang', 'satuCicilan', 'sisaTagihan'));
    }

    // Fungsi saat pelanggan menekan tombol "Bayar Sekarang"
    public function proses_bayar_kontrabon(Request $request, $nomor_pesanan)
    {
        $piutang = Piutang::where('nomor_pesanan', $nomor_pesanan)->firstOrFail();
        $nominalBayar = $request->nominal_bayar;

        // Validasi jangan sampai bayar lebih dari sisa hutang
        if ($nominalBayar > $piutang->sisa_tagihan) {
            $nominalBayar = $piutang->sisa_tagihan;
        }

        // BUAT ID UNIK UNTUK MIDTRANS (Misal: INV-123-PAY-1698231)
        $midtransOrderId = $nomor_pesanan . '-PAY-' . time();

        // 1. Simpan ke tabel Pembayaran sebagai "Menunggu Pembayaran"
        // (Kolom id_transaksi sudah dihapus agar tidak error)
        Pembayaran::create([
            'nomor_pesanan_id' => $nomor_pesanan,
            'pesanan_id_midtrans' => $midtransOrderId,
            'nominal_pembayaran' => $nominalBayar,
            'status' => 0 
        ]);

        // 2. Generate Token Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $midtransOrderId, // Gunakan ID unik
                'gross_amount' => (int) $nominalBayar,
            ],
            'customer_details' => [
                'first_name' => Session::get('nama'),
                'email' => \Illuminate\Support\Facades\DB::table('user_pelanggan')->where('id', Session::get('user_id'))->value('email'),
            ],
            'callbacks' => [
                'finish' => url('/pelanggan/pesanan') // Arahkan kembali ke pesanan pelanggan
            ]
        ];

        $snapToken = Snap::getSnapToken($params);
        
        // Return view ke halaman Midtrans Snap pelanggan
        return view('pelanggan.pesanan.pembayaran', compact('snapToken', 'midtransOrderId', 'nominalBayar'));
    }
}
