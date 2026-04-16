<?php

use Illuminate\Support\Facades\Route;
// admin_gudang
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\JenisMobilController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
// admin_keuangan
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\KontrabonController;
// pelanggan
use App\Http\Controllers\PelangganController;

Route::middleware([])->group(function () {
    Route::get('/', [AuthController::class,'loginPage'])->name('login');
    Route::post('/login', [AuthController::class,'login']);
    Route::get('/register', [AuthController::class,'registerPage'])->name('register');
    Route::post('/register', [AuthController::class,'register']);
});

Route::get('/logout', [AuthController::class,'logout'])->name('logout');


// Admin
Route::middleware(['role:admin_gudang'])->group(function () {
    Route::get('/admin/index', [AdminController::class,'index'])->name('admin.index');
    //pruduk
    Route::get('/admin/produk/index', [ProdukController::class,'index'])->name('admin.produk.index');
    Route::get('/admin/produk/create', [ProdukController::class,'create'])->name('admin.produk.create');
    Route::post('/admin/produk/store', [ProdukController::class,'store'])->name('admin.produk.store');
    Route::get('/admin/produk/edit/{kode_produk}', [ProdukController::class,'edit'])->name('admin.produk.edit');
    Route::post('/admin/produk/update/{kode_produk}', [ProdukController::class,'update'])->name('admin.produk.update');
    Route::get('/admin/produk/delete/{kode_produk}', [ProdukController::class,'delete'])->name('admin.produk.delete');
    Route::patch('/admin/produk/{kode_produk}/status', [ProdukController::class, 'update_status'])->name('admin.produk.update_status');
    // Brand
    Route::get('/admin/produk/brand/create', [BrandController::class,'create_brand'])->name('admin.produk.brand.create');
    Route::post('/admin/produk/brand/store', [BrandController::class,'store_brand'])->name('admin.produk.brand.store');
    Route::get('/admin/produk/brand/edit/{id}', [BrandController::class,'edit_brand'])->name('admin.produk.brand.edit');
    Route::post('/admin/produk/brand/update/{id}', [BrandController::class,'update_brand'])->name('admin.produk.brand.update');
    Route::get('/admin/produk/brand/delete/{id}', [BrandController::class,'delete_brand'])->name('admin.produk.brand.delete');
    // Kategori
    Route::get('/admin/produk/kategori/create', [KategoriController::class,'create_kategori'])->name('admin.produk.kategori.create');
    Route::post('/admin/produk/kategori/store', [KategoriController::class,'store_kategori'])->name('admin.produk.kategori.store');
    Route::get('/admin/produk/kategori/edit/{id}', [KategoriController::class,'edit_kategori'])->name('admin.produk.kategori.edit');
    Route::post('/admin/produk/kategori/update/{id}', [KategoriController::class,'update_kategori'])->name('admin.produk.kategori.update');
    Route::get('/admin/produk/kategori/delete/{id}', [KategoriController::class,'delete_kategori'])->name('admin.produk.kategori.delete');
    // jenis mobil
    Route::get('/admin/produk/jenis_mobil/create', [JenisMobilController::class,'create_jenis_mobil'])->name('admin.produk.jenis_mobil.create');
    Route::post('/admin/produk/jenis_mobil/store', [JenisMobilController::class,'store_jenis_mobil'])->name('admin.produk.jenis_mobil.store');
    Route::get('/admin/produk/jenis_mobil/edit/{id}', [JenisMobilController::class,'edit_jenis_mobil'])->name('admin.produk.jenis_mobil.edit');
    Route::post('/admin/produk/jenis_mobil/update/{id}', [JenisMobilController::class,'update_jenis_mobil'])->name('admin.produk.jenis_mobil.update');
    Route::get('/admin/produk/jenis_mobil/delete/{id}', [JenisMobilController::class,'delete_jenis_mobil'])->name('admin.produk.jenis_mobil.delete');
    // Pembelian
    Route::get('/admin/pembelian/index', [PembelianController::class,'index'])->name('admin.pembelian.index');
    Route::get('/admin/pembelian/create', [PembelianController::class,'create'])->name('admin.pembelian.create');
    Route::post('/admin/pembelian/store', [PembelianController::class,'store'])->name('admin.pembelian.store');
    Route::get('/admin/pembelian/search/{kode}', [PembelianController::class, 'searchByKode'])->name('admin.pembelian.search');
    // pesanan
    Route::get('/admin/pesanan/index', [AdminController::class,'kelola_pesanan'])->name('admin.pesanan.index');
    Route::post('/admin/pesanan/{nomor_pesanan}/kirim', [AdminController::class,'kirim_pesanan'])->name('admin.pesanan.kirim');
});
    

