<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Viatico;
use App\Models\ViaticoDetalle;
use App\Models\Empleado;
use App\Models\MovimientoEmpleado;

class ViaticoController extends Controller
{
   public function index()
{
    $viaticos = Viatico::with('empleado')->latest()->get();
    $empleados = Empleado::all();

    return view('documentacion.viaticos.index', compact('viaticos', 'empleados'));
}

    public function store(Request $request, $empleado_id)
    {
        $viatico = Viatico::create([
            'empleado_id' => $empleado_id,
            'chofer' => $request->chofer,
            'movil' => $request->movil,
            'origen' => $request->origen,
            'destino' => $request->destino,
            'fecha_salida' => $request->fecha_salida,
            'fecha_regreso' => $request->fecha_regreso,
            'dias' => $request->dias,
            'total' => 0
        ]);

        $total = 0;

        foreach ($request->detalles as $detalle) {

            $subtotal = $detalle['cantidad'] * $detalle['precio'];

            ViaticoDetalle::create([
                'viatico_id' => $viatico->id,
                'concepto' => $detalle['concepto'],
                'cantidad' => $detalle['cantidad'],
                'precio' => $detalle['precio'],
                'subtotal' => $subtotal,
                'comentario' => $detalle['comentario'] ?? null
            ]);

            $total += $subtotal;
        }

        // actualizar total
        $viatico->update(['total' => $total]);

        // 🔥 crear movimiento automático
        MovimientoEmpleado::create([
            'empleado_id' => $empleado_id,
            'tipo' => 'viatico',
            'monto' => $total,
            'fecha' => now(),
            'descripcion' => 'Viático #' . $viatico->id
        ]);

        return redirect()->route('rrhh.sueldos.index', $empleado_id)
            ->with('success', 'Viático creado correctamente');
    }
}