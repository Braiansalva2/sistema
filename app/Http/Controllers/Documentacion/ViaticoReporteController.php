<?php

namespace App\Http\Controllers\Documentacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Viatico;
use App\Models\ViaticoDetalle;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Empleado;

class ViaticoReporteController extends Controller
{
    public function index(Request $request)
    {
        $mes = $request->mes ?? now()->month;
        $anio = $request->anio ?? now()->year;

        // TOTAL GENERAL
        $totalGeneral = Viatico::whereMonth('fecha_salida', $mes)
            ->whereYear('fecha_salida', $anio)
            ->sum('total');

        // CANTIDAD DE VIÁTICOS
        $cantidadViaticos = Viatico::whereMonth('fecha_salida', $mes)
            ->whereYear('fecha_salida', $anio)
            ->count();

        // TOP CHOFERES
        $topChoferes = Viatico::select(
                'empleado_id',
                DB::raw('SUM(total) as total_gastado'),
                DB::raw('COUNT(*) as cantidad')
            )
            ->with('empleado')
            ->whereMonth('fecha_salida', $mes)
            ->whereYear('fecha_salida', $anio)
            ->groupBy('empleado_id')
            ->orderByDesc('total_gastado')
            ->take(10)
            ->get();

// GASTOS POR CONCEPTO
$gastosConcepto = ViaticoDetalle::select(
        'concepto',
        DB::raw('SUM(subtotal) as total')
    )
    ->join('viaticos', 'viaticos.id', '=', 'viatico_detalles.viatico_id')
    ->whereMonth('viaticos.fecha_salida', $mes)
    ->whereYear('viaticos.fecha_salida', $anio)
    ->groupBy('concepto')
    ->orderByDesc('total')
    ->get();

      // DESTINOS MÁS FRECUENTES
$destinos = Viatico::select(
        'destino',
        DB::raw('COUNT(*) as total')
    )
    ->whereMonth('fecha_salida', $mes)
    ->whereYear('fecha_salida', $anio)
    ->groupBy('destino')
    ->orderByDesc('total')
    ->take(10)
    ->get();



            $desde = $request->desde
    ? Carbon::parse($request->desde)->startOfMonth()
    : now()->subMonths(5)->startOfMonth();

$hasta = $request->hasta
    ? Carbon::parse($request->hasta)->endOfMonth()
    : now()->endOfMonth();

$choferesSeleccionados = $request->choferes ?? [];

$empleados = Empleado::orderBy('apellido')->get();

$query = Viatico::with('empleado')
    ->whereBetween('fecha_salida', [$desde, $hasta]);

if (!empty($choferesSeleccionados)) {

    $query->whereIn('empleado_id', $choferesSeleccionados);
}

$viaticosGrafico = $query->get();

$meses = [];
$datasets = [];

for ($date = $desde->copy(); $date <= $hasta; $date->addMonth()) {

    $meses[] = $date->format('M Y');
}

$empleadosAgrupados = $viaticosGrafico->groupBy('empleado_id');

foreach ($empleadosAgrupados as $empleadoId => $viaticos) {

    $empleado = $viaticos->first()->empleado;

    $data = [];

    foreach ($meses as $mesTexto) {

        $totalMes = $viaticos
            ->filter(function ($v) use ($mesTexto) {

                return Carbon::parse($v->fecha_salida)
                    ->format('M Y') == $mesTexto;
            })
            ->sum('total');

        $data[] = $totalMes;
    }

    $datasets[] = [

        'label' => $empleado->apellido . ' ' . $empleado->nombre,
        'data' => $data,
        'fill' => false,
        'tension' => 0.3,
    ];
}
      return view('documentacion.viaticos.reportes', compact(
                    'mes',
                    'anio',
                    'totalGeneral',
                    'cantidadViaticos',
                    'topChoferes',
                    'gastosConcepto',
                    'destinos',
                    'meses',
                    'datasets',
                    'empleados'
                ));
    }
}