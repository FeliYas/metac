<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nosotros extends Model
{
    protected $table = 'nosotros'; 
    protected $fillable = [
        'path',
        'titulo',
        'descripcion',
        'path2',
        'titulo2',
        'descripcion2',
    ];

}
