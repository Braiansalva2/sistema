<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Models\Roster;
use App\Models\Empleado;
use Illuminate\Http\Request;

class RosterController extends Controller
{
   public function index()
{
    /*
    |--------------------------------------------------------------------------
    | ROSTERS (GRUPOS)
    |--------------------------------------------------------------------------
    | Cargamos cada roster con sus empleados asociados.
    */
    $grupos = Roster::with('empleados')
        ->orderByDesc('fecha_subida')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | EMPLEADOS DISPONIBLES PARA ASIGNAR
    |--------------------------------------------------------------------------
    | Mostrar empleados:
    | - Activos
    | - tipo_empleado = 'roster'
    |   o
    | - cumple_roster = 1
    */
    $empleados = Empleado::where('estado', 'Activo')
        ->where(function ($query) {
            $query->where('tipo_empleado', 'roster')
                  ->orWhere('cumple_roster', 1);
        })
        ->orderBy('apellido')
        ->orderBy('nombre')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | VISTA
    |--------------------------------------------------------------------------
    */
    return view('rrhh.rosters.index', compact(
        'grupos',
        'empleados'
    ));
}
   public function store(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:255',
        'empleado_ids' => 'required|array|min:1',
        'empleado_ids.*' => 'exists:empleados,id',
        'modalidad_trabajo' => 'required|integer|min:1|max:60',
        'modalidad_descanso' => 'required|integer|min:1|max:60',
        'fecha_subida' => 'required|date',
        'fecha_bajada' => 'required|date|after_or_equal:fecha_subida',
        'estado' => 'required|in:Activo,Inactivo',
        'observaciones' => 'nullable|string',
    ]);

    $roster = Roster::create([
        'nombre' => $request->nombre,
        'modalidad_trabajo' => $request->modalidad_trabajo,
        'modalidad_descanso' => $request->modalidad_descanso,
        'fecha_subida' => $request->fecha_subida,
        'fecha_bajada' => $request->fecha_bajada,
        'estado' => $request->estado,
        'observaciones' => $request->observaciones,
    ]);

    $roster->empleados()->sync($request->empleado_ids);

    return redirect()
        ->route('rrhh.rosters.index')
        ->with('success', 'Grupo de roster creado correctamente.');
}
public function update(Request $request, Roster $roster)
{
    $request->validate([
        'nombre' => 'required|string|max:255',
        'empleado_ids' => 'required|array|min:1',
        'empleado_ids.*' => 'exists:empleados,id',
        'modalidad_trabajo' => 'required|integer|min:1|max:60',
        'modalidad_descanso' => 'required|integer|min:1|max:60',
        'fecha_subida' => 'required|date',
        'fecha_bajada' => 'required|date|after_or_equal:fecha_subida',
        'estado' => 'required|in:Activo,Inactivo',
        'observaciones' => 'nullable|string',
    ]);

    $roster->update([
        'nombre' => $request->nombre,
        'modalidad_trabajo' => $request->modalidad_trabajo,
        'modalidad_descanso' => $request->modalidad_descanso,
        'fecha_subida' => $request->fecha_subida,
        'fecha_bajada' => $request->fecha_bajada,
        'estado' => $request->estado,
        'observaciones' => $request->observaciones,
    ]);

    $roster->empleados()->sync($request->empleado_ids);

    return redirect()
        ->route('rrhh.rosters.index')
        ->with('success', 'Grupo de roster actualizado correctamente.');
}

    public function destroy(Roster $roster)
    {
        $roster->update([
            'estado' => 'Inactivo'
        ]);

        return redirect()
            ->route('rrhh.rosters.index')
            ->with('success', 'Roster dado de baja correctamente.');
    }
}