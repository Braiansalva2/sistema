<?php

namespace App\Http\Controllers\Comercial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tramo;
use App\Models\Ubicacion;
use App\Models\TipoVehiculo;
use App\Models\TipoServicio;
use App\Models\TarifaTramo;

class TramoController extends Controller
{
  public function index()
{
    $tramos = Tramo::with([
        'origen',
        'destino',
        'tipoVehiculo',
        'tipoServicio',
        'tarifas' // 🔥 IMPORTANTE
    ])->get();

    $tiposServicio = TipoServicio::all();
    $tiposVehiculo = TipoVehiculo::all();

    return view('comercial.tramos.index', compact(
        'tramos',
        'tiposServicio',
        'tiposVehiculo'
    ));
}

   public function create()
{
    $ubicaciones = Ubicacion::where('estado', 'activo')
        ->orderBy('nombre')
        ->get();

    $tiposVehiculos = TipoVehiculo::orderBy('nombre')->get();
    $tiposServicio = TipoServicio::where('estado', 'activo')
        ->orderBy('nombre')
        ->get();

    return view('comercial.tramos.create', compact(
        'ubicaciones',
        'tiposVehiculos',
        'tiposServicio'
    ));
}

 public function store(Request $request)
{
      //  return $request;
    DB::transaction(function () use ($request) {

        // ================= TRAMO =================
        $tramo = Tramo::create([
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'origen_id' => $request->origen_id,
            'destino_id' => $request->destino_id,
            'tipos_vehiculos_id' => $request->tipo_vehiculo_id,
            'tipos_servicio_id' => $request->tipo_servicio_id,
            'distancia_km' => $request->distancia_km,
            'tiempo_estimado_min' => $request->tiempo_estimado_min,
            'observaciones' => $request->observaciones,
            'estado' => $request->estado,
        ]);

        // ================= TARIFAS =================
        if ($request->has('tarifas')) {

            foreach ($request->tarifas as $tarifa) {

                // VALIDAR FILA COMPLETA
                if (
                    !empty($tarifa['tipo']) &&
                    !empty($tarifa['precio']) &&
                    !empty($tarifa['fecha_desde'])
                ) {

                    TarifaTramo::create([
                        'tramo_id'     => $tramo->id,
                        'tipo'         => $tarifa['tipo'],
                        'precio'       => $tarifa['precio'],
                        'fecha_desde'  => $tarifa['fecha_desde'],
                        'fecha_hasta'  => $tarifa['fecha_hasta'] ?? null,
                        'activo'       => true,
                        'motivo'       => null,
                    ]);
                }
            }
        }

    });

    return redirect()
        ->route('comercial.tramos.index')
        ->with('success', 'Tramo y tarifas guardados correctamente');
}

  public function edit(Tramo $tramo)
{ //dd($tramo->all());
    // Cargar relaciones del tramo
    $tramo->load([
        'origen',
        'destino',
        'tipoVehiculo',
        'tipoServicio',
        'tarifas'
    ]);

    // Datos para selects
    $ubicaciones = Ubicacion::where('estado', 'activo')
        ->orderBy('nombre')
        ->get();

    $tiposVehiculos = TipoVehiculo::orderBy('nombre')->get();

    $tiposServicio = TipoServicio::where('estado', 'activo')
        ->orderBy('nombre')
        ->get();

    return view('comercial.tramos.edit', compact(
        'tramo',
        'ubicaciones',
        'tiposVehiculos',
        'tiposServicio'
    ));
}

   public function update(Request $request, Tramo $tramo)
{

//dd($request->all());
    // ================= VALIDACIÓN =================
    $request->validate([

        'nombre' => 'required|string|max:255',
        'origen_id' => 'required|exists:ubicaciones,id',
        'destino_id' => 'required|exists:ubicaciones,id',
        'tipo_servicio_id' => 'required|exists:tipos_servicio,id',

    ]);



    // ================= ACTUALIZAR TRAMO =================
    $tramo->update([
        'codigo' => $request->codigo,
        'nombre' => $request->nombre,
        'origen_id' => $request->origen_id,
        'destino_id' => $request->destino_id,
        'tipos_vehiculos_id' => $request->tipos_vehiculos_id,
        'tipos_servicio_id' => $request->tipo_servicio_id,
        'distancia_km' => $request->distancia_km,
        'tiempo_estimado_min' => $request->tiempo_estimado_min,
        'observaciones' => $request->observaciones,
        'estado' => $request->estado,
    ]);

    // ================= TARIFAS =================
    $idsTarifasActuales = [];

    if ($request->has('tarifas')) {

        foreach ($request->tarifas as $tarifa) {

    // Evitar guardar vacíos
            if (empty($tarifa['tipo']) || empty($tarifa['precio'])) {
                continue;
            }

    // ================= UPDATE =================
            if (!empty($tarifa['id'])) {

                $t = \App\Models\TarifaTramo::find($tarifa['id']);

                if ($t) {
                    $t->update([
                        'tipo' => $tarifa['tipo'],
                        'precio' => $tarifa['precio'],
                        'fecha_desde' => $tarifa['fecha_desde'] ?? null,
                        'fecha_hasta' => $tarifa['fecha_hasta'] ?? null,
                        'activo' => 1
                    ]);

                    $idsTarifasActuales[] = $t->id;
                }

            } else {

    // ================= CREATE =================
                $nueva = $tramo->tarifas()->create([
                    'tipo' => $tarifa['tipo'],
                    'precio' => $tarifa['precio'],
                    'fecha_desde' => $tarifa['fecha_desde'] ?? null,
                    'fecha_hasta' => $tarifa['fecha_hasta'] ?? null,
                    'activo' => 1

                ]);  
                
                $idsTarifasActuales[] = $nueva->id;
            }
        }
    }

    // ================= ELIMINAR TARIFAS BORRADAS =================
    $tramo->tarifas()
        ->whereNotIn('id', $idsTarifasActuales)
        ->delete();
   
    // ================= REDIRECCIÓN =================
    return redirect()
        ->route('comercial.tramos.index')
        ->with('success', 'Tramo actualizado correctamente');
}

    public function destroy(Tramo $tramo)
    {
        $tramo->delete();

        return redirect()
            ->route('comercial.tramos.index')
            ->with('success', 'Tramo eliminado correctamente');
    }

  }