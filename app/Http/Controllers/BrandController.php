<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller
{
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
}
