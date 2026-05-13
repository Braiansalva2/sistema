<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaHistorialFiscal extends Model
{
    use HasFactory;

    protected $table = 'empresa_historial_fiscal';

    protected $fillable = [
        'empresa_id',
        'razon_social_anterior',
        'razon_social_nueva',
        'condicion_iva',
        'estado_fiscal',
        'tipo_persona',
        'actividad_principal',
        'fecha_cambio',
        'origen',
    ];

    protected $casts = [
        'fecha_cambio' => 'datetime',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
