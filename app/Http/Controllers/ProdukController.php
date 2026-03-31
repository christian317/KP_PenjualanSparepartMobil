<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


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

        if ($request->stok == 'menipis') {
            $query->where('stok_produk', '<', 10);
        }

        if ($request->stok == 'ok') {
            $query->where('stok_produk', '>=', 10);
        }

        $produk = $query->paginate(10)->withQueryString();
        $totalProduk = Produk::count();
        $produkAktif = Produk::where('status_produk', 1)->count();
        $stokMenipis = Produk::where('stok_produk', '<', 10)->count();
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
        return view('admin.produk.create', compact('kategori', 'brand'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kode_produk' => 'required|unique:produk,kode_produk|max:45',
            'part_model'  => 'required|string|max:255',
            'harga'       => 'required|numeric|min:0',
            'stok_produk' => 'required|numeric|min:0',
            'unit'        => 'required|string|max:10',
            'kategori_id' => 'required',
            'brand_id'    => 'required',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'deskripsi_produk' => 'nullable'
        ]);

        $namaFile = null;

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('produk', $namaFile, 'public');
        }

        Produk::create([
            'kode_produk'      => $request->kode_produk,
            'nama_produk'      => $request->nama_produk,
            'part_model'       => $request->part_model,
            'kategori_id'      => $request->kategori_id,
            'brand_id'         => $request->brand_id,
            'harga'            => $request->harga,
            'stok_produk'      => $request->stok_produk,
            'unit'             => $request->unit,
            'deskripsi_produk' => $request->deskripsi_produk,
            'gambar'           => $namaFile,
            'status_produk'    => $request->status_produk ? 1 : 0
        ]);

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($kode_produk)
    {
        $produk = Produk::findOrFail($kode_produk);
        $kategori = Kategori::all();
        $brand = Brand::all();
        return view('admin.produk.edit', compact('produk', 'kategori', 'brand'));
    }

    public function update(Request $request, $kode_produk)
    {
        $produk = Produk::findOrFail($kode_produk);

        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kode_produk' => 'required|max:45|unique:produk,kode_produk,' . $produk->kode_produk . ',kode_produk',
            'part_model'  => 'required|string|max:255',
            'harga'       => 'required|numeric|min:0',
            'stok_produk' => 'required|numeric|min:0',
            'unit'        => 'required|string|max:10',
            'kategori_id' => 'required',
            'brand_id'    => 'required',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
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
            'part_model'       => $request->part_model,
            'kategori_id'      => $request->kategori_id,
            'brand_id'         => $request->brand_id,
            'harga'            => $request->harga,
            'stok_produk'      => $request->stok_produk,
            'unit'             => $request->unit,
            'deskripsi_produk' => $request->deskripsi_produk,
            'gambar'           => $namaFile,
            'status_produk'    => $status
        ]);

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil diperbarui');
    }

    public function create_brand()
    {
        $brand = Brand::all();
        return view('admin.produk.brand.create', compact('brand'));
    }

    public function store_brand(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable'
        ]);
        Brand::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect()->route('admin.produk.brand.create')->with('success', 'Brand berhasil ditambahkan.');
    }

    public function edit_brand($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.produk.brand.edit', compact('brand'));
    }

    public function update_brand(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable'
        ]);

        $brand = Brand::findOrFail($id);
        $brand->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect()->route('admin.produk.brand.create')->with('success', 'Brand berhasil diperbarui.');
    }

    public function delete_brand($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();

        return redirect()->route('admin.produk.brand.create')->with('success', 'Brand berhasil dihapus.');
    }

    public function create_kategori()
    {
        $kategori = Kategori::all();
        return view('admin.produk.kategori.create', compact('kategori'));
    }

    public function store_kategori(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable'
        ]);
        Kategori::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect()->route('admin.produk.kategori.create')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit_kategori($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('admin.produk.kategori.edit', compact('kategori'));
    }

    public function update_kategori(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable'
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect()->route('admin.produk.kategori.create')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function delete_kategori($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return redirect()->route('admin.produk.kategori.create')->with('success', 'Kategori berhasil dihapus.');
    }
}
