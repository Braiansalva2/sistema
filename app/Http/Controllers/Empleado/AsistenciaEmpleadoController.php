<?php

namespace App\Http\Controllers\Empleado;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Empleado;
use App\Models\MovimientoAsistencia;
use App\Models\ConsentimientoAsistencia;
use App\Models\Sucursal;
use App\Models\UbicacionAsistencia;


class AsistenciaEmpleadoController extends Controller
{

public function index()
    {
        $empleado = Empleado::where('user_id', auth()->id())
            ->firstOrFail();

        // 🔒 CONTROL POR TIPO DE EMPLEADO (AGREGADO)
       if (!in_array($empleado->tipo_empleado, ['base', 'chofer', 'mixto'])) {
            return redirect()
                ->route('empleado.portal')
                ->with('error', 'Este módulo es solo para personal de base.');
        }

        // ÚLTIMO MOVIMIENTO
  $ultimoMovimiento = MovimientoAsistencia::where('empleado_id', $empleado->id)
    ->whereDate('fecha_hora', today())
    ->latest('fecha_hora')
    ->first();

        // consentimiento
        $consentimiento = ConsentimientoAsistencia::where('empleado_id', $empleado->id)
            ->latest()
            ->first();

            // dd($consentimiento);
        // DEFINIR ACCIÓN
        $accion = 'entrada';

        if ($ultimoMovimiento && $ultimoMovimiento->tipo == 'entrada') {
            $accion = 'salida';
        }

        // MOVIMIENTOS DEL DÍA
        $movimientosHoy = MovimientoAsistencia::where('empleado_id', $empleado->id)
            ->whereDate('fecha_hora', today())
            ->orderBy('fecha_hora', 'asc')
            ->get();

           $mostrarConsentimiento = !$consentimiento;
        return view('empleado.asistencia', compact(
            'empleado',
            'ultimoMovimiento',
            'accion',
            'movimientosHoy',
            'consentimiento',
             'mostrarConsentimiento'
        ));
    }

public function marcar(Request $request)
    {
        // EMPLEADO LOGUEADO
        $empleado = Empleado::where('user_id', auth()->id())
            ->firstOrFail();

        // 🔒 CONTROL POR TIPO DE EMPLEADO (AGREGADO)
        if (!in_array($empleado->tipo_empleado, ['base', 'chofer', 'mixto'])) {
            abort(403, 'No autorizado');
        }

        // VALIDAR REQUEST
        $request->validate([
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
        ]);

        // ÚLTIMO MOVIMIENTO
       $ultimoMovimiento = MovimientoAsistencia::where('empleado_id', $empleado->id)
                ->whereDate('fecha_hora', today())
                ->latest('fecha_hora')
                ->first();

        // =========================================
        // 🔥 PROTECCIÓN CONTRA SPAM / DOBLE MARCADO
        // =========================================

       // Definir tipo a registrar
            $tipo = 'entrada';

            if ($ultimoMovimiento && $ultimoMovimiento->tipo === 'entrada') {
                $tipo = 'salida';
            }

            // Evitar doble clic del mismo tipo en menos de 1 minuto
            if ($ultimoMovimiento &&
                $ultimoMovimiento->tipo === $tipo &&
                $ultimoMovimiento->fecha_hora->diffInSeconds(now()) < 60) {

                return redirect()
                    ->route('empleado.asistencia')
                    ->with('error', 'La asistencia ya fue registrada recientemente.');
            }

      
// =========================================
// 🔥 DEFINIR SI ES ENTRADA O SALIDA
// =========================================

$tipo = 'entrada';

if ($ultimoMovimiento && $ultimoMovimiento->tipo == 'entrada') {

    $tipo = 'salida';

}





        $estadoGps = 'sin_gps';

$distancia = null;

// 📍 SI HAY GPS
if ($request->latitud && $request->longitud) {

    // 🔥 BUSCAR SUCURSAL ACTIVA
    $ubicacion = UbicacionAsistencia::where('estado', true)
        ->first();

    if ($ubicacion) {

        // 🔥 CALCULAR DISTANCIA
        $distancia = $this->calcularDistancia(

            $request->latitud,
            $request->longitud,

            $ubicacion->latitud,
            $ubicacion->longitud

        );

        // 🔥 VALIDAR RANGO
        if ($distancia <= $ubicacion->radio_metros) {

            $estadoGps = 'correcto';

        } else {

            $estadoGps = 'fuera_de_zona';

        }

    } else {

        $estadoGps = 'error';

    }
}

   
        // =========================================
        // 🔥 GUARDAR MOVIMIENTO
        // =========================================

        MovimientoAsistencia::create([

            'empleado_id' => $empleado->id,

            'tipo' => $tipo,

            'fecha_hora' => now(),

            'latitud' => $request->latitud,
            'longitud' => $request->longitud,

            'con_ubicacion' => $request->latitud ? true : false,

            'ip' => $request->ip(),

            'device' => $request->userAgent(),

            'estado_gps' => $estadoGps,

            'distancia_base_metros' => $distancia,
        ]);

        // =========================================
        // 🔥 RESPUESTA
        // =========================================

        return redirect()
            ->route('empleado.asistencia')
            ->with('success','Asistencia registrada correctamente.'
            );
    }

public function guardarConsentimiento(Request $request)
    {
        $empleado = Empleado::where('user_id', auth()->id())
            ->firstOrFail();

        // 🔒 CONTROL POR TIPO DE EMPLEADO (AGREGADO)
        if (!in_array($empleado->tipo_empleado,['base', 'chofer', 'mixto'])) {
            abort(403);
        }

        ConsentimientoAsistencia::create([

            'empleado_id' => $empleado->id,

            'texto_aceptado' => '
                Consentimiento de uso del sistema de asistencia laboral.
            ',

            'version' => '1.0',

            'aceptado' => true,

            'fecha_aceptacion' => now(),

            'ip' => $request->ip(),

            'device' => $request->userAgent(),
        ]);

        return redirect()
            ->route('empleado.asistencia')
            ->with(
                'success',
                'Consentimiento aceptado correctamente.'
            );
    }

private function calcularDistancia(
    $lat1,
    $lon1,
    $lat2,
    $lon2
) {

    $radioTierra = 6371000;

    $latDesde = deg2rad($lat1);
    $lonDesde = deg2rad($lon1);

    $latHasta = deg2rad($lat2);
    $lonHasta = deg2rad($lon2);

    $latDelta = $latHasta - $latDesde;
    $lonDelta = $lonHasta - $lonDesde;

    $angulo = 2 * asin(
        sqrt(
            pow(sin($latDelta / 2), 2) +
            cos($latDesde) *
            cos($latHasta) *
            pow(sin($lonDelta / 2), 2)
        )
    );

    return $angulo * $radioTierra;
}
}