<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Models\Adelanto;
use App\Models\Empleado;
use App\Models\MovimientoEmpleado;
use App\Models\AdelantoCuota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdelantoController extends Controller
{
    public function index()
    {
        $adelantos = Adelanto::with(['empleado', 'aprobadoPor', 'pagadoPor', 'movimientos'])
            ->latest()
            ->get();

        return view('rrhh.adelantos.index', compact('adelantos'));
    }

    public function show($id)
    {
        $adelanto = Adelanto::with(['empleado', 'aprobadoPor', 'pagadoPor', 'movimientos'])
            ->findOrFail($id);

        return view('rrhh.adelantos.show', compact('adelanto'));
    }

    public function aprobar($id)
    {
        $adelanto = Adelanto::findOrFail($id);

        $adelanto->update([
            'estado' => 'aprobado',
            'aprobado_por' => Auth::id(),
            'fecha_aprobacion' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'El adelanto fue aprobado correctamente.');
    }

    public function rechazar($id)
    {
        $adelanto = Adelanto::findOrFail($id);

        $adelanto->update([
            'estado' => 'rechazado',
        ]);

        return redirect()
            ->back()
            ->with('success', 'El adelanto fue rechazado correctamente.');
    }

  public function pagar(Request $request, $id)
{
    $request->validate([
        'fecha_pago' => 'required|date',
        'metodo_pago' => 'required|string|max:100',
        'comprobante_pago' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:4096',
    ]);

    $adelanto = Adelanto::with('cuotas')->findOrFail($id);

    // 🔥 VALIDAR SI YA ESTÁ PAGADO
    if ($adelanto->estado == 'pagado' || $adelanto->estado == 'saldado') {

        return back()->with(
            'error',
            'Este adelanto ya fue pagado.'
        );
    }

    // 📎 SUBIR COMPROBANTE
    $comprobantePath = null;

    if ($request->hasFile('comprobante_pago')) {

        $comprobantePath = $request->file('comprobante_pago')
            ->store('adelantos/comprobantes', 'public');
    }

    // 🔥 ACTUALIZAR ADELANTO
    $adelanto->update([
        'estado' => 'pagado',
        'fecha_pago' => $request->fecha_pago,
        'metodo_pago' => $request->metodo_pago,
        'comprobante_pago' => $comprobantePath,
        'pagado_por' => Auth::id(),
    ]);

    // 🔥 CREAR CUOTAS SOLO SI NO EXISTEN
    if ($adelanto->cuotas()->count() == 0) {

        $valorCuota = $adelanto->monto_total / $adelanto->cuotas_total;

        for ($i = 1; $i <= $adelanto->cuotas_total; $i++) {

            $adelanto->cuotas()->create([

                'numero_cuota' => $i,

                'monto' => round($valorCuota, 2),

                'estado' => 'pendiente',

                'fecha_vencimiento' => now()
                    ->addMonths($i)
                    ->format('Y-m-d'),

            ]);
        }
    }

    return redirect()
        ->back()
        ->with(
            'success',
            'El pago del adelanto fue registrado correctamente.'
        );
}

    public function imprimir($id)
{
    $adelanto = Adelanto::with([
        'empleado',
        'movimientos',
        'aprobadoPor'
    ])->findOrFail($id);

    return view(
        'rrhh.adelantos.imprimir',
        compact('adelanto')
    );
}


public function cuotas(Request $request)
{
    $query = Adelanto::with([
            'empleado',
            'cuotas'
        ])
        ->where('estado', 'pagado');

    // 🔎 BUSCADOR
    if ($request->buscar) {

        $query->whereHas('empleado', function ($q) use ($request) {

            $q->where('nombre', 'like', '%' . $request->buscar . '%')
              ->orWhere('apellido', 'like', '%' . $request->buscar . '%')
              ->orWhere('dni', 'like', '%' . $request->buscar . '%');

        });
    }

    // 🎯 FILTRO
    if ($request->filtro == 'pendientes') {

        $query->whereHas('cuotas', function ($q) {
            $q->where('estado', 'pendiente');
        });

    }

    if ($request->filtro == 'saldados') {

        $query->where('estado', 'saldado');
    }

    $adelantos = $query
        ->latest()
        ->paginate(10);

    // 📊 MÉTRICAS
    $totalPendientes = AdelantoCuota::where('estado', 'pendiente')->count();

    $totalPagadas = AdelantoCuota::where('estado', 'pagada')->count();

    $montoPendiente = AdelantoCuota::where('estado', 'pendiente')
        ->sum('monto');

    return view(
        'rrhh.adelantos.cuotas',
        compact(
            'adelantos',
            'totalPendientes',
            'totalPagadas',
            'montoPendiente'
        )
    );
}


public function pagarCuotas(Request $request)
{
    $request->validate([
        'cuotas' => 'required|array'
    ]);

    // 🔥 OBTENER CUOTAS
    $cuotas = AdelantoCuota::whereIn(
        'id',
        $request->cuotas
    )->get();

    // 🔥 MARCAR COMO PAGADAS
    foreach ($cuotas as $cuota) {

        $cuota->update([

            'estado' => 'pagada',

            'fecha_pago' => now(),

            'registrado_por' => Auth::id(),

        ]);
    }

    // 🔥 BUSCAR ADELANTO
    $adelanto = Adelanto::findOrFail(
        $request->adelanto_id
    );

    // 🔥 VERIFICAR SI QUEDAN CUOTAS
    $pendientes = $adelanto->cuotas()
        ->where('estado', 'pendiente')
        ->count();

    // 🔥 SI YA NO HAY → SALDADO
    if ($pendientes == 0) {

        $adelanto->update([
            'estado' => 'saldado'
        ]);
    }

    return redirect()
        ->back()
        ->with(
            'success',
            'Cuotas registradas correctamente.'
        );
}



public function crearExcepcional()
{
    return view('rrhh.adelantos.excepcional_create');
}

public function buscarEmpleado(Request $request)
{
    $buscar = $request->buscar;

    $empleados = Empleado::where(function ($q) use ($buscar) {
            $q->where('nombre', 'like', "%{$buscar}%")
              ->orWhere('apellido', 'like', "%{$buscar}%")
              ->orWhere('dni', 'like', "%{$buscar}%");
        })
        ->limit(10)
        ->get(['id', 'nombre', 'apellido', 'dni', 'tipo_empleado']);

    return response()->json($empleados);
}

public function historialEmpleado($id)
{
    $empleado = Empleado::with(['adelantos.cuotas'])
        ->findOrFail($id);

    return response()->json($empleado);
}

public function guardarExcepcional(Request $request)
{
    $request->validate([
        'empleado_id' => 'required|exists:empleados,id',
        'monto_total' => 'required|numeric|min:1',
        'cuotas_total' => 'required|integer|min:1|max:24',
        'motivo' => 'required|string|max:1000',
    ]);

    Adelanto::create([
        'empleado_id' => $request->empleado_id,
        'monto_total' => $request->monto_total,
        'cuotas_total' => $request->cuotas_total,
        'motivo' => $request->motivo,
        'estado' => 'pendiente',
        'origen' => 'rrhh',
        'fecha_solicitud' => now(),
    ]);

    return redirect()
        ->route('rrhh.adelantos.index')
        ->with('success', 'Adelanto excepcional creado correctamente.');
}

}