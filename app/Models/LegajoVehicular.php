<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegajoVehicular extends Model
{
    use HasFactory;
   protected $table = 'legajos_vehiculares';
   
    protected $fillable = [
        'unidad_id',
        'tipo_documento',
        'descripcion',
        'fecha_emision',
        'fecha_vencimiento',
        'archivo',
        'estado'
    ];

    public function unidad()
    {
        return $this->belongsTo(Unidad::class);
    }
}
