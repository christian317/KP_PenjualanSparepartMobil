<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Piutang extends Model
{
    protected $table = 'piutang';

    protected $fillable = [
        'nomor_pesanan',
        'tanggal_jatuh_tempo',
        'total_tagihan',
        'sisa_tagihan',
        'status',
        'tanggal_pelunasan',
    ];

    public $timestamps = false;

    function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'nomor_pesanan', 'nomor_pesanan');
    }
}
