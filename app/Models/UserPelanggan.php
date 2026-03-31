<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPelanggan extends Model
{
    protected $table = 'user_pelanggan';
    protected $fillable = [
        'nama',
        'nama_toko',
        'email',
        'password',
        'telepon',
        'alamat',
        'status',
        'status_bengkel'
    ];
    
    public $timestamps = false;
    
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
