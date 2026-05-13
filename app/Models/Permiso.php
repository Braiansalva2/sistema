<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $fillable = [
        'empleado_id',
        'tipo',
        'fecha_desde',
        'fecha_hasta',
        'total_dias',
        'fecha_horas',
        'hora_desde',
        'hora_hasta',
        'total_horas',
        'motivo',
        'estado',
        'fecha_aprobacion',
        'aprobado_por',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function aprobador()
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }
}
