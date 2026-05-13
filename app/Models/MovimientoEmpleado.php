<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoEmpleado extends Model
{
    use HasFactory;

    protected $table = 'movimientos_empleado';

    protected $fillable = [
        'empleado_id',
        'tipo',
        'monto',
        'cantidad',
        'fecha',
        'descripcion',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'cantidad' => 'integer',
        'fecha' => 'date',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
    public function adelanto()
{
    return $this->belongsTo(Adelanto::class, 'adelanto_id');
}

}