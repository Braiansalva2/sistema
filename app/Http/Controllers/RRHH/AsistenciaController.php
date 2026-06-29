<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Empleado;
use App\Models\Licencia;
use App\Models\EventoOperativo;
use App\Models\MovimientoAsistencia;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
   public function index(Request $request)
{
    $fecha = $request->filled('fecha')
        ? Carbon::parse($request->fecha)
        : today();

    $estadoFiltro = $request->get('estado', 'todos');
    $buscar = trim($request->get('buscar', ''));
    $porPagina = (int) $request->get('por_pagina', 12);

    $porPagina = in_array($porPagina, [12, 24, 50, 100], true)
        ? $porPagina
        : 12;

    $mes = $fecha->month;
    $anio = $fecha->year;

    $empleadosQuery = Empleado::where('estado', 'Activo')
        ->when($buscar !== '', function ($query) use ($buscar) {
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', '%' . $buscar . '%')
                    ->orWhere('apellido', 'like', '%' . $buscar . '%')
                    ->orWhere('dni', 'like', '%' . $buscar . '%');
            });
        })
        ->orderBy('apellido')
        ->orderBy('nombre');
/*
|--------------------------------------------------------------------------
| EMPLEADOS PARA MÉTRICAS (TODOS)
|--------------------------------------------------------------------------
*/

$empleadosMetricas = (clone $empleadosQuery)->get();

$movimientosMetricas = MovimientoAsistencia::whereDate('fecha_hora', $fecha)
    ->whereIn('empleado_id', $empleadosMetricas->pluck('id'))
    ->orderBy('fecha_hora')
    ->get()
    ->groupBy('empleado_id');

$asistenciasMetricas = $this->construirAsistencias(
    $empleadosMetricas,
    $movimientosMetricas,
    $fecha
);

/*
|--------------------------------------------------------------------------
| EMPLEADOS PAGINADOS (CARDS)
|--------------------------------------------------------------------------
*/

$empleados = $empleadosQuery
    ->paginate($porPagina)
    ->appends($request->query());

$movimientosPorEmpleado = MovimientoAsistencia::whereDate('fecha_hora', $fecha)
    ->whereIn('empleado_id', $empleados->pluck('id'))
    ->orderBy('fecha_hora')
    ->get()
    ->groupBy('empleado_id');

$asistencias = $this->construirAsistencias(
    $empleados,
    $movimientosPorEmpleado,
    $fecha
);
  

    if ($estadoFiltro !== 'todos') {
        $asistencias = $asistencias->filter(function ($item) use ($estadoFiltro) {
            if ($estadoFiltro === 'presentes') {
                return $item['estado'] !== 'ausente';
            }

            if ($estadoFiltro === 'ausentes') {
                return $item['estado'] === 'ausente';
            }

            if ($estadoFiltro === 'abiertas') {
                return $item['estado'] === 'jornada_abierta';
            }

            if ($estadoFiltro === 'cerradas') {
                return $item['estado'] === 'jornada_cerrada';
            }

            if ($estadoFiltro === 'fuera_rango') {
                return $item['fuera_rango'];
            }

            if ($estadoFiltro === 'sin_gps') {
                return $item['sin_gps'];
            }

            return true;
        })->values();
    }

   $presentes = $asistenciasMetricas
    ->where('estado', '!=', 'ausente')
    ->count();

$ausentes = $asistenciasMetricas
    ->where('estado', 'ausente')
    ->count();

$abiertas = $asistenciasMetricas
    ->where('estado', 'jornada_abierta')
    ->count();

$cerradas = $asistenciasMetricas
    ->where('estado', 'jornada_cerrada')
    ->count();

$fueraRango = $asistenciasMetricas
    ->where('fuera_rango', true)
    ->count();

$sinGps = $asistenciasMetricas
    ->where('sin_gps', true)
    ->count();

    return view('rrhh.asistencias.index', compact(
        'asistencias',
        'empleados',
        'presentes',
        'ausentes',
        'abiertas',
        'cerradas',
        'fueraRango',
        'sinGps',
        'mes',
        'anio',
        'fecha',
        'estadoFiltro',
        'buscar',
        'porPagina'
    ));
}


