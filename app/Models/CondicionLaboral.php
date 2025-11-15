<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CondicionLaboral extends Model
{
    use HasFactory;
     protected $table = 'condiciones_laborales';
    protected $fillable = ['nombre_condicion', 'descripcion'];

    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }
}
