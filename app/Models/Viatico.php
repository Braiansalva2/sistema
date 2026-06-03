<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Viatico extends Model
{
    use HasFactory;

    protected $table = 'viaticos';

    protected $fillable = [
        'codigo',
        'empleado_id',
        'movil',
        'origen',
        'destino',
        'fecha_salida',
        'fecha_regreso',
        'dias',
        'dias_extra',
        'total',
        'archivo_firmado',
        'estado',
        'viatico_padre_id',
        'es_extension',
        'observaciones'
    ];

      protected $casts = [
        'fecha_salida' => 'datetime',
        'fecha_regreso' => 'datetime',
    ];

    //  RELACIÓN CON EMPLEADO
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    //  DETALLES DE GASTOS
    public function detalles()
    {
        return $this->hasMany(ViaticoDetalle::class);
    }

    //  VIÁTICO PADRE (ORIGINAL)
    public function padre()
    {
        return $this->belongsTo(Viatico::class, 'viatico_padre_id');
    }

    //  EXTENSIONES
    public function extensiones()
    {
        return $this->hasMany(Viatico::class, 'viatico_padre_id');
    }
}