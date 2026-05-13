<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpleadoEpp extends Model
{
    use HasFactory;

    protected $table = 'empleado_epp';

    protected $fillable = [
        'empleado_id',
        'tipo_epp_id',
        'talle_id',
        'fecha_entrega',
        'fecha_vencimiento',
        'cantidad',
    ];

    protected $casts = [
        'fecha_entrega' => 'date',
        'fecha_vencimiento' => 'date',
        'cantidad' => 'integer',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function tipoEpp()
    {
        return $this->belongsTo(TipoEpp::class, 'tipo_epp_id');
    }

    public function talle()
    {
        return $this->belongsTo(Talle::class);
    }
}