private function construirAsistencias($empleados, $movimientosPorEmpleado, $fecha)
{
    $asistencias = collect();

    foreach ($empleados as $empleado) {

        $movimientosHoy = $movimientosPorEmpleado->get($empleado->id, collect());

        $ultimoMovimiento = $movimientosHoy->last();

        $entrada = $movimientosHoy
            ->where('tipo', 'entrada')
            ->first();

        $salida = $movimientosHoy
            ->where('tipo', 'salida')
            ->last();

        $estado = 'ausente';



// VERIFICAR SI EL EMPLEADO ESTABA DE VIAJE
// EN LA FECHA SELECCIONADA
// (Soporta viajes de varios días)
// ======================================

$inicioDia = $fecha->copy()->startOfDay();
$finDia = $fecha->copy()->endOfDay();

// Último inicio antes o durante el día consultado
$inicioViaje = EventoOperativo::where('empleado_id', $empleado->id)
    ->where('tipo_evento', 'inicio_jornada')
    ->where('fecha_hora', '<=', $finDia)
    ->orderByDesc('fecha_hora')
    ->first();

$finViaje = null;
$enViaje = false;

if ($inicioViaje) {

    // Primer fin posterior a ese inicio
    $finViaje = EventoOperativo::where('empleado_id', $empleado->id)
        ->where('tipo_evento', 'fin_jornada')
        ->where('fecha_hora', '>', $inicioViaje->fecha_hora)
        ->orderBy('fecha_hora')
        ->first();

    /*
    Está de viaje si:

    - nunca terminó el viaje

    o

    - el viaje terminó después del día consultado
    */

    if (!$finViaje || $finViaje->fecha_hora >= $inicioDia) {
        $enViaje = true;
    }
}

        if ($movimientosHoy->count() > 0) {

            if ($ultimoMovimiento && $ultimoMovimiento->tipo == 'entrada') {
                $estado = 'jornada_abierta';
            } else {
                $estado = 'jornada_cerrada';
            }

        }
            // Si tiene un viaje abierto, prevalece ese estado
            if ($enViaje) {
                $estado = 'viaje';
            }
        $fueraRango = $ultimoMovimiento &&
            $ultimoMovimiento->estado_gps == 'fuera_de_zona';

        $sinGps = $ultimoMovimiento &&
            $ultimoMovimiento->estado_gps != 'correcto' &&
            $ultimoMovimiento->estado_gps != 'fuera_de_zona';

       $asistencias->push([

            'empleado' => $empleado,

            'movimientos' => $movimientosHoy,

            'ultimo_movimiento' => $ultimoMovimiento,

            'entrada' => $entrada,  

            'salida' => $salida,

            'estado' => $estado,

            'fuera_rango' => $fueraRango,

            'sin_gps' => $sinGps,

            'en_viaje' => $enViaje,

            'inicio_viaje' => $inicioViaje ? $inicioViaje->fecha_hora : null,
            
            'fin_viaje' => $finViaje ? $finViaje->fecha_hora : null,

            'origen' => $inicioViaje->origen ?? null,

            'destino' => $inicioViaje->destino ?? null,

            'vehiculo' => $inicioViaje->vehiculo ?? null,

        ]);
    }

    return $asistencias;
}

