<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaDocumento extends Model
{
    use HasFactory;

    protected $table = 'empresa_documentos';

    protected $fillable = [
        'empresa_id',
        'tipo_documento',
        'nombre_documento',
        'archivo',
        'fecha_emision',
        'fecha_vencimiento',
        'estado',
        'observaciones',
        'created_by',
    ];

    protected $dates = [
        'fecha_emision',
        'fecha_vencimiento',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
