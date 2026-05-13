<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarifaTramo extends Model
{
    protected $table = 'tarifas_tramo';

    protected $fillable = [
        'tramo_id',
        'tipo',
        'precio',
        'fecha_desde',
        'fecha_hasta',
        'activo',
        'motivo',
    ];

    protected $casts = [
        'fecha_desde' => 'date',
        'fecha_hasta' => 'date',
        'activo' => 'boolean',
    ];

    public function tramo()
    {
        return $this->belongsTo(Tramo::class, 'tramo_id');
    }
} 