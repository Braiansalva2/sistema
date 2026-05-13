<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModeloVehiculo extends Model
{
    use HasFactory;

    protected $table = 'modelos';

    protected $fillable = [
        'marca_id',
        'nombre',
    ];

    // Un modelo pertenece a una marca
    public function marca()
    {
        return $this->belongsTo(Marca::class, 'marca_id');
    }

    // Un modelo tiene muchas unidades
    public function unidades()
    {
        return $this->hasMany(Unidad::class, 'modelo_id');
    }
}
