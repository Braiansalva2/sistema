<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsentimientoOperativo extends Model
{
    use HasFactory;
    
    protected $table = 'consentimientos_operativos';

    protected $fillable = [

    'empleado_id',
    'texto_aceptado',
    'version',
    'aceptado',
    'fecha_aceptacion',
    'ip',
    'device',

];

}
