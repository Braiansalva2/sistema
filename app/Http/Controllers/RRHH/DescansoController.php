<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use App\Models\Descanso;
use Illuminate\Http\Request;

class DescansoController extends Controller
{
    public function index($empleado_id)
    {
        $empleado = Empleado::findOrFail($empleado_id);
        $descansos = Descanso::where('empleado_id', $empleado_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('rrhh.descansos.index', compact('empleado', 'descansos'));
    }

    public function create($empleado_id)
    {
        $empleado = Empleado::findOrFail($empleado_id);
        return view('rrhh.descansos.create', compact('empleado'));
    }

    public function store(Request $request, $empleado_id)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'motivo' => 'nullable|string|max:255',
            'tipo' => 'required|in:roster,especial',
            'estado' => 'required|in:programado,en_curso,finalizado'
        ]);

        Descanso::create([
            'empleado_id' => $empleado_id,
            ...$request->only(['fecha_inicio','fecha_fin','motivo','tipo','estado'])
        ]);

        return redirect()
            ->route('rrhh.descansos.index', $empleado_id)
            ->with('success', 'Descanso registrado correctamente.');
    }

    public function edit($empleado_id, $id)
    {
        $empleado = Empleado::findOrFail($empleado_id);
        $descanso = Descanso::findOrFail($id);

        return view('rrhh.descansos.edit', compact('empleado', 'descanso'));
    }

    public function update(Request $request, $empleado_id, $id)
    {
        $descanso = Descanso::findOrFail($id);

        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'motivo' => 'nullable|string|max:255',
            'tipo' => 'required|in:roster,especial',
            'estado' => 'required|in:programado,en_curso,finalizado'
        ]);

        $descanso->update($request->all());

        return redirect()
            ->route('rrhh.descansos.index', $empleado_id)
            ->with('success', 'Descanso actualizado correctamente.');
    }

    public function destroy($empleado_id, $id)
    {
        Descanso::findOrFail($id)->delete();

        return back()->with('success', 'Descanso eliminado correctamente.');
    }
}
