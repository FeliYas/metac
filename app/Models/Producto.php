<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'orden',
        'titulo',
        'subtitulo',
        'descripcion',
        'subcategoria_id',
        'fichatecnica',
        'fichaseguridad'
    ];

    public function subcategoria()
    {
        return $this->belongsTo(Subcategoria::class);
    }

    public function industrias()
    {
        return $this->belongsToMany(Industria::class, 'industria_producto', 'producto_id', 'industria_id');
    }
}
