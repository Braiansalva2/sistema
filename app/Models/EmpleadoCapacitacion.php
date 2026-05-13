<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpleadoCapacitacion extends Model
{
    use HasFactory;

    protected $table = 'empleado_capacitacion';

    protected $fillable = [
        'empleado_id',
        'capacitacion_id',
        'fecha_realizada',
        'fecha_vencimiento',
        'dictado_por',
        'constancia_path',
        'observaciones',
    ];

    protected $casts = [
        'fecha_realizada' => 'date',
        'fecha_vencimiento' => 'date',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function capacitacion()
    {
        return $this->belongsTo(Capacitacion::class);
    }
}