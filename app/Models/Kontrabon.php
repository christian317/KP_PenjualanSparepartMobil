<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kontrabon extends Model
{
    protected $table = 'kontrabon';
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'user_pelanggan_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'total_tagihan',
        'status'
    ];

    public function pesanan()
    {
        return $this->belongsToMany(Pesanan::class, 'detail_kontrabon', 'kontrabon_id', 'nomor_pesanan');
    }

    public function piutang()
    {
        return $this->hasOne(Piutang::class, 'kontrabon_id', 'id');
    }
}