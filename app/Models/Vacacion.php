<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Empleado;
use App\Models\User;

class Vacacion extends Model
{
    protected $table = 'vacaciones';

    protected $fillable = [
        'empleado_id',
        'periodo',
        'dias_correspondientes',
        'dias_tomados',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'aprobado_por',
        'fecha_aprobacion',
        'observaciones'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'fecha_aprobacion' => 'date',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    public function aprobadoPorUsuario()
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }
}