<?php

namespace App\Http\Controllers\Trafico;

use App\Http\Controllers\Controller;
use App\Models\ModeloVehiculo;
use Illuminate\Http\Request;

class ModeloController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'nombre' => 'required',
        'marca_id' => 'required|exists:marcas,id',
    ]);

    $modelo = ModeloVehiculo::create([
        'nombre' => $request->nombre,
        'marca_id' => $request->marca_id
    ]);

    return response()->json([
        'success' => true,
        'modelo' => $modelo
    ]);
}

}
