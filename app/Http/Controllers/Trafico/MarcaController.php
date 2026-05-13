<?php

namespace App\Http\Controllers\Trafico;

use App\Http\Controllers\Controller;
use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
   public function store(Request $request)
{
    $request->validate([
        'nombre' => 'required|unique:marcas,nombre'
    ]);

    $marca = Marca::create([
        'nombre' => $request->nombre
    ]);

    return response()->json([
        'success' => true,
        'marca' => $marca
    ]);
}

}
