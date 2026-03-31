<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ProdukController;


Route::get('/', [AuthController::class,'loginPage'])->name('login');
Route::post('/login', [AuthController::class,'login']);

Route::get('/register', [AuthController::class,'registerPage'])->name('register');
Route::post('/register', [AuthController::class,'register']);

Route::get('/logout', [AuthController::class,'logout'])->name('logout');

// Admin
Route::get('/admin/index', [AdminController::class,'index'])->name('admin.index');
// User
Route::get('/admin/user/index', [AdminController::class,'user_index'])->name('admin.user.index');
Route::get('/admin/user/create', [AdminController::class,'user_create'])->name('admin.user.create');
Route::post('/admin/user/store', [AdminController::class,'user_store'])->name('admin.user.store');
Route::get('/admin/user/edit/{id}', [AdminController::class,'user_edit'])->name('admin.user.edit');
Route::post('/admin/user/update/{id}', [AdminController::class,'user_update'])->name('admin.user.update');
//pruduk
Route::get('/admin/produk/index', [ProdukController::class,'index'])->name('admin.produk.index');
Route::get('/admin/produk/create', [ProdukController::class,'create'])->name('admin.produk.create');
Route::post('/admin/produk/store', [ProdukController::class,'store'])->name('admin.produk.store');
Route::get('/admin/produk/edit/{kode_produk}', [ProdukController::class,'edit'])->name('admin.produk.edit');
Route::post('/admin/produk/update/{kode_produk}', [ProdukController::class,'update'])->name('admin.produk.update');
Route::get('/admin/produk/delete/{kode_produk}', [ProdukController::class,'delete'])->name('admin.produk.delete');
// Brand
Route::get('/admin/produk/brand/create', [ProdukController::class,'create_brand'])->name('admin.produk.brand.create');
Route::post('/admin.produk.brand.store', [ProdukController::class,'store_brand'])->name('admin.produk.brand.store');
Route::get('/admin/produk/brand/edit/{id}', [ProdukController::class,'edit_brand'])->name('admin.produk.brand.edit');
Route::post('/admin.produk.brand.update/{id}', [ProdukController::class,'update_brand'])->name('admin.produk.brand.update');
Route::get('/admin/produk/brand/delete/{id}', [ProdukController::class,'delete_brand'])->name('admin.produk.brand.delete');
// Kategori
Route::get('/admin/produk/kategori/create', [ProdukController::class,'create_kategori'])->name('admin.produk.kategori.create');
Route::post('/admin.produk.kategori.store', [ProdukController::class,'store_kategori'])->name('admin.produk.kategori.store');
Route::get('/admin/produk/kategori/edit/{id}', [ProdukController::class,'edit_kategori'])->name('admin.produk.kategori.edit');
Route::post('/admin.produk.kategori.update/{id}', [ProdukController::class,'update_kategori'])->name('admin.produk.kategori.update');
Route::get('/admin/produk/kategori/delete/{id}', [ProdukController::class,'delete_kategori'])->name('admin.produk.kategori.delete');

// Keuangan
Route::get('/keuangan/index', [KeuanganController::class,'index'])->name('keuangan.index');
// Pelanggan
Route::get('/pelanggan/index', [PelangganController::class,'index'])->name('pelanggan.index');
Route::get('pelanggan/produk/{kode_produk}', [PelangganController::class, 'detail_produk'])->name('pelanggan.detail_produk');
//pesanan
Route::get('pelanggan/pesanan/keranjang', [PelangganController::class, 'keranjang'])->name('pelanggan.pesanan.keranjang');
Route::post('pelanggan/pesanan/keranjang/tambah', [PelangganController::class, 'tambah_ke_keranjang'])->name('pelanggan.pesanan.keranjang.tambah');
Route::post('pelanggan/pesanan/keranjang/update', [PelangganController::class, 'update_keranjang'])->name('pelanggan.pesanan.keranjang.update');
Route::post('pelanggan/pesanan/keranjang/hapus', [PelangganController::class, 'hapus_keranjang'])->name('pelanggan.pesanan.keranjang.hapus');