<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoVehiculo extends Model
{
    use HasFactory;

    protected $table = 'tipos_vehiculos';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    // Relación: un tipo de vehículo puede tener muchas unidades
    public function unidades()
    {
        return $this->hasMany(Unidad::class, 'tipo_vehiculo_id');
    }
}
