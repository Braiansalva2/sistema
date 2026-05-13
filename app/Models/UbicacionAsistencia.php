<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UbicacionAsistencia extends Model
{
    use HasFactory;

    protected $table = 'ubicaciones_asistencia';

    protected $fillable = [
       
        'sucursal_id',
        'nombre',
        'latitud',
        'longitud',
        'radio_metros',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];


    public function sucursal()
{
    return $this->belongsTo(Sucursal::class);
}
}