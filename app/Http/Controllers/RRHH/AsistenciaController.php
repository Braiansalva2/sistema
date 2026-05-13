<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Empleado;
use App\Models\Licencia;
use App\Models\MovimientoAsistencia;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    public function index()
    {
    // MES Y AÑO ACTUAL
    $mes = now()->month;
    $anio = now()->year;
        // EMPLEADOS ACTIVOS
        $empleados = Empleado::where('estado', 'Activo')
            ->get();

        $asistencias = [];

        foreach ($empleados as $empleado) {

            // MOVIMIENTOS DEL DÍA
            $movimientosHoy = MovimientoAsistencia::where('empleado_id', $empleado->id)
                ->whereDate('fecha_hora', today())
                ->orderBy('fecha_hora', 'asc')
                ->get();

            // ÚLTIMO MOVIMIENTO
            $ultimoMovimiento = $movimientosHoy->last();

            // ENTRADA
            $entrada = $movimientosHoy
                ->where('tipo', 'entrada')
                ->first();

            // SALIDA
            $salida = $movimientosHoy
                ->where('tipo', 'salida')
                ->last();

            // ESTADO
            $estado = 'ausente';

            if ($movimientosHoy->count() > 0) {

                if ($ultimoMovimiento && $ultimoMovimiento->tipo == 'entrada') {

                    $estado = 'jornada_abierta';

                } else {

                    $estado = 'jornada_cerrada';
                }
            }

            $asistencias[] = [

                'empleado' => $empleado,

                'movimientos' => $movimientosHoy,

                'ultimo_movimiento' => $ultimoMovimiento,

                'entrada' => $entrada,

                'salida' => $salida,

                'estado' => $estado,
                
            ];
        }

        // MÉTRICAS
        $presentes = collect($asistencias)
            ->where('estado', '!=', 'ausente')
            ->count();

        $ausentes = collect($asistencias)
            ->where('estado', 'ausente')
            ->count();

        $abiertas = collect($asistencias)
            ->where('estado', 'jornada_abierta')
            ->count();

        $cerradas = collect($asistencias)
            ->where('estado', 'jornada_cerrada')
            ->count();

        return view('rrhh.asistencias.index', compact(
            'asistencias',
            'presentes',
            'ausentes',
            'abiertas',
            'cerradas',
            'mes',
            'anio'
        ));
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