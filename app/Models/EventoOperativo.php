<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventoOperativo extends Model
{
    use HasFactory;

    protected $table = 'eventos_operativos';

    protected $fillable = [
        'empleado_id',
        'tipo_evento',
        'fecha_hora',
        'latitud',
        'longitud',
        'origen',
        'destino',
        'vehiculo',
        'lugar',
        'descripcion',
        'ip',
        'device'
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
    ];

    // 🔹 RELACIÓN: pertenece a empleado
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}