<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPrendaTalle extends Model
{
    protected $table = 'tipo_prenda_talles';

    protected $fillable = [
        'tipo_prenda_id',
        'nombre',
        'orden',
        'estado'
    ];

    public function tipoPrenda()
    {
        return $this->belongsTo(TipoPrenda::class);
    }
}
