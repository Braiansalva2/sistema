<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referente extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'nombre',
        'apellido',
        'cargo',
        'telefono',
        'correo',
        'es_principal',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}

