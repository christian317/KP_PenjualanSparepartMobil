<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    protected $table = 'keranjang';
    public $incrementing = false;
    protected $primaryKey = null;
    protected $fillable = [
        'user_id',
        'produk_id', 
        'jumlah'
    ];

    public $timestamps = false;
    
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'kode_produk');
    }
}