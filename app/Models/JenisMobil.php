<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisMobil extends Model
{
    protected $table = 'jenis_mobil';
    protected $fillable = [
        'merk',
        'nama_model',
        'tahun'
    ];
    public $timestamps = false;
    
}
