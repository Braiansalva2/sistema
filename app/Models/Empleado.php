<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellido',
        'dni',
        'cuil',
        'fecha_nacimiento',
        'direccion',
        'telefono',
        'email',
        'fecha_ingreso',
        'estado',
        'banco_id',
        'cbu',
        'obra_social_id',
        'fecha_cambio_obra_social',
        'constancia_cambio_path',
        'art_id',
        'rol_puesto_id',
        'condicion_laboral_id',
        'contrato_id',
        'user_id'
    ];

    // Relaciones principales
    public function banco() { return $this->belongsTo(Banco::class); }
    public function obraSocial() { return $this->belongsTo(ObraSocial::class); }
    public function art() { return $this->belongsTo(Art::class); }
    public function rolPuesto() { return $this->belongsTo(RolPuesto::class); }
    public function condicionLaboral() { return $this->belongsTo(CondicionLaboral::class); }
    public function contrato() { return $this->belongsTo(Contrato::class); }
    public function usuario() { return $this->belongsTo(User::class, 'user_id'); }

    // Relaciones hijas
    public function legajos() { return $this->hasMany(LegajoEmpleado::class); }
    public function vacaciones() { return $this->hasMany(Vacacion::class); }
    public function sanciones() { return $this->hasMany(Sancion::class); }
    public function descansos() { return $this->hasMany(Descanso::class); }
    public function contactosEmergencia() { return $this->hasMany(ContactoEmergencia::class); }
}
