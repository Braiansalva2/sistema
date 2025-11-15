<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Models\Banco;
use Illuminate\Http\Request;

class BancoController extends Controller
{
    public function index()
    {
        return response()->json(Banco::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_banco' => 'required|string|max:100',
            'codigo' => 'nullable|string|max:20',
        ]);

        $banco = Banco::create($validated);

        return response()->json($banco);
    }
}
