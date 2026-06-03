<?php

namespace App\Http\Controllers\Documentacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Viatico;
use App\Models\ViaticoDetalle;
use App\Models\Empleado;

class ViaticoController extends Controller
{
   public function index()
{
    $viaticos = Viatico::with(['empleado', 'extensiones'])
        ->whereNull('viatico_padre_id') //  SOLO ORIGINALES
        ->latest()
        ->get();

    $empleados = Empleado::whereIn('tipo_empleado', ['chofer', 'mixto'])
    ->where('estado', 'Activo')
    ->orderBy('apellido')
    ->orderBy('nombre')
    ->get();

    return view('documentacion.viaticos.index', compact('viaticos','empleados'));
}

    // 📌 GUARDAR VIÁTICO + DETALLE
    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'origen' => 'required',
            'destino' => 'required',
            'fecha_salida' => 'required|date',
            
        ]);

        // 🔥 GENERAR CÓDIGO
        $ultimo = Viatico::latest()->first();
        $numero = $ultimo ? $ultimo->id + 1 : 1;
        $codigo = 'VIA-' . str_pad($numero, 4, '0', STR_PAD_LEFT);

        // 🔹 ARCHIVO
        $archivo = null;
        if ($request->hasFile('archivo_firmado')) {
            $archivo = $request->file('archivo_firmado')
                ->store('viaticos', 'public');
        }

        // 🔹 CREAR VIÁTICO
        $viatico = Viatico::create([
            'codigo' => $codigo,
            'empleado_id' => $request->empleado_id,
            'movil' => $request->movil,
            'origen' => $request->origen,
            'destino' => $request->destino,
            'fecha_salida' => $request->fecha_salida,
            'fecha_regreso' => $request->fecha_regreso,
            'dias' => $request->dias ?? 1,
            'total' => 0,
            'archivo_firmado' => $archivo,
            'estado' => 'pendiente',
             'observaciones' => $request->observacion_general
        ]);

        // 🔹 DETALLES + TOTAL
        $total = 0;

        if ($request->concepto) {
            foreach ($request->concepto as $i => $concepto) {

                if ($concepto) {

                    $cantidad = $request->cantidad[$i] ?? 1;
                    $precio = $request->precio[$i] ?? 0;
                    $subtotal = $cantidad * $precio;

                    ViaticoDetalle::create([
                        'viatico_id' => $viatico->id,
                        'concepto' => $concepto,
                        'cantidad' => $cantidad,
                        'precio' => $precio,
                        'subtotal' => $subtotal,
                        'observaciones' => $request->observaciones[$i] ?? null,
                    ]);

                    $total += $subtotal;
                }
            }
        }

        $viatico->update(['total' => $total]);

        return back()->with('success', 'Viático creado correctamente');
    }

    // EXTENSIÓN (DESDE MODAL)
    public function storeExtension(Request $request, $id)
    {
        $original = Viatico::findOrFail($id);

        // contar extensiones
        $count = Viatico::where('viatico_padre_id', $original->id)->count() + 1;

        // código extensión
        $codigo = $original->codigo . '-EXT' . $count;

        // 🔹 CREAR EXTENSIÓN
        $viatico = Viatico::create([
            'codigo' => $codigo,
            'empleado_id' => $original->empleado_id,
            'viatico_padre_id' => $original->id,
            'es_extension' => true,
            'dias_extra' => $request->dias_extra,
            'dias' => $request->dias_extra,
            'movil' => $original->movil,
            'origen' => $original->origen,
            'destino' => $original->destino,
            'fecha_salida' => now(),
            'estado' => 'pendiente',
            'total' => 0
        ]);

        // 🔹 DETALLES
        $total = 0;

        if ($request->concepto) {
            foreach ($request->concepto as $i => $concepto) {

                if ($concepto) {

                    $cantidad = $request->cantidad[$i] ?? 1;
                    $precio = $request->precio[$i] ?? 0;
                    $subtotal = $cantidad * $precio;

                    ViaticoDetalle::create([
                        'viatico_id' => $viatico->id,
                        'concepto' => $concepto,
                        'cantidad' => $cantidad,
                        'precio' => $precio,
                        'subtotal' => $subtotal,
                        'observaciones' => $request->observaciones[$i] ?? null,
                    ]);

                    $total += $subtotal;
                }
            }
        }

        $viatico->update(['total' => $total]);

        return back()->with('success', 'Extensión creada correctamente');
    }

    // APROBAR
    public function aprobar($id)
    {
        $viatico = Viatico::findOrFail($id);
        $viatico->update(['estado' => 'aprobado']);

        return back()->with('success', 'Viático aprobado');
    }

    // RECHAZAR
    public function rechazar($id)
    {
        $viatico = Viatico::findOrFail($id);
        $viatico->update(['estado' => 'rechazado']);

        return back()->with('success', 'Viático rechazado');
    }

    // ELIMINAR
    public function destroy($id)
    {
        Viatico::findOrFail($id)->delete();

        return back()->with('success', 'Viático eliminado');
    }


    public function edit($id)
{
    $viatico = Viatico::with('detalles','empleado')->findOrFail($id);

    return view('documentacion.viaticos.edit', compact('viatico'));
}

