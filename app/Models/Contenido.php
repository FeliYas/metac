<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contenido extends Model
{
    protected $table = 'contenido';
    protected $fillable = [
        'path',
        'logo',
        'titulo',
        'descripcion',
    ];
}
