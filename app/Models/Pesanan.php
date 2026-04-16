<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'nomor_pesanan';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'nomor_pesanan',
        'user_pelanggan_id',
        'user_admin',
        'tanggal_pemesanan',
        'metode_pembayaran',
        'status_pembayaran',
        'status_pesanan',
        'catatan',
    ];

    public $timestamps = false;

    public function UserPelanggan()
    {
        return $this->belongsTo(UserPelanggan::class, 'user_pelanggan_id');
    }
}
