<?php

use Illuminate\Support\Facades\Route;
// admin_gudang
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Gudang\AdminController;
use App\Http\Controllers\Gudang\BrandController;
use App\Http\Controllers\Gudang\JenisMobilController;
use App\Http\Controllers\Gudang\KategoriController;
use App\Http\Controllers\Gudang\ProdukController;
use App\Http\Controllers\Gudang\PembelianController;
use App\Http\Controllers\Gudang\KelolaPesananController;
// owner
use App\Http\Controllers\Owner\OwnerController;
use App\Http\Controllers\Owner\KontrabonController;
use App\Http\Controllers\Owner\PreorderController;
use App\Http\Controllers\Owner\UserController;
use App\Http\Controllers\Owner\TransaksiController;
use App\Http\Controllers\Owner\RiwayatPesananController;
use App\Http\Controllers\Owner\MonitoringController;
use App\Http\Controllers\Owner\RefundDanaController;
// pelanggan
use App\Http\Controllers\Pelanggan\PelangganController;
use App\Http\Controllers\Pelanggan\KeranjangController;
use App\Http\Controllers\Pelanggan\RiwayatController;
use App\Http\Controllers\Pelanggan\CheckoutController;
use App\Http\Controllers\Pelanggan\PembayaranKontrabonController;

Route::get('/welcomepage', function () {
    return view('welcome');
});

Route::middleware([])->group(function () {
    Route::get('/login', [AuthController::class,'loginPage'])->name('login');
    Route::post('/login', [AuthController::class,'login']);
    Route::get('/register', [AuthController::class,'registerPage'])->name('register');
    Route::post('/register', [AuthController::class,'register']);
    Route::get('/verifikasi-email/{token}', [AuthController::class, 'verifyEmail'])->name('verifikasi.email');
});

Route::get('/logout', [AuthController::class,'logout'])->name('logout');


// Admin
Route::middleware(['role:admin_gudang'])->group(function () {
    Route::get('/admin/index', [AdminController::class,'index'])->name('admin.index');
    //pruduk
    Route::get('/admin/produk/index', [ProdukController::class,'index'])->name('admin.produk.index');
    Route::get('/admin/produk/create', [ProdukController::class,'create'])->name('admin.produk.create');
    Route::post('/admin/produk/store', [ProdukController::class,'store'])->name('admin.produk.store');
    Route::get('/admin/produk/edit/{id}', [ProdukController::class,'edit'])->name('admin.produk.edit');
    Route::post('/admin/produk/update/{id}', [ProdukController::class,'update'])->name('admin.produk.update');
    Route::delete('/admin/produk/delete/{id}', [ProdukController::class, 'destroy'])->name('admin.produk.delete');
    Route::patch('/admin/produk/{id}/status', [ProdukController::class, 'update_status'])->name('admin.produk.update_status');
    // Brand
    Route::get('/admin/produk/brand/create', [BrandController::class,'create'])->name('admin.produk.brand.create');
    Route::post('/admin/produk/brand/store', [BrandController::class,'store'])->name('admin.produk.brand.store');
    Route::get('/admin/produk/brand/edit/{id}', [BrandController::class,'edit'])->name('admin.produk.brand.edit');
    Route::post('/admin/produk/brand/update/{id}', [BrandController::class,'update'])->name('admin.produk.brand.update');
    Route::get('/admin/produk/brand/delete/{id}', [BrandController::class,'delete'])->name('admin.produk.brand.delete');
    // Kategori
    Route::get('/admin/produk/kategori/create', [KategoriController::class,'create'])->name('admin.produk.kategori.create');
    Route::post('/admin/produk/kategori/store', [KategoriController::class,'store'])->name('admin.produk.kategori.store');
    Route::get('/admin/produk/kategori/edit/{id}', [KategoriController::class,'edit'])->name('admin.produk.kategori.edit');
    Route::post('/admin/produk/kategori/update/{id}', [KategoriController::class,'update'])->name('admin.produk.kategori.update');
    Route::get('/admin/produk/kategori/delete/{id}', [KategoriController::class,'delete'])->name('admin.produk.kategori.delete');
    // jenis mobil
    Route::get('/admin/produk/jenis_mobil/create', [JenisMobilController::class,'create'])->name('admin.produk.jenis_mobil.create');
    Route::post('/admin/produk/jenis_mobil/store', [JenisMobilController::class,'store'])->name('admin.produk.jenis_mobil.store');
    Route::get('/admin/produk/jenis_mobil/edit/{id}', [JenisMobilController::class,'edit'])->name('admin.produk.jenis_mobil.edit');
    Route::post('/admin/produk/jenis_mobil/update/{id}', [JenisMobilController::class,'update'])->name('admin.produk.jenis_mobil.update');
    Route::get('/admin/produk/jenis_mobil/delete/{id}', [JenisMobilController::class,'delete'])->name('admin.produk.jenis_mobil.delete');
    // Pembelian
    Route::get('/admin/pembelian/index', [PembelianController::class,'index'])->name('admin.pembelian.index');
    Route::get('/admin/pembelian/create', [PembelianController::class,'create'])->name('admin.pembelian.create');
    Route::post('/admin/pembelian/store', [PembelianController::class,'store'])->name('admin.pembelian.store');
    Route::get('/admin/pembelian/search/{id}', [PembelianController::class, 'searchById'])->name('admin.pembelian.search');
    // pesanan
    Route::get('/admin/pesanan/index', [KelolaPesananController::class,'index'])->name('admin.pesanan.index');
    Route::post('/admin/pesanan/{nomor}/kirim', [KelolaPesananController::class,'update'])->name('admin.pesanan.kirim');
    Route::get('/admin/pesanan/faktur/{nomor}/cetak', [KelolaPesananController::class, 'cetak_faktur'])->name('admin.pesanan.faktur');
    Route::post('/admin/pesanan/{nomor}/selesai', [KelolaPesananController::class, 'selesai'])->name('admin.pesanan.selesai');
    // riwayat pesanan
    Route::get('/admin/riwayat_pesanan/index', [KelolaPesananController::class, 'riwayat_pesanan'])->name('admin.riwayat_pesanan.index');
});
    