public function update(Request $request, $id)
{
    $viatico = Viatico::findOrFail($id);

    $request->validate([
        'origen' => 'required',
        'destino' => 'required',
        'fecha_salida' => 'required|date',
    ]);

    // 🔹 actualizar viático
    $viatico->update([
        'movil' => $request->movil,
        'origen' => $request->origen,
        'destino' => $request->destino,
        'fecha_salida' => $request->fecha_salida,
        'fecha_regreso' => $request->fecha_regreso,
        'dias' => $request->dias ?? 1,
        'dias_extra' => $request->dias_extra,
        'observaciones' => $request->observacion_general
    ]);

    //  BORRAR DETALLES VIEJOS
    $viatico->detalles()->delete();

    // RECREAR DETALLES
    $total = 0;

    if ($request->concepto) {
        foreach ($request->concepto as $i => $concepto) {

            if ($concepto) {

                $cantidad = $request->cantidad[$i] ?? 1;
                $precio = $request->precio[$i] ?? 0;
                $subtotal = $cantidad * $precio;

                ViaticoDetalle::create([
                    'viatico_id' => $viatico->id,
                    'concepto' => $concepto,
                    'cantidad' => $cantidad,
                    'precio' => $precio,
                    'subtotal' => $subtotal,
                    'observaciones' => $request->observaciones[$i] ?? null,
                ]);

                $total += $subtotal;
            }
        }
    }

    // 🔥 actualizar total
    $viatico->update([
        'total' => $total
    ]);

    return redirect()->route('documentacion.viaticos.index')
        ->with('success', 'Actualizado correctamente');
}

public function show($id)
{
    $viatico = Viatico::with(['empleado', 'detalles'])->findOrFail($id);

    $extensiones = Viatico::with('detalles')
        ->where('viatico_padre_id', $viatico->id)
        ->get();

    // 🔥 SUMAR TOTAL GENERAL
    $totalGeneral = $viatico->total;

    foreach ($extensiones as $ext) {
        $totalGeneral += $ext->total;
    }

    return view('documentacion.viaticos.show', compact('viatico', 'extensiones', 'totalGeneral'));
}

public function json($id)
{
    $viatico = \App\Models\Viatico::with('detalles', 'empleado', 'extensiones')->findOrFail($id);

    return response()->json($viatico);
}


public function print($id)
{
    $viatico = Viatico::with(['empleado', 'detalles'])->findOrFail($id);

    return view('documentacion.viaticos.print', compact('viatico'));
}  

public function printExtension($id)
{
    $viatico = Viatico::with(['empleado', 'detalles', 'padre'])
        ->findOrFail($id);

    return view('documentacion.viaticos.print_extension', compact('viatico'));
}

public function updateExtension(Request $request, $id)
{
    $viatico = Viatico::findOrFail($id);

    // VALIDACIÓN
    $request->validate([
        'dias_extra' => 'required|numeric|min:1',
    ]);

    // 🔹 ACTUALIZAR SOLO CAMPOS DE EXTENSIÓN
    $viatico->update([
        'dias_extra' => $request->dias_extra,
        'dias' => $request->dias_extra,
        'observaciones' => $request->observacion_general,
    ]);

    //  BORRAR DETALLES VIEJOS
    $viatico->detalles()->delete();

    //  CREAR NUEVOS DETALLES
    $total = 0;

    if ($request->concepto) {
        foreach ($request->concepto as $i => $concepto) {

            if ($concepto) {

                $cantidad = $request->cantidad[$i] ?? 1;
                $precio = $request->precio[$i] ?? 0;
                $subtotal = $cantidad * $precio;

                ViaticoDetalle::create([
                    'viatico_id' => $viatico->id,
                    'concepto' => $concepto,
                    'cantidad' => $cantidad,
                    'precio' => $precio,
                    'subtotal' => $subtotal,
                    'observaciones' => $request->observaciones[$i] ?? null,
                ]);

                $total += $subtotal;
            }
        }
    }

    //  ACTUALIZAR TOTAL
    $viatico->update([
        'total' => $total
    ]);

    return back()->with('success', 'Extensión actualizada correctamente');
}


}