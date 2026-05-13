<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sueldo;
use App\Models\Empleado;
use App\Models\MovimientoEmpleado;

class SueldoController extends Controller
{
 public function index($empleado)
{
    $empleado = Empleado::with([
        'adelantos' => function ($query) {
            $query->latest()->take(2);
        },
        'adelantos.movimientos'
    ])->findOrFail($empleado);

    // sueldo activo
    $sueldo = Sueldo::where('empleado_id', $empleado->id)
                ->where('activo', true)
                ->first();

    // movimientos
    $movimientos = MovimientoEmpleado::where('empleado_id', $empleado->id)
                    ->orderBy('fecha', 'desc')
                    ->get();

    // cálculos
    $ingresos = $movimientos->whereIn('tipo', ['hora_extra','viatico'])->sum('monto');
    $descuentos = $movimientos->whereIn('tipo', ['anticipo','descuento'])->sum('monto');

    $sueldoBase = $sueldo->sueldo_base ?? 0;

    $neto = $sueldoBase + $ingresos - $descuentos;

    return view('rrhh.empleados.sueldos.index', compact(
        'empleado',
        'sueldo',
        'movimientos',
        'ingresos',
        'descuentos',
        'neto'
    ));
}
    public function create($empleado)
    {
        $empleado = Empleado::findOrFail($empleado);
        return view('rrhh.empleados.sueldos.create', compact('empleado'));
    }

   public function store(Request $request, $empleado_id)
{
    $request->validate([
        'sueldo_base' => 'required|numeric',
        'valor_hora' => 'nullable|numeric',
        'porcentaje_hora_extra' => 'nullable|numeric',
        'fecha_desde' => 'required|date',
    ]);

    // desactivar sueldo anterior
    Sueldo::where('empleado_id', $empleado_id)
        ->update(['activo' => false]);

    // crear nuevo sueldo
    Sueldo::create([
        'empleado_id' => $empleado_id,
        'sueldo_base' => $request->sueldo_base,
        'valor_hora' => $request->valor_hora,
        'porcentaje_hora_extra' => $request->porcentaje_hora_extra ?? 1.5,
        'fecha_desde' => $request->fecha_desde,
        'activo' => true,
    ]);

    return back()->with('success', 'Sueldo configurado correctamente');
}

    public function edit($empleado, $id)
    {
        $sueldo = Sueldo::findOrFail($id);

        return view('rrhh.empleados.sueldos.edit', compact('sueldo', 'empleado'));
    }

    public function update(Request $request, $empleado, $id)
    {
        $sueldo = Sueldo::findOrFail($id);

        $sueldo->update($request->all());

        return redirect()->route('rrhh.sueldos.index', $empleado);
    }

    public function destroy($empleado, $id)
    {
        Sueldo::findOrFail($id)->delete();

        return back()->with('success', 'Sueldo eliminado');
    }
}