<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    
    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'nama',
        'kategori_id',
        'brand_id',
        'harga',
        'stok',
        'min_stok',
        'unit',
        'gambar',
        'status',
        'preorder',
        'deskripsi',
    ];

    public $timestamps = false;

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function jenisMobil()
    {
        return $this->belongsToMany(JenisMobil::class, 'produk_jenis_mobil', 'produk_id', 'jenis_mobil_id');
    }
}
