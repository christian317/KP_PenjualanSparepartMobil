<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailKontrabon extends Model
{
    protected $table = 'detail_kontrabon';
    public $timestamps = false;
    protected $fillable = [
        'kontrabon_id',
        'nomor_pesanan'
    ];

    function kontrabon()
    {
        return $this->belongsTo(Kontrabon::class, 'kontrabon_id', 'id');
    }
    function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'nomor_pesanan', 'nomor');
    }
}
