<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Keranjang;
use App\Models\Produk;
use Illuminate\Support\Facades\Session;

class KeranjangController extends Controller
{
    public function index()
    {
        $userId = Session::get('user_pelanggan_id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $cart = Keranjang::with(['produk.brand'])->where('user_pelanggan_id', $userId)->get();
        return view('pelanggan.checkout.keranjang', compact('cart'));
    }

    public function create(Request $request)
    {
        $kodeProduk = $request->id;
        $userId = Session::get('user_pelanggan_id');
        $jumlahInput = $request->input('jumlah', 1);

        if (!$userId){
             return redirect()->route('login')->with('error', 'Silakan login untuk belanja');
        }

        $produk = Produk::findOrFail($kodeProduk);
        
        if ($produk->preorder == 0 && $produk->stok < $jumlahInput) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi');
        }

        $exists = Keranjang::where('user_pelanggan_id', $userId)->where('produk_id', $kodeProduk)->first();

        if ($exists) {
            $totalDiminta = $exists->jumlah + $jumlahInput;
            if ($produk->preorder == 0 && $produk->stok < $totalDiminta) {
                return redirect()->back()->with('error', 'Stok maksimal produk ini sudah mencapai batas di keranjang Anda');
            }
            
            $exists->increment('jumlah', $jumlahInput);
        } else {
            Keranjang::create(['user_pelanggan_id' => $userId, 'produk_id' => $kodeProduk, 'jumlah' => $jumlahInput]);
        }

        return redirect()->back()->with('toast_success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request)
    {
        $keranjang = Keranjang::with('produk')
            ->where('user_pelanggan_id', Session::get('user_pelanggan_id'))
            ->where('produk_id', $request->id)
            ->first();

        if (!$keranjang) {
            return redirect()->route('pelanggan.checkout.keranjang');
        }

        if ($request->type == 'plus') {
            if ($keranjang->produk->preorder == 0 && $keranjang->produk->stok <= $keranjang->jumlah) {
                return redirect()->route('pelanggan.checkout.keranjang')->with('error', 'Maaf, stok maksimal telah tercapai.');
            }
            $keranjang->increment('jumlah');
            
        } elseif ($request->type == 'minus') {
            if ($keranjang->jumlah > 1) {
                $keranjang->decrement('jumlah');
            }
        }
        
        return redirect()->route('pelanggan.checkout.keranjang');
    }

    public function delete(Request $request)
    {
        Keranjang::where('user_pelanggan_id', Session::get('user_pelanggan_id'))->where('produk_id', $request->id)->delete();
        return redirect()->route('pelanggan.checkout.keranjang');
    }
}
