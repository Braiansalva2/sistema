<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use App\Models\Sancion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SancionController extends Controller
{
    public function index($empleadoId)
    {
        $empleado = Empleado::findOrFail($empleadoId);
        $sanciones = Sancion::where('empleado_id', $empleadoId)->latest()->get();

        return view('rrhh.sanciones.index', compact('empleado', 'sanciones'));
    }

    public function create($empleadoId)
    {
        $empleado = Empleado::findOrFail($empleadoId);
        return view('rrhh.sanciones.create', compact('empleado'));
    }

    public function store(Request $request, $empleadoId)
    {
        $validated = $request->validate([
            'tipo_sancion' => 'required|string|max:100',
            'fecha_sancion' => 'required|date',
            'motivo' => 'required|string',
            'documento' => 'nullable|mimes:pdf|max:2048',
        ]);

        $validated['empleado_id'] = $empleadoId;

        if ($request->hasFile('documento')) {
            $validated['documento_path'] = $request->file('documento')
                ->store('sanciones', 'public');
        }

        Sancion::create($validated);

        return redirect()
            ->route('rrhh.sanciones.index', $empleadoId)
            ->with('success', 'Sanción registrada correctamente.');
    }

    public function edit(Sancion $sancion)
    {
        return view('rrhh.sanciones.edit', compact('sancion'));
    }

    public function update(Request $request, Sancion $sancion)
    {
        $validated = $request->validate([
            'tipo_sancion' => 'required|string|max:100',
            'fecha_sancion' => 'required|date',
            'motivo' => 'required|string',
            'estado' => 'required|in:vigente,cumplida',
            'documento' => 'nullable|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('documento')) {
            if ($sancion->documento_path && Storage::disk('public')->exists($sancion->documento_path)) {
                Storage::disk('public')->delete($sancion->documento_path);
            }

            $validated['documento_path'] = $request->file('documento')
                ->store('sanciones', 'public');
        }

        $sancion->update($validated);

        return redirect()
            ->route('rrhh.sanciones.index', $sancion->empleado_id)
            ->with('success', 'Sanción actualizada correctamente.');
    }

    public function destroy(Sancion $sancion)
    {
        if ($sancion->documento_path && Storage::disk('public')->exists($sancion->documento_path)) {
            Storage::disk('public')->delete($sancion->documento_path);
        }

        $sancion->delete();

        return back()->with('success', 'Sanción eliminada correctamente.');
    }
}
