<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViaticoDetalle extends Model
{
    protected $fillable = [
        'viatico_id',
        'concepto',
        'cantidad',
        'precio',
        'subtotal',
        'comentario',
        'observaciones', 
    ];

    public function viatico()
    {
        return $this->belongsTo(Viatico::class);
    }
}