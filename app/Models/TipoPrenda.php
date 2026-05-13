<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPrenda extends Model
{
    protected $table = 'tipos_prenda';

    protected $fillable = ['nombre', 'estado'];

    public function talles()
    {
        return $this->hasMany(TipoPrendaTalle::class);
    }
}