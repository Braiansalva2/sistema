<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TipoPrendaTalle;

class TipoPrendaTalleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

     public function store(Request $request)
    {
        TipoPrendaTalle::create([
            'tipo_prenda_id' => $request->tipo_prenda_id,
            'nombre' => $request->nombre,
            'estado' => true
        ]);

        return back()->with('success', 'Talle agregado');
    }

    public function update(Request $request, $id)
    {
        $talle = TipoPrendaTalle::findOrFail($id);

        $talle->update([
            'nombre' => $request->nombre,
            'estado' => $request->estado
        ]);

        return back()->with('success', 'Talle actualizado');
    }
}
