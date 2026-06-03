<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdelantoCuota extends Model
{
    use HasFactory;

    protected $table = 'adelanto_cuotas';

    protected $fillable = [
        'adelanto_id',
        'numero_cuota',
        'monto',
        'fecha_vencimiento',
        'fecha_pago',
        'estado',
        'observaciones',
        'registrado_por',
    ];

    protected $casts = [
        'fecha_vencimiento' => 'date',
        'fecha_pago' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    // Adelanto principal
    public function adelanto()
    {
        return $this->belongsTo(Adelanto::class);
    }

    // Usuario RRHH/Finanzas que registró pago
    public function registradoPor()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }
}