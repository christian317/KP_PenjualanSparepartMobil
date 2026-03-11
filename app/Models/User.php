<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'user';
    protected $fillable = [
        'role_id',
        'nama',
        'nama_toko',
        'email',
        'password',
        'telepon',
        'alamat',
        'status'
    ];
    
}
