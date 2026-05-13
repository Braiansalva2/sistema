<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

   protected $fillable = [
    'razon_social',
    'cuit',
    'tipo_persona',
    'condicion_iva',
    'estado_fiscal',
    'actividad_principal',
    'observaciones',
    'logo',
    'estado'
];

    public function referentes()
    {
        return $this->hasMany(Referente::class);
    }

    public function documentos()
    {
        return $this->hasMany(EmpresaDocumento::class);
    }
    public function historialFiscal()
{
    return $this->hasMany(EmpresaHistorialFiscal::class);
}

}
