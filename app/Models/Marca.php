<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $table = 'marcas';

    protected $fillable = [
        'nombre',
    ];

    // Una marca tiene muchos modelos
    public function modelos()
    {
        return $this->hasMany(ModeloVehiculo::class, 'marca_id');
    }

    // Una marca tiene muchas unidades
    public function unidades()
    {
        return $this->hasMany(Unidad::class, 'marca_id');
    }
}
