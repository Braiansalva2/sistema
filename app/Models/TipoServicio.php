<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoServicio extends Model
{

     protected $table = 'tipos_servicio';

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    public function tramos()
    {
        return $this->hasMany(Tramo::class, 'tipo_servicio_id');
    }
}