// Keuangan
Route::middleware(['role:admin_keuangan'])->group(function () {
    Route::get('/keuangan/index', [KeuanganController::class,'index'])->name('keuangan.index');
    // Kelola User
    Route::get('/keuangan/user/index', [KeuanganController::class,'user_index'])->name('keuangan.user.index');
    Route::get('/keuangan/user/create', [KeuanganController::class,'user_create'])->name('keuangan.user.create');
    Route::post('/keuangan/user/store', [KeuanganController::class,'user_store'])->name('keuangan.user.store');
    Route::get('/keuangan/user/edit/{id}', [KeuanganController::class,'user_edit'])->name('keuangan.user.edit');
    Route::post('/keuangan/user/update/{id}', [KeuanganController::class,'user_update'])->name('keuangan.user.update');
    // refund
    Route::get('/keuangan/refund/index', [KeuanganController::class, 'refund_index'])->name('keuangan.refund.index');
    Route::post('/keuangan/refund/{nomor_pesanan}/selesai', [KeuanganController::class, 'refund_selesai'])->name('keuangan.refund.selesai');
    // Kelola kontrabon
    Route::get('/keuangan/kontrabon/index', [KontrabonController::class, 'kontrabon_index'])->name('keuangan.kontrabon.index');
    Route::post('/keuangan/kontrabon/{nomor_kontrabon}/approve', [KontrabonController::class, 'approve_kontrabon'])->name('keuangan.kontrabon.approve');
    Route::post('/keuangan/kontrabon/{nomor_kontrabon}/tolak', [KontrabonController::class, 'tolak_kontrabon'])->name('keuangan.kontrabon.tolak');
    // Transaksi
    Route::get('/keuangan/transaksi/index', [KeuanganController::class, 'transaksi_index'])->name('keuangan.transaksi.index');
});


// Pelanggan
Route::middleware(['role:pelanggan'])->group(function () {
    Route::get('/pelanggan/index', [PelangganController::class,'index'])->name('pelanggan.index');
    Route::get('pelanggan/produk/{kode_produk}', [PelangganController::class, 'detail_produk'])->name('pelanggan.detail_produk');
    //pesanan
    Route::get('pelanggan/pesanan/keranjang', [PelangganController::class, 'keranjang'])->name('pelanggan.pesanan.keranjang');
    Route::post('pelanggan/pesanan/keranjang/tambah', [PelangganController::class, 'tambah_ke_keranjang'])->name('pelanggan.pesanan.keranjang.tambah');
    Route::post('pelanggan/pesanan/keranjang/update', [PelangganController::class, 'update_keranjang'])->name('pelanggan.pesanan.keranjang.update');
    Route::post('pelanggan/pesanan/keranjang/hapus', [PelangganController::class, 'hapus_keranjang'])->name('pelanggan.pesanan.keranjang.hapus');
    // checkout
    Route::get('pelanggan/pesanan/checkout', [PelangganController::class, 'checkout'])->name('pelanggan.pesanan.checkout');
    Route::post('pelanggan/pesanan/proses_checkout', [PelangganController::class, 'proses_checkout'])->name('pelanggan.pesanan.proses_checkout');
    // refund
    Route::get('/pelanggan/pesanan/index', [PelangganController::class, 'index_pesanan'])->name('pelanggan.pesanan.index');
    Route::get('/pelanggan/pesanan/detail_pesanan/{nomor_pesanan}', [PelangganController::class, 'detail_pesanan'])->name('pelanggan.pesanan.detail_pesanan');
    Route::post('/pelanggan/pesanan/{nomor_pesanan}/cancel_pesanan', [PelangganController::class, 'cancel_pesanan'])->name('pelanggan.pesanan.cancel_pesanan');
    // pembayaran kontrabon
    Route::get('/pembayaran_kontrabon', [PelangganController::class, 'daftar_tagihan_kontrabon'])->name('pelanggan.pesanan.daftar_tagihan');
    Route::get('/pelanggan/pembayaran_kontrabon/index/{nomor_pesanan}', [PelangganController::class, 'index_pembayaran_kontrabon'])->name('pelanggan.pembayaran_kontrabon.index');
    Route::post('/pelanggan/pembayaran_kontrabon/{nomor_pesanan}/proses', [PelangganController::class, 'proses_bayar_kontrabon'])->name('pelanggan.pembayaran_kontrabon.proses');
    });
    
Route::post('/midtrans/callback', [PelangganController::class, 'midtransCallback'])->name('midtrans.callback');