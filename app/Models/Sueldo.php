<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sueldo extends Model
{
    use HasFactory;

    protected $table = 'sueldos';

    protected $fillable = [
        'empleado_id',
        'sueldo_base',
        'valor_hora',
        'porcentaje_hora_extra',
        'fecha_desde',
        'fecha_hasta',
        'activo',
    ];

    protected $casts = [
        'sueldo_base' => 'decimal:2',
        'valor_hora' => 'decimal:2',
        'porcentaje_hora_extra' => 'decimal:2',
        'fecha_desde' => 'date',
        'fecha_hasta' => 'date',
        'activo' => 'boolean',

        
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}