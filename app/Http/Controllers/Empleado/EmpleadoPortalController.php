<?php

namespace App\Http\Controllers\Empleado;

use App\Http\Controllers\Controller;

use App\Models\Empleado;
use App\Models\Vacacion;
use App\Models\Adelanto;
use App\Models\Licencia;
use App\Models\TipoPrenda;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmpleadoPortalController extends Controller
{
public function index()
{
    $empleado = Empleado::with('grupoFamiliar')
        ->where('user_id', auth()->id())
        ->first();

    if (!$empleado) {
        dd('No existe empleado vinculado a este usuario');  
    }

    return view('empleado.index', compact('empleado'));
}


    public function perfil()
    {
        $empleado = Empleado::where('user_id', auth()->id())
            ->with('grupoFamiliar')
            ->firstOrFail();
$talles = TipoPrenda::with('talles')->where('estado', true)->get();
$empleadoTalles = $empleado->talles->keyBy('tipo_prenda_id');

return view('empleado.perfil', compact('empleado', 'talles', 'empleadoTalles'));
        return view('empleado.perfil', compact('empleado'));
    }

       public function vacaciones()
    {
        $empleado = Empleado::where('user_id', auth()->id())->firstOrFail();

        $vacaciones = Vacacion::where('empleado_id', $empleado->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('empleado.vacaciones', compact('empleado', 'vacaciones'));
    }

    public function guardarVacaciones(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'observaciones' => 'nullable|string|max:1000',
        ], [
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_fin.required' => 'La fecha de fin es obligatoria.',
            'fecha_fin.after_or_equal' => 'La fecha de fin no puede ser menor que la fecha de inicio.',
        ]);

        $empleado = Empleado::where('user_id', auth()->id())->firstOrFail();

        $fechaInicio = Carbon::parse($request->fecha_inicio);
        $fechaFin = Carbon::parse($request->fecha_fin);

        $diasSolicitados = $fechaInicio->diffInDays($fechaFin) + 1;

        Vacacion::create([
            'empleado_id' => $empleado->id,
            'periodo' => $fechaInicio->year,
            'dias_correspondientes' => 14,
            'dias_tomados' => $diasSolicitados,
            'fecha_inicio' => $fechaInicio->format('Y-m-d'),
            'fecha_fin' => $fechaFin->format('Y-m-d'),
            'estado' => 'pendiente',
            'observaciones' => $request->observaciones,
        ]);

        return redirect()
            ->route('empleado.vacaciones')
            ->with('success', 'La solicitud de vacaciones fue enviada correctamente.');
    }

 public function adelantos()
{
    $empleado = Empleado::where('user_id', auth()->id())->firstOrFail();

    $adelantos = $empleado->adelantos()
        ->with('movimientos')
        ->latest()
        ->take(3)
        ->get();

    return view('empleado.adelantos', compact('empleado', 'adelantos'));
}

public function guardarAdelanto(Request $request)
{
    // 🔹 Validación
    $request->validate([
        'monto_total' => 'required|numeric|min:1',
        'cuotas_total' => 'required|integer|min:1|max:24',
        'motivo' => 'nullable|string|max:1000',
    ]);

    // 🔹 Obtener empleado logueado
    $empleado = Empleado::where('user_id', auth()->id())->firstOrFail();

    // 🔹 Crear adelanto
    Adelanto::create([
        'empleado_id' => $empleado->id,
        'monto_total' => $request->monto_total,
        'cuotas_total' => $request->cuotas_total,
        'motivo' => $request->motivo,
        'estado' => 'pendiente',
        'fecha_solicitud' => now(),
    ]);

    // 🔹 Redirigir con mensaje
    return redirect()
        ->route('empleado.adelantos')
        ->with('success', 'Solicitud de adelanto enviada correctamente.');
}

public function historialAdelantos()
{
    $empleado = Empleado::where('user_id', auth()->id())->firstOrFail();

    $adelantos = $empleado->adelantos()
        ->with('movimientos')
        ->latest()
        ->paginate(8);

    return view('empleado.adelantos_historial', compact('empleado', 'adelantos'));
}



public function permisos()
{
    $user = auth()->user();

    // Buscar empleado del usuario
    $empleado = \App\Models\Empleado::where('user_id', $user->id)->first();

    // Traer permisos del empleado
    $permisos = \App\Models\Permiso::where('empleado_id', $empleado->id)
                    ->latest()
                    ->get();

    return view('empleado.permisos', compact('empleado', 'permisos'));
}


