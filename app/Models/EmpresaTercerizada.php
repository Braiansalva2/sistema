<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaTercerizada extends Model
{
    use HasFactory;
protected $table = 'empresas_tercerizadas';
    protected $fillable = [
        'nombre',
        'cuit',
        'telefono',
        'correo',
        'responsable'
    ];

    public function unidades()
    {
        return $this->hasMany(Unidad::class, 'empresa_tercerizada_id');
    }
}
