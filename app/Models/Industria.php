<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Industria extends Model
{
    protected $fillable = [
        'orden',
        'banners',
        'portada',
        'path',
        'titulo',
        'subtitulo',
        'descripcion',
    ];

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'industria_producto', 'industria_id', 'producto_id');
    }

}
