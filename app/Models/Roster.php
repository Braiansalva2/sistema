<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roster extends Model
{
    use HasFactory;

    protected $table = 'rosters';

    protected $fillable = [
        'nombre',
        'modalidad_trabajo',
        'modalidad_descanso',
        'fecha_subida',
        'fecha_bajada',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha_subida' => 'date',
        'fecha_bajada' => 'date',
    ];

    public function empleados()
    {
        return $this->belongsToMany(
            Empleado::class,
            'roster_empleado',
            'roster_id',
            'empleado_id'
        );
    }

    public function getModalidadAttribute()
    {
        return $this->modalidad_trabajo . 'x' . $this->modalidad_descanso;
    }




    public function proximaSubida()
{
    $hoy = now();
    $fecha = $this->fecha_subida->copy();
    $ciclo = $this->modalidad_trabajo + $this->modalidad_descanso;

    while ($fecha->lt($hoy)) {
        $fecha->addDays($ciclo);
    }

    return $fecha;
}

public function proximaBajada()
{
    return $this->proximaSubida()
        ->copy()
        ->addDays($this->modalidad_trabajo - 1);
}
}