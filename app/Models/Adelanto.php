<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adelanto extends Model
{
    use HasFactory;

    protected $fillable = [
        'empleado_id',
        'monto_total',
        'cuotas_total',
        'estado',
        'aprobado_por',
        'fecha_aprobacion',
        'fecha_pago',
        'metodo_pago',
        'comprobante_pago',
        'pagado_por',
        'fecha_solicitud',
        'motivo',
        'origen',
    ];

    protected $casts = [
        'monto_total' => 'decimal:2',
        'fecha_aprobacion' => 'datetime',
        'fecha_pago' => 'date',
    ];

    //RELACIONES

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function aprobadoPor()
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }

    public function pagadoPor()
    {
        return $this->belongsTo(User::class, 'pagado_por');
    }

    // Relación con cuotas (movimientos)
    public function movimientos()
    {
        return $this->hasMany(MovimientoEmpleado::class, 'adelanto_id');
    }

    public function cuotas()
{
    return $this->hasMany(AdelantoCuota::class);
}
}