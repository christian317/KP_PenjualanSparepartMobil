<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    protected $table = 'pembelian';
    protected $fillable = [
        'nomor_pembelian',
        'nama_supplier',
        'tanggal_pembelian',
        'catatan',
    ];

    public $timestamps = false;

    public function details()
    {
        return $this->hasMany(DetailPembelian::class, 'nomor_pembelian_id', 'nomor_pembelian');
    }
}
