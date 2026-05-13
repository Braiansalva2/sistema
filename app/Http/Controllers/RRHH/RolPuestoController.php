<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Models\RolPuesto;
use Illuminate\Http\Request;

class RolPuestoController extends Controller
{
    public function index()
    {
        return response()->json(RolPuesto::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_puesto' => 'required|string|max:100',
            'descripcion' => 'nullable|string'
        ]);

        $rol = RolPuesto::create($validated);

        return response()->json($rol);
    }
}
