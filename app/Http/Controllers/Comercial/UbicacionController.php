<?php

namespace App\Http\Controllers\Comercial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ubicacion;

class UbicacionController extends Controller
{
    public function index()
    {
        $ubicaciones = Ubicacion::latest()->get();
        return view('comercial.ubicaciones.index', compact('ubicaciones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        Ubicacion::create($request->all());

        return redirect()->back()->with('success', 'Ubicación creada');
    }




    public function update(Request $request, $id)
    {
        $ubicacion = Ubicacion::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $ubicacion->update($request->all());

        return redirect()->back()->with('success', 'Ubicación actualizada');
    }

    public function destroy($id)
    {
        Ubicacion::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Ubicación eliminada');
    }


    
    public function create() {}
    public function edit($id) {}
    public function show($id) {}

    public function storeAjax(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:255',
    ]);

    $ubicacion = Ubicacion::create([
        'nombre' => $request->nombre,
        'tipo' => $request->tipo,
        'descripcion' => $request->descripcion,
        'estado' => 'activo'
    ]);

    return response()->json($ubicacion);
}
}