<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventoOperativo;
use App\Models\Empleado;

class RRHHOperativoController extends Controller
{
 public function operativoEnVivo(Request $request)
{
    $empleados = Empleado::whereIn('tipo_empleado', ['chofer','mixto'])
        ->with(['eventosOperativos' => function($q){
            $q->orderBy('fecha_hora','asc');
        }])
        ->get();

    // 🔍 BUSCADOR
    if ($request->buscar) {
        $empleados = $empleados->filter(function($emp) use ($request){
            return str_contains(
                strtolower($emp->nombre . ' ' . $emp->apellido),
                strtolower($request->buscar)
            );
        });
    }

    $activos = [];
    $finalizados = [];

    foreach ($empleados as $emp) {

        $eventos = $emp->eventosOperativos;

        if ($eventos->isEmpty()) continue;

        // 🔥 último evento del empleado
        $ultimo = $eventos->last();

        // 🔥 buscar último inicio (viaje actual o último viaje)
        $inicio = $eventos
            ->where('tipo_evento','inicio_jornada')
            ->last();

        if (!$inicio) continue;

        // 🔥 eventos desde ese inicio
        $eventosActuales = $eventos->filter(function ($e) use ($inicio) {
            return $e->fecha_hora >= $inicio->fecha_hora;
        });

        // 🔥 reportes del viaje
        $reportes = $eventosActuales
            ->where('tipo_evento','punto_control')
            ->values();

        // 🔥 PROGRESO DINÁMICO (NO limitado a 5)
        $totalPasos = 10; // podés ajustar esto
        $avance = min($reportes->count(), $totalPasos);
        $porcentaje = ($avance / $totalPasos) * 100;

        $dataItem = [
            'empleado'   => $emp,
            'inicio'     => $inicio,
            'reportes'   => $reportes,
            'ultimo'     => $ultimo,
            'porcentaje' => $porcentaje,
        ];

        // 🔥 SEPARACIÓN CLAVE
        if ($ultimo->tipo_evento == 'fin_jornada') {

            // llega al final SIEMPRE
            $dataItem['porcentaje'] = 100;

            $finalizados[] = $dataItem;

        } else {

            $activos[] = $dataItem;

        }
    }

    return view('rrhh.operativo.index', compact('activos','finalizados'));
}



public function historial(Request $request)
{
    $query = EventoOperativo::where('tipo_evento', 'inicio_jornada')
        ->with('empleado');

    // 🔍 filtro por empleado
    if ($request->empleado_id) {
        $query->where('empleado_id', $request->empleado_id);
    }

    // 🔍 filtro por fecha
    if ($request->desde && $request->hasta) {
        $query->whereBetween('fecha_hora', [$request->desde, $request->hasta]);
    }

    $viajes = $query->orderBy('fecha_hora', 'desc')->get();

    $empleados = Empleado::whereIn('tipo_empleado', ['chofer','mixto'])->get();

  return view('rrhh.operativo.viajes', compact('viajes','empleados'));
}
public function detalle($id)
{
    $inicio = EventoOperativo::findOrFail($id);

    $eventos = EventoOperativo::where('empleado_id', $inicio->empleado_id)
        ->where('fecha_hora', '>=', $inicio->fecha_hora)
        ->orderBy('fecha_hora')
        ->get([
            'id',
            'tipo_evento',
            'fecha_hora',
            'lugar',
            'latitud',
            'longitud'
        ]);

    return response()->json($eventos);
}



    public function matriz()
    {
        return view('rrhh.matriz.index');
    }
}