<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use App\Models\LegajoEmpleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class LegajoEmpleadoController extends Controller
{
    /** LISTADO DE LEGAJOS POR EMPLEADO */
    public function index($empleado_id)
    {
        $empleado = Empleado::findOrFail($empleado_id);
        $legajos = LegajoEmpleado::where('empleado_id', $empleado_id)->get();

        return view('rrhh.legajos.index', compact('empleado', 'legajos'));
    }

    /** FORMULARIO DE CREACIÓN */
    public function create($empleado_id)
    {
        $empleado = Empleado::findOrFail($empleado_id);
        return view('rrhh.legajos.create', compact('empleado'));
    }

    /** GUARDAR LEGAJO */
    public function store(Request $request, $empleado_id)
    {
        $request->validate([
            'nombre_archivo' => 'required|string|max:150',
            'archivo'        => 'required|file|mimes:pdf,jpg,jpeg,png,webp',
            'fecha_inicio'   => 'nullable|date',
            'fecha_fin'      => 'nullable|date',
            'estado'         => 'nullable|in:vigente,por_vencer,vencido',
        ]);

        // Guardar archivo
        $path = $request->file('archivo')->store('legajos', 'public');

        LegajoEmpleado::create([
            'empleado_id'   => $empleado_id,
            'nombre_archivo'=> $request->nombre_archivo,
            'descripcion'   => $request->descripcion,
            'archivo_path'  => $path,
            'fecha_inicio'  => $request->fecha_inicio,
            'fecha_fin'     => $request->fecha_fin,
            'estado'        => $request->estado ?? 'vigente', // default correcto
        ]);

  return redirect()
    ->route('rrhh.empleados.legajos.index', $empleado_id)
    ->with('success', 'Legajo cargado correctamente.');
    }

    /** FORMULARIO EDITAR */
    public function edit($empleado_id, $id)
    {
        $empleado = Empleado::findOrFail($empleado_id);
        $legajo = LegajoEmpleado::findOrFail($id);

        return view('rrhh.legajos.edit', compact('empleado', 'legajo'));
    }

    /** ACTUALIZAR */
    public function update(Request $request, $empleado_id, $id)
    {
        $request->validate([
            'nombre_archivo' => 'required|string|max:150',
            'archivo'        => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp',
            'fecha_inicio'   => 'nullable|date',
            'fecha_fin'      => 'nullable|date',
            'estado'         => 'required|in:vigente,por_vencer,vencido',
        ]);

        $legajo = LegajoEmpleado::findOrFail($id);

        // Si sube archivo nuevo, eliminar anterior
        if ($request->hasFile('archivo')) {
            Storage::disk('public')->delete($legajo->archivo_path);
            $legajo->archivo_path = $request->file('archivo')->store('legajos', 'public');
        }

        $legajo->update([
            'nombre_archivo' => $request->nombre_archivo,
            'descripcion'    => $request->descripcion,
            'fecha_inicio'   => $request->fecha_inicio,
            'fecha_fin'      => $request->fecha_fin,
            'estado'         => $request->estado,
        ]);

      return redirect()
    ->route('rrhh.empleados.legajos.index', $empleado_id)
    ->with('success', 'Legajo actualizado correctamente.');

    }

    /** ELIMINAR */
    public function destroy($empleado_id, $id)
    {
        $legajo = LegajoEmpleado::findOrFail($id);

        Storage::disk('public')->delete($legajo->archivo_path);
        $legajo->delete();
        
return redirect()
    ->route('rrhh.empleados.legajos.index', $empleado_id)
    ->with('success', 'Legajo eliminado.');
    }


  
}
