<?php

namespace App\Http\Controllers\Trafico;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unidad;
use App\Models\LegajoVehicular;
use Illuminate\Support\Facades\Storage;

class LegajoVehicularController extends Controller
{
   public function index($id)
{
    $unidad = Unidad::with(['marca', 'modelo', 'tipoVehiculo'])->findOrFail($id);

    // PAGINACIÓN (6 documentos por página)
    $documentos = LegajoVehicular::where('unidad_id', $id)
                    ->orderBy('created_at', 'desc')
                    ->paginate(6); // 👈 PAGINACIÓN REAL

    return view('trafico.legajos.index', compact('unidad', 'documentos'));
}


    public function store(Request $request, $unidad_id)
    {
        $request->validate([
            'tipo_documento'   => 'required|string|max:255',
            'descripcion'      => 'nullable|string',
            'fecha_emision'    => 'nullable|date',
            'fecha_vencimiento'=> 'nullable|date',
            'archivo'          => 'required|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        // Guardar archivo en storage
        $path = $request->file('archivo')->store('legajos_vehiculares', 'public');

        LegajoVehicular::create([
            'unidad_id'        => $unidad_id,
            'tipo_documento'   => $request->tipo_documento,
            'descripcion'      => $request->descripcion,
            'fecha_emision'    => $request->fecha_emision,
            'fecha_vencimiento'=> $request->fecha_vencimiento,
            'archivo'          => $path,  // <--- NOMBRE CORRECTO SEGÚN MIGRACIÓN
            'estado'           => 'vigente',
        ]);

        return back()->with('success', 'Documento cargado correctamente.');
    }
public function update(Request $request, $id)
{
    $doc = LegajoVehicular::findOrFail($id);

    $request->validate([
        'tipo_documento'   => 'required|string|max:255',
        'descripcion'      => 'nullable|string',
        'fecha_emision'    => 'nullable|date',
        'fecha_vencimiento'=> 'nullable|date',
        'archivo'          => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
    ]);

    // Si se sube un archivo nuevo
    if ($request->hasFile('archivo')) {

        // Borrar archivo anterior
        if ($doc->archivo && Storage::disk('public')->exists($doc->archivo)) {
            Storage::disk('public')->delete($doc->archivo);
        }

        // Guardar nuevo archivo
        $doc->archivo = $request->file('archivo')->store('legajos_vehiculares', 'public');
    }

    // Actualizar datos
    $doc->update([
        'tipo_documento'   => $request->tipo_documento,
        'descripcion'      => $request->descripcion,
        'fecha_emision'    => $request->fecha_emision,
        'fecha_vencimiento'=> $request->fecha_vencimiento,
    ]);

    return back()->with('success', 'Documento actualizado correctamente.');
}

    public function destroy($id)
    {
        $doc = LegajoVehicular::findOrFail($id);

        // Eliminar archivo físico
        if ($doc->archivo && Storage::disk('public')->exists($doc->archivo)) {
            Storage::disk('public')->delete($doc->archivo);
        }

        $doc->delete();

        return back()->with('success', 'Documento eliminado correctamente.');
    }
}
