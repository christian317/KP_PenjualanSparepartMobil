<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'nomor_pesanan_id',
        'pesanan_id_midtrans',
        'nominal_pembayaran',
        'status'
    ];

    public $timestamps = false;

    function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'nomor_pesanan', 'nomor_pesanan');
    }
}
