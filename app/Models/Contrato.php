<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    use HasFactory;

    protected $fillable = ['tipo_contrato', 
    'fecha_inicio',
    'fecha_fin',
     'estado'];

    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }
}
