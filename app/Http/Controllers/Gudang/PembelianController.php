<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Pembelian;
use App\Models\DetailPembelian;
use App\Models\PergerakanStok;

class PembelianController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembelian::with(['details.produk']);

        // Filter search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'LIKE', "%$search%")
                    ->orWhere('nama_supplier', 'LIKE', "%$search%");
            });
        }

        // Filter waktu
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        } else {
            if ($request->filled('bulan')) $query->whereMonth('tanggal', $request->bulan);
            if ($request->filled('tahun')) $query->whereYear('tanggal', $request->tahun);
        }

        // Tabel Pagination
        $pembelian = $query->orderBy('tanggal', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.pembelian.index', compact('pembelian'));
    }


    public function create()
    {
        $semuaProduk = Produk::select('id', 'nama')->get();
        return view('admin.pembelian.create', compact('semuaProduk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id'                 => 'required|unique:pembelian,id',
            'nama_supplier'      => 'required|string|max:100',
            'tanggal'            => 'required',
            'items'              => 'required|array|min:1',
            'items.*.id'         => 'required|exists:produk,id|distinct',
            'items.*.jumlah'     => 'required|integer|min:1',
        ], [
            'items.*.id.exists'   => 'Salah satu produk tidak ditemukan.',
            'items.*.id.distinct' => 'Produk yan    g sama tidak boleh dipilih lebih dari satu kali.',
        ]);

        $pembelian = Pembelian::create([
            'id'             => $request->id,
            'nama_supplier'  => $request->nama_supplier,
            'tanggal'        => $request->tanggal,
            'catatan'        => $request->catatan,
        ]);

        foreach ($request->items as $item) {

            DetailPembelian::create([
                'pembelian_id' => $pembelian->id,
                'produk_id'    => $item['id'],
                'jumlah'       => $item['jumlah'],
            ]);

            $produk = Produk::find($item['id']);
            $produk->increment('stok', $item['jumlah']);

            PergerakanStok::create([
                'produk_id'       => $item['id'],
                'tipe_pergerakan' => 0,
                'jumlah'          => $item['jumlah'],
                'tipe_referensi'  => 0,
                'catatan'         => 'Pembelian No: ' . $pembelian->id,
            ]);
        }

        return redirect()->route('admin.pembelian.index')
            ->with('success', 'Pembelian berhasil disimpan!');
    }

    public function searchByKode($kode)
    {
        $produk = Produk::where('id', $kode)->first();

        if ($produk) {
            return response()->json([
                'success' => true,
                'nama' => $produk->nama
            ]);
        }

        return response()->json(['success' => false]);
    }
}
