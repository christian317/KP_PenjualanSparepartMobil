<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'nomor';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'nomor',
        'user_pelanggan_id',
        'user_admin_id',
        'tanggal',
        'metode_pembayaran',
        'status_pembayaran',
        'status',
        'catatan',
        'updated_at'
    ];

    public $timestamps = false;
    const updated_at = 'updated_at';

    public function UserPelanggan()
    {
        return $this->belongsTo(UserPelanggan::class, 'user_pelanggan_id', 'id');
    }

    public function kontrabon()
    {
        return $this->belongsToMany(Kontrabon::class, 'detail_kontrabon', 'nomor_pesanan', 'kontrabon_id');
    }

    public function items()
    {
        return $this->hasMany(DetailPesanan::class, 'nomor_pesanan', 'nomor');
    }

    public function refund()
    {
        return $this->hasOne(PengajuanRefund::class, 'nomor_pesanan', 'nomor');
    }

}
