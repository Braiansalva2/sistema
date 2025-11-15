<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Models\CondicionLaboral;
use Illuminate\Http\Request;

class CondicionLaboralController extends Controller
{
    public function index()
    {
        return response()->json(CondicionLaboral::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_condicion' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
        ]);

        $condicion = CondicionLaboral::create($validated);

        return response()->json($condicion);
    }
}
