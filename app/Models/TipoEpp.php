<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEpp extends Model
{
    use HasFactory;

    protected $table = 'tipos_epp';

    protected $fillable = [
        'nombre',
    ];

    public function entregas()
    {
        return $this->hasMany(EmpleadoEpp::class, 'tipo_epp_id');
    }
}