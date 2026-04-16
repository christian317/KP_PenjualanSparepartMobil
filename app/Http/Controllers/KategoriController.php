<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
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
