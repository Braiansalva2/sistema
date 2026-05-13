<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpleadoTalle extends Model
{
    protected $table = 'empleado_talles';

    protected $fillable = [
        'empleado_id',
        'tipo_prenda_id',
        'tipo_prenda_talle_id'
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function tipoPrenda()
    {
        return $this->belongsTo(TipoPrenda::class);
    }

    public function talle()
    {
        return $this->belongsTo(TipoPrendaTalle::class, 'tipo_prenda_talle_id');
    }
}
