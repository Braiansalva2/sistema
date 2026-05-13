<?php

namespace App\Http\Controllers\Empleado;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\EventoOperativo;
use App\Models\ConsentimientoOperativo;

class OperativoController extends Controller
{
    public function index()
    {
        $empleado = Empleado::where('user_id', auth()->id())
            ->firstOrFail();

        if (!in_array($empleado->tipo_empleado, ['chofer', 'mixto'])) {
            abort(403);
        }

        // último evento
        $ultimo = EventoOperativo::where('empleado_id', $empleado->id)
            ->latest('fecha_hora')
            ->first();
            
        return view('empleado.operativo.index', compact('empleado', 'ultimo'));
    }

    public function iniciar(Request $request)
    {
        $empleado = Empleado::where('user_id', auth()->id())
            ->firstOrFail();

        if (!in_array($empleado->tipo_empleado, ['chofer', 'mixto'])) {
            abort(403);
        }

        $ultimo = EventoOperativo::where('empleado_id', $empleado->id)
            ->latest('fecha_hora')
            ->first();

        // evitar iniciar si ya está en jornada
        if ($ultimo && $ultimo->tipo_evento != 'fin_jornada') {
            return back()->with('error', 'Ya tenés una jornada activa');
        }
           
       $lugar = $this->obtenerLugar(
    $request->latitud,
    $request->longitud
);

EventoOperativo::create([

    'empleado_id' => $empleado->id,

    'tipo_evento' => 'inicio_jornada',

    'fecha_hora' => now(),

    'latitud' => $request->latitud,

    'longitud' => $request->longitud,

    'lugar' => $lugar,

    'origen' => $request->origen,

    'destino' => $request->destino,

    'vehiculo' => $request->vehiculo,

    'ip' => $request->ip(),

    'device' => $request->userAgent(),

]);

        return back()->with('success', 'Jornada iniciada');
    }

    public function reportar(Request $request)
    {
        $empleado = Empleado::where('user_id', auth()->id())
            ->firstOrFail();

        if (!in_array($empleado->tipo_empleado, ['chofer', 'mixto'])) {
            abort(403);
        }

        $ultimo = EventoOperativo::where('empleado_id', $empleado->id)
            ->latest('fecha_hora')
            ->first();

        // no permitir reportar si no inició
        if (!$ultimo || $ultimo->tipo_evento == 'fin_jornada') {
            return back()->with('error', 'Primero debés iniciar el viaje');
        }

        if (!$request->latitud || !$request->longitud) {
        return back()->with('error', 'No se pudo obtener la ubicación');
         }

         $lugar = $this->obtenerLugar($request->latitud, $request->longitud);
        EventoOperativo::create([
            'empleado_id' => $empleado->id,
            'tipo_evento' => 'punto_control',
            'fecha_hora' => now(),
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'lugar' => $lugar, 
            'ip' => $request->ip(),
            'device' => $request->userAgent(),
        ]);

        return back()->with('success', 'Ubicación reportada');
    }


private function obtenerLugar($lat, $lon)
{
    $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lon}";

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_USERAGENT => 'SistemaGVH'
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        curl_close($ch);
        return 'Ubicación desconocida';
    }

    curl_close($ch);

    if ($response) {
        $data = json_decode($response, true);

        // 🔥 ACÁ ESTÁ LA CLAVE
        if (isset($data['address'])) {
            return $data['address']['city']
                ?? $data['address']['town']
                ?? $data['address']['village']
                ?? $data['address']['state']
                ?? $data['display_name']
                ?? 'Ubicación desconocida';
        }
    }

    return 'Ubicación desconocida';
}

    public function finalizar(Request $request)
    {
        $empleado = Empleado::where('user_id', auth()->id())
            ->firstOrFail();

        if (!in_array($empleado->tipo_empleado, ['chofer', 'mixto'])) {
            abort(403);
        }

        $ultimo = EventoOperativo::where('empleado_id', $empleado->id)
            ->latest('fecha_hora')
            ->first();

        //  no permitir finalizar sin iniciar
        if (!$ultimo || $ultimo->tipo_evento == 'fin_jornada') {
            return back()->with('error', 'No hay jornada activa');
        }

        //  VALIDACIÓN GPS (AQUÍ)
        if (!$request->latitud || !$request->longitud) {
            return back()->with('error', 'No se pudo obtener la ubicación');
        }

    $lugar = $this->obtenerLugar($request->latitud, $request->longitud);

        EventoOperativo::create([
            'empleado_id' => $empleado->id,
            'tipo_evento' => 'fin_jornada',
            'fecha_hora' => now(),
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'lugar' => $lugar,
            'ip' => $request->ip(),
            'device' => $request->userAgent(),
        ]);

        return back()->with('success', 'Jornada finalizada');
    }

    public function viaje()
{
    $empleado = Empleado::where('user_id', auth()->id())
        ->firstOrFail();

    if (!in_array($empleado->tipo_empleado, ['chofer', 'mixto'])) {
        abort(403);
    }

    $ultimo = EventoOperativo::where('empleado_id', $empleado->id)
        ->latest('fecha_hora')
        ->first();
    // CONSENTIMIENTO OPERATIVO
    $consentimiento = ConsentimientoOperativo::where(
                    'empleado_id',
                    $empleado->id
                )->latest()->first();
    return view('empleado.operativo.viaje', compact('empleado', 'ultimo','consentimiento'));
}



public function guardarConsentimiento(Request $request)
{
    $empleado = Empleado::where(
        'user_id',
        auth()->id()
    )->firstOrFail();

    // 🔒 SOLO CHOFER/MIXTO
    if (!in_array($empleado->tipo_empleado, ['chofer', 'mixto'])) {

        abort(403);

    }

    ConsentimientoOperativo::create([

        'empleado_id' => $empleado->id,

        'texto_aceptado' => '
            Consentimiento de uso del sistema operativo,
            seguimiento GPS y puntos de control durante
            jornadas operativas.
        ',

        'version' => '1.0',

        'aceptado' => true,

        'fecha_aceptacion' => now(),

        'ip' => $request->ip(),

        'device' => $request->userAgent(),

    ]);

    return redirect()
        ->route('empleado.operativo.viaje')
        ->with(
            'success',
            'Consentimiento operativo aceptado correctamente.'
        );
}
}