public function matriz()
{
    $mes = request('mes', now()->month);
    $anio = request('anio', now()->year);

    $diasMes = \Carbon\Carbon::create($anio, $mes)->daysInMonth;

    $empleados = \App\Models\Empleado::where('estado', 'Activo')
        ->orderBy('apellido')
        ->get();

    // 🔥 SEPARAMOS EMPLEADOS
    $empleadosBase = $empleados->where('tipo_empleado', 'base');
    $empleadosOperativo = $empleados->whereIn('tipo_empleado', ['chofer','mixto']);

    $matriz = [];

    foreach ($empleados as $empleado) {

        // 🔥 TODOS LOS EVENTOS DEL EMPLEADO
        $eventos = \App\Models\EventoOperativo::where('empleado_id', $empleado->id)
            ->orderBy('fecha_hora')
            ->get();

        // 🔥 ARMAMOS VIAJES (RANGOS)
        $viajes = [];
        $inicioActual = null;

        foreach ($eventos as $evento) {

            if ($evento->tipo_evento == 'inicio_jornada') {
                $inicioActual = $evento->fecha_hora;
            }

            if ($evento->tipo_evento == 'fin_jornada' && $inicioActual) {

                $viajes[] = [
                    'inicio' => $inicioActual,
                    'fin' => $evento->fecha_hora
                ];

                $inicioActual = null;
            }
        }

        // 🔥 SI QUEDÓ VIAJE ABIERTO
        if ($inicioActual) {
            $viajes[] = [
                'inicio' => $inicioActual,
                'fin' => now()
            ];
        }

        // 🔥 RECORREMOS DÍAS
        for ($dia = 1; $dia <= $diasMes; $dia++) {

            $fecha = \Carbon\Carbon::create($anio, $mes, $dia);

            $estado = 'ausente';

            // 🔥 DOMINGO
            if ($fecha->isSunday()) {
                $estado = 'domingo';
            }

            // 🔥 LICENCIA
            $licencia = \App\Models\Licencia::where('empleado_id', $empleado->id)
                ->where('estado', 'aprobada')
                ->where(function($query) use ($fecha){

                    $query->where(function($q) use ($fecha){
                        $q->whereNotNull('fecha_hasta')
                          ->whereDate('fecha_desde', '<=', $fecha)
                          ->whereDate('fecha_hasta', '>=', $fecha);
                    })

                    ->orWhere(function($q) use ($fecha){
                        $q->whereNull('fecha_hasta')
                          ->whereDate('fecha_desde', $fecha);
                    });

                })
                ->exists();

            if ($licencia) {
                $estado = 'licencia';
            }

            // 🔥 DETECTAR VIAJE POR RANGO
            $enViaje = false;

            foreach ($viajes as $viaje) {

                if ($fecha->between(
                    \Carbon\Carbon::parse($viaje['inicio'])->startOfDay(),
                    \Carbon\Carbon::parse($viaje['fin'])->endOfDay()
                )) {
                    $enViaje = true;
                    break;
                }
            }

            if ($enViaje) {
                $estado = 'viaje';
            }

            // 🔥 BASE (si no está en viaje)
           if (!$enViaje && !$licencia) {

                $asistencia = \App\Models\MovimientoAsistencia::where('empleado_id', $empleado->id)
                    ->whereDate('fecha_hora', $fecha->toDateString())
                    ->exists();

                if ($asistencia) {
                    $estado = 'base';
                }
            }

            $matriz[$empleado->id][$dia] = [
                'estado' => $estado
            ];
        }
    }

    return view('rrhh.asistencias.matriz', [
        'empleadosBase' => $empleadosBase,
        'empleadosOperativo' => $empleadosOperativo,
        'matriz' => $matriz,
        'diasMes' => $diasMes,
        'mes' => $mes,
        'anio' => $anio
    ]);
}


public function exportar(Request $request)
{
    $tipo = $request->tipo_personal; // base u operativo
    $mes = $request->mes;
    $anio = $request->anio;

    $diasMes = \Carbon\Carbon::create($anio, $mes)->daysInMonth;

    // Obtener la matriz usando tu mismo método
    $request->merge([
        'mes' => $mes,
        'anio' => $anio
    ]);

    // Reutilizamos la lógica de matriz()
    $view = $this->matriz();

    $data = $view->getData();

    $matriz = $data['matriz'];

    if ($tipo == 'base') {
        $empleados = $data['empleadosBase'];
    } else {
        $empleados = $data['empleadosOperativo'];
    }

    return response()->streamDownload(function () use (
        $empleados,
        $matriz,
        $diasMes
    ) {
        $handle = fopen('php://output', 'w');

        // BOM UTF-8
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

        // ENCABEZADOS
        $header = ['Empleado', 'DNI'];

        for ($dia = 1; $dia <= $diasMes; $dia++) {
            $header[] = $dia;
        }

        fputcsv($handle, $header, ';');

        // FILAS
        foreach ($empleados as $empleado) {

            $fila = [
                $empleado->apellido . ' ' . $empleado->nombre,
                $empleado->dni
            ];

            for ($dia = 1; $dia <= $diasMes; $dia++) {

                $estado = $matriz[$empleado->id][$dia]['estado']
                    ?? 'ausente';

                switch ($estado) {
                    case 'base':
                    case 'presente':
                        $letra = 'B';
                        break;

                    case 'viaje':
                        $letra = 'V';
                        break;

                    case 'licencia':
                        $letra = 'L';
                        break;

                    case 'domingo':
                        $letra = 'D';
                        break;

                    default:
                        $letra = 'A';
                        break;
                }

                $fila[] = $letra;
            }

            fputcsv($handle, $fila, ';');
        }

        fclose($handle);

    }, "matriz_{$tipo}_{$mes}_{$anio}.csv", [
        'Content-Type' => 'text/csv; charset=UTF-8',
    ]);
}
}