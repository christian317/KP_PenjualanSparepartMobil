<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    
    // 1. Tentukan Primary Key baru
    protected $primaryKey = 'kode_produk';

    // 2. Beritahu Laravel bahwa PK bukan Integer (karena kode biasanya string)
    protected $keyType = 'string';

    // 3. Beritahu Laravel bahwa PK tidak auto-increment
    public $incrementing = false;

    protected $fillable = [
        'kode_produk',
        'nama_produk',
        'kategori_id',
        'brand_id',
        'harga',
        'stok_produk',
        "min_stok",
        'unit',
        'deskripsi_produk',
        'gambar',
        'status_produk',
    ];

    public $timestamps = false;

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function jenisMobil()
    {
        return $this->belongsToMany(JenisMobil::class, 'produk_jenis_mobil', 'produk_id', 'jenis_mobil_id');
    }
}
