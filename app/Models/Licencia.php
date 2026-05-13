<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Licencia extends Model
{
    use HasFactory;

    protected $table = 'licencias';

    protected $fillable = [
        'empleado_id',
        'tipo',
        'fecha_desde',
        'fecha_hasta',  
        'hora_desde',
        'hora_hasta',
        'dias',
        'horas',
        'archivo',
        'observaciones',
        'estado',
        'aprobado_por',
        'fecha_aprobacion',
    ];

    protected $casts = [
        'fecha_desde' => 'date',
        'fecha_hasta' => 'date',
        'fecha_aprobacion' => 'date',
    ];

    //  RELACIÓN CON EMPLEADO
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    //  QUIÉN APROBÓ
    public function aprobador()
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }


}