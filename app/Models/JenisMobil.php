<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisMobil extends Model
{
    protected $table = 'jenis_mobil';
    protected $fillable = [
        'merk_mobil',
        'nama_mobil',
        'tahun_mobil'
    ];
    public $timestamps = false;
    
}
