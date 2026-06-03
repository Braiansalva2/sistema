<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TipoPrenda;
use App\Models\Empleado;

class TipoPrendaController extends Controller
{
    public function index()
    {
        $prendas = TipoPrenda::with('talles')
            ->latest()
            ->paginate(10);

        $empleados = Empleado::where('estado', 'Activo')
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->get();

        return view('rrhh.indumentaria.index', compact('prendas', 'empleados'));
    }

    public function store(Request $request)
    {
        TipoPrenda::create([
            'nombre' => $request->nombre,
            'estado' => true
        ]);

        return back()->with('success', 'Prenda creada');
    }

    public function update(Request $request, $id)
    {
        $prenda = TipoPrenda::findOrFail($id);

        $prenda->update([
            'nombre' => $request->nombre,
            'estado' => $request->estado
        ]);

        return back()->with('success', 'Prenda actualizada');
    }

    public function exportarEmpleados(Request $request)
    {
        $tipoExportacion = $request->tipo_exportacion;

        $prendas = TipoPrenda::where('estado', true)
            ->orderBy('nombre')
            ->get();

        $query = Empleado::with([
                'rolPuesto',
                'talles.tipoPrenda',
                'talles.talle'
            ])
            ->orderBy('apellido')
            ->orderBy('nombre');

        if ($tipoExportacion === 'seleccionados') {
            $request->validate([
                'empleado_ids' => 'required|array',
                'empleado_ids.*' => 'exists:empleados,id',
            ]);

            $query->whereIn('id', $request->empleado_ids);
        }

        $empleados = $query->get();

        return response()->streamDownload(function () use ($empleados, $prendas) {

            $handle = fopen('php://output', 'w');

            // BOM UTF-8 para que Excel lea bien acentos y ñ
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            $header = [
                'Empleado',
                'DNI',
                'Puesto',
            ];

            foreach ($prendas as $prenda) {
                $header[] = $prenda->nombre;
            }

            fputcsv($handle, $header, ';');

            foreach ($empleados as $empleado) {

                $fila = [
                    $empleado->apellido . ' ' . $empleado->nombre,
                    $empleado->dni,
                    optional($empleado->rolPuesto)->nombre ?? 'Sin puesto',
                ];

                foreach ($prendas as $prenda) {

                    $talleEmpleado = $empleado->talles
                        ->where('tipo_prenda_id', $prenda->id)
                        ->first();

                    $fila[] = $talleEmpleado && $talleEmpleado->talle
                        ? $talleEmpleado->talle->nombre
                        : 'Sin talle';
                }

                fputcsv($handle, $fila, ';');
            }

            fclose($handle);

        }, 'talles_empleados_' . now()->format('Y_m_d_H_i') . '.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}