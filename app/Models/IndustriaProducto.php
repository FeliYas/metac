<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndustriaProducto extends Model
{
     protected $table = 'industria_producto';

    protected $fillable = [
        'industria_id',
        'producto_id',
    ];

    public function industria()
    {
        return $this->belongsTo(Industria::class, 'industria_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
