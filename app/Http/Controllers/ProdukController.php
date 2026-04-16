<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\JenisMobil;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with(['brand', 'kategori']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_produk', 'like', '%' . $request->search . '%')
                    ->orWhere('kode_produk', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status_produk', $request->status);
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // PERBAIKAN 1: Filter pencarian dropdown
        if ($request->stok == 'menipis') {
            $query->whereColumn('stok_produk', '<=', 'min_stok');
        }

        if ($request->stok == 'ok') {
            $query->whereColumn('stok_produk', '>', 'min_stok');
        }

        $produk = $query->paginate(10)->withQueryString();
        $totalProduk = Produk::count();
        $produkAktif = Produk::where('status_produk', 1)->count();

        // PERBAIKAN 2: Menghitung badge indikator di atas
        $stokMenipis = Produk::whereColumn('stok_produk', '<=', 'min_stok')
            ->where('stok_produk', '>', 0)
            ->count();

        $stokHabis = Produk::where('stok_produk', 0)->count();

        $kategori = Kategori::all();

        return view('admin.produk.index', compact(
            'produk',
            'kategori',
            'totalProduk',
            'produkAktif',
            'stokMenipis',
            'stokHabis'
        ));
    }

    public function create()
    {
        $kategori = Kategori::all();
        $brand = Brand::all();
        $jenis_mobil = JenisMobil::orderBy('merk_mobil', 'asc')->get();

        return view('admin.produk.create', compact('kategori', 'brand', 'jenis_mobil'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kode_produk' => 'required|unique:produk,kode_produk|max:45',
            'harga'       => 'required|numeric|min:0',
            'stok_produk' => 'required|numeric|min:0',
            'min_stok'    => 'required|numeric|min:0',
            'unit'        => 'required|string|max:10',
            'kategori_id' => 'required',
            'brand_id'    => 'required',
            'jenis_mobil_id' => 'required|array|min:1',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'deskripsi_produk' => 'nullable'
        ], [
            'jenis_mobil_id.required' => 'Pilih minimal satu jenis mobil yang cocok untuk produk ini.'
        ]);

        $namaFile = null;

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('produk', $namaFile, 'public');
        }

        $produk = Produk::create([
            'kode_produk'      => $request->kode_produk,
            'nama_produk'      => $request->nama_produk,
            'kategori_id'      => $request->kategori_id,
            'brand_id'         => $request->brand_id,
            'harga'            => $request->harga,
            'stok_produk'      => $request->stok_produk,
            'min_stok'         => $request->min_stok,
            'unit'             => $request->unit,
            'deskripsi_produk' => $request->deskripsi_produk,
            'gambar'           => $namaFile,
            'status_produk'    => $request->status_produk ? 1 : 0
        ]);

        $produk->jenisMobil()->attach($request->jenis_mobil_id);

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($kode_produk)
    {
        $produk = Produk::findOrFail($kode_produk);
        $kategori = Kategori::all();
        $brand = Brand::all();
        $jenis_mobil = JenisMobil::all();
        $selectedMobil = $produk->jenisMobil()->pluck('id')->toArray();

        return view('admin.produk.edit', compact('produk', 'kategori', 'brand', 'jenis_mobil', 'selectedMobil'));
    }

    public function update(Request $request, $kode_produk)
    {
        $produk = Produk::findOrFail($kode_produk);

        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kode_produk' => 'required|max:45|unique:produk,kode_produk,' . $produk->kode_produk . ',kode_produk',
            'harga'       => 'required|numeric|min:0',
            'stok_produk' => 'required|numeric|min:0',
            'min_stok'    => 'required|numeric|min:0',
            'unit'        => 'required|string|max:10',
            'kategori_id' => 'required',
            'brand_id'    => 'required',
            'jenis_mobil_id' => 'required|array|min:1', // <-- Ini wajib ada
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'jenis_mobil_id.required' => 'Pilih minimal satu jenis mobil yang cocok untuk produk ini.'
        ]);

        $namaFile = $produk->gambar;

        if ($request->hasFile('gambar')) {
            if ($produk->gambar && Storage::disk('public')->exists('produk/' . $produk->gambar)) {
                Storage::disk('public')->delete('produk/' . $produk->gambar);
            }
            $file = $request->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('produk', $namaFile, 'public');
        }

        $status = $request->has('status_produk') ? 1 : 0;
        $produk->update([
            'kode_produk'      => $request->kode_produk,
            'nama_produk'      => $request->nama_produk,
            'kategori_id'      => $request->kategori_id,
            'brand_id'         => $request->brand_id,
            'harga'            => $request->harga,
            'stok_produk'      => $request->stok_produk,
            'min_stok'         => $request->min_stok,
            'unit'             => $request->unit,
            'deskripsi_produk' => $request->deskripsi_produk,
            'gambar'           => $namaFile,
            'status_produk'    => $status
        ]);

        $produk->jenisMobil()->sync($request->jenis_mobil_id);

        return redirect()->route('admin.produk.index')
            ->with('toast_success', 'Data produk berhasil diperbarui!');
    }


    public function update_status(Request $request, $kode_produk)
    {
        $produk = Produk::where('kode_produk', $kode_produk)->firstOrFail();

        $statusBaru = $request->has('status_produk') ? 1 : 0;

        $produk->update([
            'status_produk' => $statusBaru
        ]);

        return redirect()->back()->with('success', 'Status produk ' . $produk->nama_produk . ' berhasil diperbarui.');
    }
}
