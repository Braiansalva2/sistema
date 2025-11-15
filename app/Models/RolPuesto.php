<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolPuesto extends Model
{
    use HasFactory;
   protected $table = 'roles_puestos';
    protected $fillable = ['nombre_puesto', 'descripcion'];

    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }
}
