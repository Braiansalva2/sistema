<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Models\Vacacion;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VacacionController extends Controller
{
    public function index($empleado_id)
    {
        $empleado = Empleado::findOrFail($empleado_id);
        $vacaciones = Vacacion::where('empleado_id', $empleado_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('rrhh.vacaciones.index', compact('empleado', 'vacaciones'));
    }

    public function create($empleado_id)
    {
        $empleado = Empleado::findOrFail($empleado_id);
        return view('rrhh.vacaciones.create', compact('empleado'));
    }

  public function store(Request $request, $empleado_id)
{
    $request->validate([
        'periodo' => 'required|integer',
        'dias_correspondientes' => 'required|integer|min:1',
        'dias_tomados' => 'required|integer|min:0',
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        'observaciones' => 'nullable|string|max:500',
    ]);

    Vacacion::create([
        'empleado_id' => $empleado_id,
        'periodo' => $request->periodo,
        'dias_correspondientes' => $request->dias_correspondientes,
        'dias_tomados' => $request->dias_tomados,
        'fecha_inicio' => $request->fecha_inicio,
        'fecha_fin' => $request->fecha_fin,
        'observaciones' => $request->observaciones,
    ]);

    return redirect()->route('rrhh.vacaciones.index', $empleado_id)
        ->with('success', 'Vacaciones registradas correctamente');
}


   public function edit($id)
{
    $vacacion = Vacacion::findOrFail($id);
    $empleado = $vacacion->empleado;

    return view('rrhh.vacaciones.edit', compact('vacacion', 'empleado'));
}


  public function update(Request $request, $id)
{
    $vacacion = Vacacion::findOrFail($id);

    $request->validate([
        'periodo' => 'required|integer',
        'dias_correspondientes' => 'required|integer|min:1',
        'dias_tomados' => 'required|integer|min:0',
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        'observaciones' => 'nullable|string|max:500',
    ]);

    $vacacion->update($request->all());

    return redirect()->route('rrhh.vacaciones.index', $vacacion->empleado_id)
        ->with('success', 'Vacaciones actualizadas correctamente');
}


public function aprobar(Vacacion $vacacion)
{
    if (!auth()->user()->can('aprobar vacaciones')) {
        abort(403, 'No autorizado');
    }

    $vacacion->update([
        'estado' => 'aprobadas',
        'aprobado_por' => auth()->id(),
        'fecha_aprobacion' => now(),
    ]);

    return back()->with('success', 'Vacaciones aprobadas correctamente');
}

public function rechazar(Vacacion $vacacion)
{
    if (!auth()->user()->can('rechazar vacaciones')) {
        abort(403, 'No autorizado');
    }

    $vacacion->update([
        'estado' => 'rechazadas',
        'aprobado_por' => auth()->id(),
        'fecha_aprobacion' => now(),
    ]);

    return back()->with('success', 'Vacaciones rechazadas correctamente');
}



    public function destroy($id)
    {
        $vacacion = Vacacion::findOrFail($id);
        $empleado_id = $vacacion->empleado_id;
        $vacacion->delete();

        return redirect()->route('rrhh.vacaciones.index', $empleado_id)
            ->with('success', 'Registro eliminado');
    }
}
