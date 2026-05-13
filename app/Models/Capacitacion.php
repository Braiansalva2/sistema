<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capacitacion extends Model
{
    use HasFactory;

    protected $table = 'capacitaciones';

    protected $fillable = [
        'nombre',
        'descripcion',
        'horas',
    ];

    protected $casts = [
        'horas' => 'integer',
    ];
 
    public function empleados()
    {
        return $this->belongsToMany(Empleado::class, 'empleado_capacitacion')
            ->withPivot([
                'fecha_realizada',
                'fecha_vencimiento',
                'dictado_por',
                'constancia_path',
                'observaciones'  
                
            ])
            ->withTimestamps();
    }
}