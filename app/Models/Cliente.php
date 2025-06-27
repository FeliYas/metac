<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Cliente extends Model
{
    protected $fillable = ['nombre', 'apellido', 'usuario', 'email', 'telefono', 'cuit','rol', 'password', 'autorizado'];

    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $casts = [
        'password' => 'hashed', // Laravel 10+ automáticamente hashea
    ];
}
