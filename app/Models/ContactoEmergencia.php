<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactoEmergencia extends Model
{
    use HasFactory;

    protected $table = 'contactos_emergencia';

    protected $fillable = [
        'empleado_id',
        'nombre_contacto',
        'parentesco',
        'telefono',
        'domicilio',
        'es_principal',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
