<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsentimientoAsistencia extends Model
{
    use HasFactory;

    protected $table = 'consentimientos_asistencia';

    protected $fillable = [
        'empleado_id',
        'texto_aceptado',
        'version',
        'aceptado',
        'fecha_aceptacion',
        'ip',
        'device'
    ];

    protected $casts = [
        'fecha_aceptacion' => 'datetime',
        'aceptado' => 'boolean',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}