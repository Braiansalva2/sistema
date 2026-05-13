<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sueldo;
use App\Models\MovimientoEmpleado;
use App\Models\Capacitacion;
use App\Models\EmpleadoCapacitacion;
use App\Models\EmpleadoEpp;

class Empleado extends Model
{
    use HasFactory;

   protected $fillable = [
    'sucursal_id',
    'nombre',
    'apellido',
    'dni',
    'cuil',
    'fecha_nacimiento',
    'lugar_nacimiento',   
    'nacionalidad',       
    'estado_civil', 
    'sexo',      
    'direccion',
    'telefono',
    'email',
    'fecha_ingreso',
    'estado',
    'tipo_empleado',
    'banco_id',
    'cbu',
    'numero_cuenta',
    'obra_social_id',
    'fecha_cambio_obra_social',
    'constancia_cambio_path',
    'art_id',
    'rol_puesto_id',
    'condicion_laboral_id',
    'contrato_id',
    'foto_perfil',
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
    public function sucursal()
{
    return $this->belongsTo(Sucursal::class);
}


public function sueldos()
{
    return $this->hasMany(Sueldo::class);
}

public function sueldoActual()
{
    return $this->hasOne(Sueldo::class)->where('activo', true);
}

public function movimientos()
{
    return $this->hasMany(MovimientoEmpleado::class);
}

public function capacitaciones()
{
    return $this->belongsToMany(Capacitacion::class, 'empleado_capacitacion')
        ->withPivot([
            'fecha_realizada',
            'fecha_vencimiento',
            'dictado_por',
            'constancia_path',
            'observaciones'
        ])
        ->withTimestamps();
}

public function empleadoCapacitaciones()
{
    return $this->hasMany(EmpleadoCapacitacion::class);
}

public function epps()
{
    return $this->hasMany(EmpleadoEpp::class);
}

    protected $casts = [
    'fecha_nacimiento' => 'date',
    'fecha_ingreso' => 'date',
    'fecha_cambio_obra_social' => 'date',
];
public function familiares()
{
    return $this->hasMany(GrupoFamiliar::class);
}

public function grupoFamiliar()
{
    return $this->hasMany(\App\Models\GrupoFamiliar::class);
}

// grupod para el perfil
public function getTelefonoOcultoAttribute()
{
    if (!$this->telefono) {
        return 'No registrado';
    }

    $telefono = preg_replace('/\s+/', '', $this->telefono);

    if (strlen($telefono) <= 4) {
        return str_repeat('*', strlen($telefono));
    }

    return substr($telefono, 0, -3) . '***';
}

public function getEmailOcultoAttribute()
{
    if (!$this->email) {
        return 'No registrado';
    }

    if (!str_contains($this->email, '@')) {
        return 'Email inválido';
    }

    [$nombre, $dominio] = explode('@', $this->email, 2);

    $inicioNombre = substr($nombre, 0, 3);
    $inicioDominio = substr($dominio, 0, 1);

    return $inicioNombre . '***@' . $inicioDominio . '***';
}

public function getDireccionOcultaAttribute()
{
    if (!$this->direccion) {
        return 'No registrada';
    }

    if (strlen($this->direccion) <= 10) {
        return substr($this->direccion, 0, 4) . '...';
    }

    return substr($this->direccion, 0, 10) . '...';
}


public function adelantos()
{
    return $this->hasMany(Adelanto::class);
}

public function talles()
{
    return $this->hasMany(EmpleadoTalle::class);
}

public function movimientosAsistencia()
{
    return $this->hasMany(MovimientoAsistencia::class);
}

public function consentimientosAsistencia()
{
    return $this->hasMany(ConsentimientoAsistencia::class);
}

public function eventosOperativos()
{
    return $this->hasMany(EventoOperativo::class);
}
public function rosters()
{
    return $this->hasMany(Roster::class);
}

public function getFechaNacimientoOcultaAttribute()
{
    if (!$this->fecha_nacimiento) {
        return 'No registrada';
    }

    return $this->fecha_nacimiento->format('d') . '/**/****';
}

}
