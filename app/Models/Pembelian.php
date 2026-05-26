<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    protected $table = 'pembelian';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'nama_supplier',
        'tanggal',
        'catatan',
    ];

    public $timestamps = false;

    public function details()
    {
        return $this->hasMany(DetailPembelian::class,'pembelian_id','id');
    }
}