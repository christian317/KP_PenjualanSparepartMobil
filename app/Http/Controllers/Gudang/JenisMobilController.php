<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JenisMobil;

class JenisMobilController extends Controller
{
    public function create()
    {
        $jenis_mobil = JenisMobil::orderBy('merk', 'asc')->get();
        return view('admin.produk.jenis_mobil.create', compact('jenis_mobil'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'merk' => 'required|string|max:100',
            'nama_model' => 'required|string|max:100',
            'tahun' => 'nullable|string|max:20'
        ]);

        JenisMobil::create([
            'merk' => $request->merk,
            'nama_model' => $request->nama_model,
            'tahun' => $request->tahun
        ]);

        return redirect()->route('admin.produk.jenis_mobil.create')->with('success', 'Jenis mobil berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jenis_mobil = JenisMobil::findOrFail($id);
        return view('admin.produk.jenis_mobil.edit', compact('jenis_mobil'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'merk' => 'required|string|max:100',
            'nama_model' => 'required|string|max:100',
            'tahun' => 'nullable|string|max:20'
        ]);

        $mobil = JenisMobil::findOrFail($id);
        $mobil->update([
            'merk' => $request->merk,
            'nama_model' => $request->nama_model,
            'tahun' => $request->tahun
        ]);

        return redirect()->route('admin.produk.jenis_mobil.create')->with('success', 'Data jenis mobil berhasil diperbarui!');
    }

    public function delete($id)
    {
        $mobil = JenisMobil::findOrFail($id);

        if ($mobil->produks()->exists()) {
            return redirect()->back()->with('error', 'Gagal menghapus! Jenis mobil ini sedang digunakan oleh satu atau lebih produk.');
        }

        $mobil->delete();

        return redirect()->route('admin.produk.jenis_mobil.create')->with('success', 'Data jenis mobil berhasil dihapus!');
    }
}
