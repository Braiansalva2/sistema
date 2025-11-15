<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Models\Contrato;
use Illuminate\Http\Request;

class ContratoController extends Controller
{
    public function index()
    {
        return response()->json(Contrato::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo_contrato' => 'required|string|max:100',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date',
            'estado' => 'nullable|string|max:50',
        ]);

        $contrato = Contrato::create($validated);

        return response()->json($contrato);
    }
}
