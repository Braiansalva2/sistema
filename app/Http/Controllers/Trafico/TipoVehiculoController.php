<?php

namespace App\Http\Controllers\Trafico;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TipoVehiculo;

class TipoVehiculoController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'nombre' => 'required|unique:tipos_vehiculos,nombre'
    ]);

    $tipo = TipoVehiculo::create([
        'nombre' => $request->nombre
    ]);

    return response()->json([
        'success' => true,
        'tipo' => $tipo
    ]);
}

}
