<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    protected $table = 'sucursales';

protected $fillable = [
    'nombre',
    'codigo',
    'direccion',
    'localidad',
    'provincia',
    'telefono',
    'email',
    'estado',
    'observaciones',
];


    /**
     * Una sucursal tiene muchos empleados
     */
    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }

    public function ubicaciones()
    {
        return $this->hasMany(UbicacionAsistencia::class);
    }
}

                                    