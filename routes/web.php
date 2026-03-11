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
Route::get('/admin/produk/index', [ProdukController::class,'index'])->name('admin.produk.index');
Route::get('/admin/produk/create', [ProdukController::class,'create'])->name('admin.produk.create');
// Keuangan
Route::get('/keuangan/index', [KeuanganController::class,'index'])->name('keuangan.index');
// Pelanggan
Route::get('/pelanggan/index', [PelangganController::class,'index'])->name('pelanggan.index');