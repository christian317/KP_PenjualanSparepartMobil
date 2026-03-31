<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\Keranjang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $kategori = Kategori::all();
        $brand = Brand::all();

        $query = Produk::with(['kategori', 'brand'])->where('status_produk', 1);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_produk', 'LIKE', "%$search%")
                    ->orWhere('kode_produk', 'LIKE', "%$search%")
                    ->orWhere('part_model', 'LIKE', "%$search%");
            });
        }

        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->has('brand') && $request->brand != '') {
            $query->where('brand_id', $request->brand);
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

        return view('pelanggan.index', compact('produk', 'kategori', 'brand'));
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
        // Ambil user_id dari Session Manual
        $userId = Session::get('user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Ambil data dari tabel keranjang
        $cart = Keranjang::with(['produk.brand'])
            ->where('user_id', $userId)
            ->get();

        return view('pelanggan.pesanan.keranjang', compact('cart'));
    }

    public function tambah_ke_keranjang(Request $request)
    {
        $kodeProduk = $request->id;
        $userId = Session::get('user_id');

        // Ambil jumlah dari input, jika tidak ada default ke 1
        $jumlahInput = $request->input('jumlah', 1);

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Silakan login untuk belanja');
        }

        $produk = Produk::findOrFail($kodeProduk);
        if ($produk->stok_produk < $jumlahInput) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi');
        }

        $exists = DB::table('keranjang')
            ->where('user_id', $userId)
            ->where('produk_id', $kodeProduk)
            ->first();

        if ($exists) {
            DB::table('keranjang')
                ->where('user_id', $userId)
                ->where('produk_id', $kodeProduk)
                ->increment('jumlah', $jumlahInput);
        } else {
            DB::table('keranjang')->insert([
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

        $query = DB::table('keranjang')
            ->where('user_id', $userId)
            ->where('produk_id', $kodeProduk);

        if ($type == 'plus') {
            $query->increment('jumlah');
        } elseif ($type == 'minus') {
            // Ambil data dulu untuk cek jumlah minimal
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

        DB::table('keranjang')
            ->where('user_id', $userId)
            ->where('produk_id', $request->id)
            ->delete();

        return redirect()->route('pelanggan.pesanan.keranjang');
    }
}