public function guardarPermiso(Request $request)
{
    $user = auth()->user();

    $empleado = \App\Models\Empleado::where('user_id', $user->id)->first();

    // Validación
    $request->validate([
        'tipo' => 'required|in:dias,horas',
        'motivo' => 'required|string|max:500',
    ]);

    $data = [
        'empleado_id' => $empleado->id,
        'tipo' => $request->tipo,
        'motivo' => $request->motivo,
        'estado' => 'pendiente',
    ];

    // 👉 SI ES POR DÍAS
    if ($request->tipo == 'dias') {

        $request->validate([
            'fecha_desde' => 'required|date',
            'fecha_hasta' => 'required|date|after_or_equal:fecha_desde',
        ]);

        $data['fecha_desde'] = $request->fecha_desde;
        $data['fecha_hasta'] = $request->fecha_hasta;

        $data['total_dias'] = \Carbon\Carbon::parse($request->fecha_desde)
            ->diffInDays(\Carbon\Carbon::parse($request->fecha_hasta)) + 1;
    }

    // 👉 SI ES POR HORAS
  if ($request->tipo == 'horas') {

    $request->validate([
        'fecha_horas' => 'required|date',
        'hora_desde' => 'required',
        'hora_hasta' => 'required',
    ]);

    $data['fecha_horas'] = $request->fecha_horas;
    $data['hora_desde'] = $request->hora_desde;
    $data['hora_hasta'] = $request->hora_hasta;

    $inicio = \Carbon\Carbon::parse($request->hora_desde);
    $fin = \Carbon\Carbon::parse($request->hora_hasta);

    $data['total_horas'] = $inicio->diffInMinutes($fin); // 🔥 mejor en minutos
}

    \App\Models\Permiso::create($data);

    return redirect()->route('empleado.permisos')
        ->with('success', 'Permiso solicitado correctamente');
}   



public function licencias()
{
    $empleado = Empleado::where('user_id', auth()->id())->firstOrFail();

    $licencias = Licencia::where('empleado_id', $empleado->id)
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get();

    return view('empleado.licencias', compact('empleado', 'licencias'));
}

public function guardarLicencia(Request $request)
{
    $empleado = Empleado::where('user_id', auth()->id())->firstOrFail();

    // 🔹 Validación base
    $request->validate([
        'tipo' => 'required|string',
        'observaciones' => 'nullable|string|max:1000',
    ]);

    $data = [
        'empleado_id' => $empleado->id,
        'tipo' => $request->tipo,
        'estado' => 'pendiente',
        'observaciones' => $request->observaciones,
    ];

    // =============================
    // VACACIONES / LICENCIAS POR DÍAS
    // =============================
    if (in_array($request->tipo, ['vacaciones', 'matrimonio', 'nacimiento', 'fallecimiento', 'capacitacion'])) {

        $request->validate([
            'fecha_desde' => 'required|date',
            'fecha_hasta' => 'required|date|after_or_equal:fecha_desde',
        ]);

        $data['fecha_desde'] = $request->fecha_desde;
        $data['fecha_hasta'] = $request->fecha_hasta;

        $data['dias'] = \Carbon\Carbon::parse($request->fecha_desde)
            ->diffInDays(\Carbon\Carbon::parse($request->fecha_hasta)) + 1;
    }

    // =============================
    // LICENCIA POR ENFERMEDAD
    // =============================
    if ($request->tipo == 'enfermedad') {

        $request->validate([
            'fecha_desde' => 'required|date',
            'fecha_hasta' => 'required|date|after_or_equal:fecha_desde',
            'archivo' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data['fecha_desde'] = $request->fecha_desde;
        $data['fecha_hasta'] = $request->fecha_hasta;

        $data['dias'] = \Carbon\Carbon::parse($request->fecha_desde)
            ->diffInDays(\Carbon\Carbon::parse($request->fecha_hasta)) + 1;

        // 📎 Guardar archivo
        if ($request->hasFile('archivo')) {
            $data['archivo'] = $request->file('archivo')->store('licencias', 'public');
        }
    }

    // =============================
    // 🟡 LICENCIA ORDINARIA (POR HORAS)
    // =============================
    if ($request->tipo == 'ordinaria') {

        $request->validate([
            'fecha_desde' => 'required|date',
            'hora_desde' => 'required',
            'hora_hasta' => 'required',
        ]);

        $data['fecha_desde'] = $request->fecha_desde;
        $data['hora_desde'] = $request->hora_desde;
        $data['hora_hasta'] = $request->hora_hasta;

        $inicio = \Carbon\Carbon::parse($request->hora_desde);
        $fin = \Carbon\Carbon::parse($request->hora_hasta);

        $data['horas'] = $inicio->diffInMinutes($fin);
    }

    // 🔥 GUARDAR
    Licencia::create($data);

    return redirect()
        ->route('empleado.licencias')
        ->with('success', 'Licencia solicitada correctamente');
}


public function historialLicencias()
{
    $empleado = Empleado::where('user_id', auth()->id())->firstOrFail();

    $licencias = Licencia::where('empleado_id', $empleado->id)
        ->latest()
        ->paginate(10);

    return view('empleado.licencias_historial', compact('empleado', 'licencias'));
}



public function guardarTalles(Request $request)
{
    $empleado = auth()->user()->empleado;

    foreach ($request->talles as $tipo_prenda_id => $talle_id) {

        \App\Models\EmpleadoTalle::updateOrCreate(
            [
                'empleado_id' => $empleado->id,
                'tipo_prenda_id' => $tipo_prenda_id
            ],
            [
                'tipo_prenda_talle_id' => $talle_id
            ]
        );
    }

    return back()->with('success', 'Talles actualizados correctamente');
}

// metodos para los rosters
public function roster()
{
    $empleado = auth()->user()->empleado;

    if (!$empleado || $empleado->tipo_empleado !== 'roster') {
        abort(403, 'No tenés acceso a esta sección.');
    }

    // Buscar el grupo de roster activo al que pertenece
    $roster = \App\Models\Roster::where('estado', 'Activo')
        ->whereHas('empleados', function ($query) use ($empleado) {
            $query->where('empleados.id', $empleado->id);
        })
        ->with('empleados')
        ->first();

    return view('empleado.roster', compact(
        'empleado',
        'roster'
    ));
}
}