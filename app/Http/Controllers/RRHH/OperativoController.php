<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empleado;

class OperativoController extends Controller
{
    

public function operativoEnVivo()
{
    $empleados = Empleado::whereIn('tipo_empleado', ['chofer','mixto'])
        ->with(['eventosOperativos' => function($q){
            $q->orderBy('fecha_hora','asc');
        }])
        ->get();

    $data = [];

    foreach ($empleados as $emp) {

        $eventos = $emp->eventosOperativos;

        if ($eventos->isEmpty()) {
            continue;
        }

        $inicio = $eventos->where('tipo_evento','inicio_jornada')->first();
        $reportes = $eventos->where('tipo_evento','punto_control')->values();
        $fin = $eventos->where('tipo_evento','fin_jornada')->first();
        $ultimo = $eventos->last();

        if ($fin) {
            continue;
        }

        $totalPasos = 5;
        $avance = min($reportes->count(), $totalPasos);
        $porcentaje = ($avance / $totalPasos) * 100;

        $data[] = [
            'empleado'   => $emp,
            'inicio'     => $inicio,
            'reportes'   => $reportes,
            'ultimo'     => $ultimo,
            'porcentaje' => $porcentaje,
        ];
    }

    return view('rrhh.operativo.index', compact('data'));
}
}
