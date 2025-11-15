<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'empleado_id',
        'periodo',
        'dias_correspondientes',
        'dias_tomados',
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
