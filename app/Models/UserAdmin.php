<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAdmin extends Model
{
    protected $table = 'user_admin';
    protected $fillable = [
        'role_id',
        'email',
        'password',
    ];
    
    public $timestamps = false;
    
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
