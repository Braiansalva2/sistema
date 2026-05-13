<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegajoTecnicoUnidad extends Model
{
    use HasFactory;

    protected $table = 'legajo_tecnico_unidades';

    protected $fillable = [
        'unidad_id',
        'seguro_vencimiento',
        'vtv_vencimiento',
        'tecnica_vencimiento',
        'poliza_archivo',
        'vtv_archivo',
        'tecnica_archivo',
        'otros_documentos',
    ];

    // Una ficha técnica pertenece a una unidad
    public function unidad()
    {
        return $this->belongsTo(Unidad::class, 'unidad_id');
    }
}
        