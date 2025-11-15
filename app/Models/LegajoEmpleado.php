<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegajoEmpleado extends Model
{
    use HasFactory;

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
}
