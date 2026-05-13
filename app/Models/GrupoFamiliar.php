<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class GrupoFamiliar extends Model
{
    use HasFactory;

    protected $table = 'grupo_familiar';

    protected $fillable = [
        'empleado_id',
        'nombre',
        'apellido',
        'parentesco',
        'a_cargo',
        'fecha_nacimiento',
        'dni'
    ];

    // Relación con empleado
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }


    public function getNombreOcultoAttribute()
{
    if (!$this->nombre) {
        return '';
    }

    return substr($this->nombre, 0, 2) . '***';
}

public function getApellidoOcultoAttribute()
{
    if (!$this->apellido) {
        return '';
    }

    return substr($this->apellido, 0, 2) . '***';
}
}
