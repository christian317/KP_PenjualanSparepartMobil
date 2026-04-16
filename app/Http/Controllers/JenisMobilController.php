<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisMobil;

class JenisMobilController extends Controller
{
    public function create_jenis_mobil(Request $request)
    {

        $jenis_mobil = JenisMobil::orderBy('merk_mobil', 'asc')->get();

        return view('admin.produk.jenis_mobil.create', compact('jenis_mobil'));
    }

    public function store_jenis_mobil(Request $request)
    {
        $request->validate([
            'merk_mobil' => 'required|string|max:100',
            'nama_mobil' => 'required|string|max:100',
            'tahun_mobil' => 'nullable|string|max:20'
        ]);

        JenisMobil::create([
            'merk_mobil' => $request->merk_mobil,
            'nama_mobil' => $request->nama_mobil,
            'tahun_mobil' => $request->tahun_mobil
        ]);

        return redirect()->route('admin.produk.jenis_mobil.create')->with('success', 'Jenis mobil berhasil ditambahkan.');
    }

    public function update_jenis_mobil(Request $request, $id)
    {
        $request->validate([
            'merk_mobil' => 'required|string|max:100',
            'nama_mobil' => 'required|string|max:100',
            'tahun_mobil' => 'nullable|string|max:20'
        ]);

        $mobil = JenisMobil::findOrFail($id);
        $mobil->update([
            'merk_mobil' => $request->merk_mobil,
            'nama_mobil' => $request->nama_mobil,
            'tahun_mobil' => $request->tahun_mobil
        ]);

        return redirect()->route('admin.produk.jenis_mobil.create')->with('success', 'Data jenis mobil berhasil diperbarui!');
    }

    public function delete_jenis_mobil($id)
    {
        $mobil = JenisMobil::findOrFail($id);
        $mobil->delete();

        return redirect()->route('admin.produk.jenis_mobil.create')->with('success', 'Data jenis mobil berhasil dihapus!');
    }
}
