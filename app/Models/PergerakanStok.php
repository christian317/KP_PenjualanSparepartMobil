<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PergerakanStok extends Model
{
    protected $table = 'pergerakan_stok';

    protected $fillable = [
        'produk_id',
        'tipe_pergerakan',
        'jumlah',
        'ripe_referensi',
        'catatan'
    ];

    public $timestamps = false;

    function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
