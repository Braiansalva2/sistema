<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    use HasFactory;

    protected $fillable = [
    'nombre_banco', 
    'codigo',
    'numero_cuenta',
];

    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }
}
