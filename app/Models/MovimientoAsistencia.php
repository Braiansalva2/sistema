<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoAsistencia extends Model
{
    use HasFactory;

    protected $table = 'movimientos_asistencia';

    protected $fillable = [
        'empleado_id',
        'tipo',
        'fecha_hora',
        'latitud',
        'longitud',
        'precision_gps',
        'con_ubicacion',
        'ip',
        'device',
        'foto',
        'observaciones',
        'estado_gps',
        'distancia_base_metros'
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'con_ubicacion' => 'boolean',
    ];

    // RELACIÓN
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}