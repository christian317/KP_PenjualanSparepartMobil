<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPembelian extends Model
{
    protected $table = 'detail_pembelian';
    protected $fillable = [
        'nomor_pembelian_id',
        'produk_id',
        'harga_beli',
        'jumlah',
    ];

    public $timestamps = false;

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'kode_produk');
    }
}
