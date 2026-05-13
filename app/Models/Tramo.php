<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tramo extends Model
{
    protected $table = 'tramos';

    protected $fillable = [
        'codigo',
        'nombre',
        'origen_id',
        'destino_id',
        'tipos_vehiculos_id',
        'tipos_servicio_id',
        'distancia_km',
        'tiempo_estimado_min',
        'observaciones',
        'estado',
    ];

    public function origen()
    {
        return $this->belongsTo(Ubicacion::class, 'origen_id');
    }

    public function destino()
    {
        return $this->belongsTo(Ubicacion::class, 'destino_id');
    }

    public function tipoVehiculo()
    {
        return $this->belongsTo(TipoVehiculo::class, 'tipos_vehiculos_id');
    }

    public function tipoServicio()
    {
        return $this->belongsTo(TipoServicio::class, 'tipos_servicio_id');
    }

    public function tarifas()
    {
        return $this->hasMany(TarifaTramo::class, 'tramo_id');
    }
}