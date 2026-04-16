<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    protected $table = 'detail_pesanan';
    protected $fillable = [
        'nomor_pesanan_fk',
        'produk_id',
        'jumlah',
        'harga',
    ];

    public $timestamps = false;

    function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'nomor_pesanan_fk');
    }
}
