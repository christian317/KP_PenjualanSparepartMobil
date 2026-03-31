<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'nomor_pesanan';
    protected $fillable = [
        'user_pelanggan_id',
        'user_admin_id',
        'tanggal_pemesanan',
        'metode_pembayaran',
        'status',
        'catatan',
    ];

    public function UserPelanggan()
    {
        return $this->belongsTo(UserPelanggan::class, 'user_pelanggan_id');
    }
}
