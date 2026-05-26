<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function create()
    {
        $kategori = Kategori::all();
        return view('admin.produk.kategori.create', compact('kategori'));
    }

    public function store(Request $request)
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

    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('admin.produk.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
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

    public function delete($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return redirect()->route('admin.produk.kategori.create')->with('success', 'Kategori berhasil dihapus.');
    }
}