// owner
Route::middleware(['role:admin_owner'])->group(function () {
    Route::get('/owner/index', [OwnerController::class,'index'])->name('owner.index');
    // Kelola User
    Route::get('/owner/user/index', [UserController::class,'index'])->name('owner.user.index');
    Route::get('/owner/user/create', [UserController::class,'create'])->name('owner.user.create');
    Route::post('/owner/user/store', [UserController::class,'store'])->name('owner.user.store');
    Route::get('/owner/user/edit/{id}', [UserController::class,'edit'])->name('owner.user.edit');
    Route::post('/owner/user/update/{id}', [UserController::class,'update'])->name('owner.user.update');
    // Kelola kontrabon
    Route::get('/owner/kontrabon/index', [KontrabonController::class, 'index'])->name('owner.kontrabon.index');
    Route::post('/owner/kontrabon/{nomor_pesanan}/approve', [KontrabonController::class, 'approve'])->name('owner.kontrabon.approve');
    Route::post('/owner/kontrabon/{nomor_pesanan}/tolak', [KontrabonController::class, 'tolak'])->name('owner.kontrabon.tolak');
    Route::get('/owner/kontrabon/{id}/detail', [KontrabonController::class, 'detail'])->name('owner.kontrabon.detail');
    Route::post('/owner/kontrabon/detail/{id}/terbitkan', [KontrabonController::class, 'terbitkan'])->name('owner.kontrabon.detail.terbitkan');
    // Refund Dana
    Route::get('/owner/refund/index', [OwnerController::class, 'refund_index'])->name('owner.refund.index');
    Route::post('/owner/refund/{nomor_pesanan}/selesai', [OwnerController::class, 'refund_selesai'])->name('owner.refund.selesai');
    Route::get('/owner/refund_dana/index', [RefundDanaController::class, 'index'])->name('owner.refund_dana.index');
    Route::post('/owner/refund_dana/{nomor_pesanan}/approve-refund', [RefundDanaController::class, 'approve_refund'])->name('owner.refund.approve');
    Route::post('/owner/refund_dana/{nomor_pesanan}/tolak-refund', [RefundDanaController::class, 'tolak_refund'])->name('owner.refund.tolak');
    // Preorder
    Route::get('/owner/preorder/index', [PreorderController::class, 'index'])->name('owner.preorder.index');
    Route::post('/owner/preorder/{nomor}', [PreorderController::class, 'update'])->name('owner.preorder.teruskan');
    // Transaksi
    Route::get('/owner/transaksi/index', [TransaksiController::class, 'index'])->name('owner.transaksi.index');
    // Riwayat Pesanan
    Route::get('/owner/riwayat_pesanan/index', [RiwayatPesananController::class, 'index'])->name('owner.riwayat_pesanan.index');
    // Monitoring
    Route::get('/owner/monitoring/produk', [MonitoringController::class, 'index_produk'])->name('owner.monitoring_produk.index');
    Route::get('/owner/monitoring/pembelian', [MonitoringController::class, 'index_pembelian'])->name('owner.monitoring_pembelian.index');
});


