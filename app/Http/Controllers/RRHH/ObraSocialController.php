<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Models\ObraSocial;
use Illuminate\Http\Request;

class ObraSocialController extends Controller
{
    public function index()
    {
        return response()->json(ObraSocial::all());
    }

  public function store(Request $request)
{
    $validated = $request->validate([
        'nombre' => 'required|string|max:100',
        'codigo' => 'nullable|string|max:50',
        'vigencia' => 'nullable|date',
        'estado' => 'required|string'
    ]);

    $obra = ObraSocial::create($validated);

    return response()->json($obra);
}



public function show($id)
{
    return ObraSocial::findOrFail($id);
}

public function update(Request $request, $id)
{
    $obra = ObraSocial::findOrFail($id);

    $obra->update($request->all());

    return response()->json($obra);
}
public function destroy($id)
{
    $obra = ObraSocial::findOrFail($id);
    $obra->delete();

    return response()->json(['message' => 'Eliminado']);
}

}
