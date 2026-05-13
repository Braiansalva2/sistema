<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    use HasFactory;

    protected $table = 'unidades';

    protected $fillable = [
    'marca_id',
    'modelo_id',
    'tipo_vehiculo_id',
    'cod_interno',
    'dominio',
    'anio',
    'color',
    'km_actual',
    'origen',
    'empresa_tercerizada_id',
    'capacidad_kg',
    'largo_total',
    'alto',
    'ancho',
    'estado',
    'fecha_alta',
    'fecha_baja',
    'observaciones',

    'strix_thing_id',
    'tiene_gps',
];
  

    // Relaciones
    public function marca()
    {
        return $this->belongsTo(Marca::class, 'marca_id');
    }

    public function modelo()
    {
        return $this->belongsTo(ModeloVehiculo::class, 'modelo_id');
    }

    public function tipoVehiculo()
    {
        return $this->belongsTo(TipoVehiculo::class, 'tipo_vehiculo_id');
    }

    public function legajoTecnico()
    {
        return $this->hasOne(LegajoTecnicoUnidad::class, 'unidad_id');
    }
    public function legajos()
    {
        return $this->hasMany(LegajoVehicular::class);
    }
public function empresa()
{
    return $this->belongsTo(EmpresaTercerizada::class, 'empresa_tercerizada_id');
}
public function empresaTercerizada()
{
    return $this->belongsTo(EmpresaTercerizada::class);
}

}
