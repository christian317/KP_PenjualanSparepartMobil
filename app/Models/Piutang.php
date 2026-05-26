<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Piutang extends Model
{
    protected $table = 'piutang';

    protected $fillable = [
        'kontrabon_id',
        'tanggal_jatuh_tempo',
        'total_tagihan',
        'sisa_tagihan',
        'status',
        'tanggal_pelunasan',
    ];

    public $timestamps = false;

    function kontrabon()
    {
        return $this->belongsTo(Kontrabon::class, 'kontrabon_id', 'id');
    }
}
