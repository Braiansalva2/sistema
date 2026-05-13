<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MovimientoEmpleado;

class MovimientoEmpleadoController extends Controller
{
    public function store(Request $request, $empleado_id)
    {
        $request->validate([
            'tipo' => 'required',
            'monto' => 'required|numeric',
            'fecha' => 'required|date',
        ]);

        MovimientoEmpleado::create([
            'empleado_id' => $empleado_id,
            'tipo' => $request->tipo,
            'monto' => $request->monto,
            'cantidad' => $request->cantidad,
            'fecha' => $request->fecha,
            'descripcion' => $request->descripcion,
        ]);

        return back()->with('success', 'Movimiento registrado');
    }

    public function destroy($empleado_id, $id)
    {
        MovimientoEmpleado::findOrFail($id)->delete();

        return back()->with('success', 'Movimiento eliminado');
    }
}