// Pelanggan
Route::middleware(['role:pelanggan'])->group(function () {
    Route::get('/pelanggan/index', [PelangganController::class,'index'])->name('pelanggan.index');
    Route::get('pelanggan/produk/{id}', [PelangganController::class, 'detail_produk'])->name('pelanggan.detail_produk');
    // keranjang
    Route::get('pelanggan/checkout/keranjang', [KeranjangController::class, 'index'])->name('pelanggan.checkout.keranjang');
    Route::post('pelanggan/checkout/keranjang/tambah', [KeranjangController::class, 'create'])->name('pelanggan.checkout.keranjang.tambah');
    Route::post('pelanggan/checkout/keranjang/update', [KeranjangController::class, 'update'])->name('pelanggan.checkout.keranjang.update');
    Route::post('pelanggan/checkout/keranjang/hapus', [KeranjangController::class, 'delete'])->name('pelanggan.checkout.keranjang.hapus');
    // checkout
    Route::get('pelanggan/checkout/checkout', [CheckoutController::class, 'checkout'])->name('pelanggan.checkout.checkout');
    Route::post('pelanggan/checkout/proses_checkout', [CheckoutController::class, 'proses_checkout'])->name('pelanggan.checkout.proses_checkout');
    // pesanan
    Route::get('/pelanggan/riwayat/index', [RiwayatController::class, 'index'])->name('pelanggan.riwayat.index');
    Route::get('/pelanggan/riwayat/detail_pesanan/{nomor}', [RiwayatController::class, 'detail_pesanan'])->name('pelanggan.riwayat.detail_pesanan');
    Route::post('/pelanggan/riwayat/{nomor}/cancel_pesanan', [RiwayatController::class, 'cancel_pesanan'])->name('pelanggan.riwayat.cancel_pesanan');
    Route::post('/pelanggan/riwayat/{nomor}/konfirmasi_diterima', [RiwayatController::class, 'konfirmasi_diterima'])->name('pelanggan.riwayat.konfirmasi_diterima');
    Route::post('/pelanggan/riwayat/{nomor}/konfirmasi', [RiwayatController::class, 'konfirmasi_diterima'])->name('pelanggan.riwayat.konfirmasi_diterima');
    // pembayaran kontrabon
    Route::get('/pelanggan/checkout/pembayaran_kontrabon/index', [PembayaranKontrabonController::class, 'index_pembayaran_kontrabon'])->name('pelanggan.pembayaran_kontrabon.index');
    Route::get('/pelanggan/checkout/pembayaran_kontrabon/detail/{kontrabonId}', [PembayaranKontrabonController::class, 'daftar_tagihan_kontrabon'])->name('pelanggan.pembayaran_kontrabon.detail');
    Route::post('/pelanggan/checkout/pembayaran_kontrabon/{kontrabonId}/proses', [PembayaranKontrabonController::class, 'proses_bayar_kontrabon'])->name('pelanggan.pembayaran_kontrabon.proses');
   
    });
    
Route::post('/midtrans/callback', [PelangganController::class, 'midtransCallback'])->name('midtrans.callback');