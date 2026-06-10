<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Produk;

class BrandController extends Controller
{
    public function create()
    {
        $brand = Brand::all();
        return view('admin.produk.brand.create', compact('brand'));
    }

    public function store(Request $request)
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

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.produk.brand.edit', compact('brand'));
    }

    public function update(Request $request, $id)
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

    public function delete($id)
    {
        $brand = Brand::findOrFail($id);

        $brandTerpakai = Produk::where('brand_id', $id)->exists();
        if ($brandTerpakai) {
            return redirect()->back()->with('error', 'Gagal menghapus! Brand ini sedang digunakan oleh satu atau lebih produk.');
        }

        $brand->delete();

        return redirect()->route('admin.produk.brand.create')->with('success', 'Brand berhasil dihapus.');
    }
}
