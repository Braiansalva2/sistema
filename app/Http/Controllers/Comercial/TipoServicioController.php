<?php

namespace App\Http\Controllers\Comercial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TipoServicio;
class TipoServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
{
    $tipos = TipoServicio::latest()->get();

    return view('comercial.tipos_servicio.index', compact('tipos'));
}


    public function storeAjax(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:255',
    ]);

    $tipo = TipoServicio::create([
        'nombre' => $request->nombre,
        'descripcion' => $request->descripcion,
        'estado' => 'activo'
    ]);

    return response()->json($tipo);
}


public function store(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:255',
    ]);

    TipoServicio::create([
        'nombre' => $request->nombre,
        'descripcion' => $request->descripcion,
        'estado' => 'activo'
    ]);

    return redirect()->back()->with('success', 'Servicio creado');
}
     // ACTUALIZAR (MODAL EDIT)
    public function update(Request $request, $id)
    {
        $tipo = TipoServicio::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $tipo->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->back()->with('success', 'Servicio actualizado');
    }

    // ELIMINAR (MODAL DELETE)
    public function destroy($id)
    {
        $tipo = TipoServicio::findOrFail($id);
        $tipo->delete();

        return redirect()->back()->with('success', 'Servicio eliminado');
    }
}
