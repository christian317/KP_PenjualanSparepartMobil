<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Pembelian;
use App\Models\DetailPembelian;
use App\Models\PergerakanStok;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembelian::with(['details.produk']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_pembelian', 'LIKE', "%$search%")
                    ->orWhere('nama_supplier', 'LIKE', "%$search%");
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('tanggal_pembelian', $request->date);
        }

        $pembelian = $query->orderBy('tanggal_pembelian', 'desc')->paginate(10);

        return view('admin.pembelian.index', compact('pembelian'));
    }

    public function create()
    {
        $semuaProduk = Produk::select('kode_produk', 'nama_produk')->get();
        return view('admin.pembelian.create', compact('semuaProduk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_pembelian'   => 'required|unique:pembelian,nomor_pembelian',
            'nama_supplier'     => 'required|string|max:100',
            'tanggal_pembelian' => 'required',
            'items'             => 'required|array|min:1',
            'items.*.kode_produk' => 'required|exists:produk,kode_produk',
            'items.*.jumlah'      => 'required|integer|min:1',
        ], [
            'items.*.kode_produk.exists' => 'Salah satu Kode Produk tidak ditemukan. Pastikan produk sudah terdaftar.',
        ]);

        DB::beginTransaction();
        try {
            $pembelian = Pembelian::create([
                'nomor_pembelian'   => $request->nomor_pembelian,
                'nama_supplier'     => $request->nama_supplier,
                'tanggal_pembelian' => $request->tanggal_pembelian,
                'catatan'           => $request->catatan,
            ]);

            foreach ($request->items as $item) {
                DetailPembelian::create([
                    'nomor_pembelian_id' => $pembelian->nomor_pembelian,
                    'produk_id'          => $item['kode_produk'],
                    'jumlah'             => $item['jumlah'],
                ]);

                // Update Stok
                $produk = Produk::where('kode_produk', $item['kode_produk'])->first();
                $produk->increment('stok_produk', $item['jumlah']);

                // Catat Riwayat
                PergerakanStok::create([
                    'produk_id'       => $item['kode_produk'],
                    'tipe_pergerakan' => '0',
                    'jumlah'          => $item['jumlah'],
                    'tipe_referensi'  => '0',
                    'catatan'         => 'Pembelian No: ' . $pembelian->nomor_pembelian,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.pembelian.index')->with('success', 'Stok berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->withErrors(['error' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    public function searchByKode($kode)
    {
        $produk = Produk::where('kode_produk', $kode)->first();

        if ($produk) {
            return response()->json([
                'success' => true,
                'nama_produk' => $produk->nama_produk   
            ]);
        }

        return response()->json(['success' => false]);
    }
}
