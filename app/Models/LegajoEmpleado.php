<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LegajoEmpleado extends Model
{
    use HasFactory;

     protected $table = 'legajos_empleados';
    protected $fillable = [
        'empleado_id',
        'nombre_archivo',
        'descripcion',
        'archivo_path',
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

      public function getEstadoCalculadoAttribute()
{
    if (!$this->fecha_fin) {
        return 'vigente';
    }

    $fin = Carbon::parse($this->fecha_fin);
    $hoy = Carbon::today();

    if ($fin->isPast()) {
        return 'vencido';
    }

    if ($hoy->diffInDays($fin) <= 14) {
        return 'por_vencer';
    }

    return 'vigente';
}
}
