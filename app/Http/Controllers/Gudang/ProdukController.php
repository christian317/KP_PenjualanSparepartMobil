<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
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
        $totalProduk = Produk::count();
        $produkAktif = Produk::where('status', 1)->count();
        $stokHabis = Produk::where('stok', 0)->count();
        $stokMenipis = Produk::whereColumn('stok', '<=', 'min_stok')
            ->where('stok', '>', 0)
            ->count();
        $kategori = Kategori::all();
        
        // Filter
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                    ->orWhere('id', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }
        if ($request->stok == 'menipis') {
            $query->whereColumn('stok', '<=', 'min_stok');
        }
        if ($request->stok == 'ok') {
            $query->whereColumn('stok', '>', 'min_stok');
        }

        // Tabel pagination
        $produk = $query->paginate(10)->withQueryString();

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
        $jenis_mobil = JenisMobil::orderBy('merk', 'asc')->get();

        return view('admin.produk.create', compact('kategori', 'brand', 'jenis_mobil'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:150',
            'id' => 'required|unique:produk,id|max:30',
            'harga'       => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'min_stok'    => 'required|numeric|min:0',
            'unit'        => 'required|string|max:10',
            'kategori_id' => 'required',
            'brand_id'    => 'required',
            'jenis_mobil_id' => 'required|array|min:1',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'deskripsi' => 'nullable'
        ], [
            'jenis_mobil_id.required' => 'Pilih minimal satu jenis mobil yang cocok untuk produk ini.'
        ]);

        // Upload gambar
        $namaFile = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('produk', $namaFile, 'public');
        }

        $produk = Produk::create([
            'id'      => $request->id,
            'nama'      => $request->nama,
            'kategori_id'      => $request->kategori_id,
            'brand_id'         => $request->brand_id,
            'harga'            => $request->harga,
            'stok'      => $request->stok,
            'min_stok'         => $request->min_stok,
            'unit'             => $request->unit,
            'deskripsi' => $request->deskripsi,
            'gambar'           => $namaFile,
            'status'    => $request->status ? 1 : 0,
            'preorder' => $request->preorder ? 1 : 0
        ]);

        $produk->jenisMobil()->attach($request->jenis_mobil_id);

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        $kategori = Kategori::all();
        $brand = Brand::all();
        $jenis_mobil = JenisMobil::all();
        $selectedMobil = $produk->jenisMobil()->pluck('jenis_mobil.id')->toArray();

        return view('admin.produk.edit', compact('produk', 'kategori', 'brand', 'jenis_mobil', 'selectedMobil'));
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'id' => 'required|max:30|unique:produk,id,' . $produk->id . ',id',
            'harga'       => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'min_stok'    => 'required|numeric|min:0',
            'unit'        => 'required|string|max:10',
            'kategori_id' => 'required',
            'brand_id'    => 'required',
            'jenis_mobil_id' => 'required|array|min:1',
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

        $status = $request->has('status') ? 1 : 0;
        $produk->update([
            'id'      => $request->id,
            'nama'      => $request->nama,
            'kategori_id'      => $request->kategori_id,
            'brand_id'         => $request->brand_id,
            'harga'            => $request->harga,
            'stok'      => $request->stok,
            'min_stok'         => $request->min_stok,
            'unit'             => $request->unit,
            'deskripsi' => $request->deskripsi,
            'gambar'           => $namaFile,
            'status'    => $status,
            'preorder' => $request->preorder ? 1 : 0
        ]);

        $produk->jenisMobil()->sync($request->jenis_mobil_id);

        return redirect()->route('admin.produk.index')
            ->with('success', 'Data produk berhasil diperbarui!');
    }


    public function update_status(Request $request, $id)
    {
        $produk = Produk::where('id', $id)->firstOrFail();
        $statusBaru = $request->has('status') ? 1 : 0;
        $produk->update([
            'status' => $statusBaru
        ]);

        return redirect()->back()->with('success', 'Status produk ' . $produk->nama . ' berhasil diperbarui.');
    }
}
