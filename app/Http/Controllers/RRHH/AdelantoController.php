<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Models\Adelanto;
use App\Models\Empleado;
use App\Models\MovimientoEmpleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdelantoController extends Controller
{
    public function index()
    {
        $adelantos = Adelanto::with(['empleado', 'aprobadoPor', 'pagadoPor', 'movimientos'])
            ->latest()
            ->get();

        return view('rrhh.adelantos.index', compact('adelantos'));
    }

    public function show($id)
    {
        $adelanto = Adelanto::with(['empleado', 'aprobadoPor', 'pagadoPor', 'movimientos'])
            ->findOrFail($id);

        return view('rrhh.adelantos.show', compact('adelanto'));
    }

    public function aprobar($id)
    {
        $adelanto = Adelanto::findOrFail($id);

        $adelanto->update([
            'estado' => 'aprobado',
            'aprobado_por' => Auth::id(),
            'fecha_aprobacion' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'El adelanto fue aprobado correctamente.');
    }

    public function rechazar($id)
    {
        $adelanto = Adelanto::findOrFail($id);

        $adelanto->update([
            'estado' => 'rechazado',
        ]);

        return redirect()
            ->back()
            ->with('success', 'El adelanto fue rechazado correctamente.');
    }

    public function pagar(Request $request, $id)
    {
        $request->validate([
            'fecha_pago' => 'required|date',
            'metodo_pago' => 'required|string|max:100',
            'comprobante_pago' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:4096',
        ]);

        $adelanto = Adelanto::with('movimientos')->findOrFail($id);

        $comprobantePath = null;

        if ($request->hasFile('comprobante_pago')) {
            $comprobantePath = $request->file('comprobante_pago')->store('adelantos/comprobantes', 'public');
        }

        $adelanto->update([
            'estado' => 'pagado',
            'fecha_pago' => $request->fecha_pago,
            'metodo_pago' => $request->metodo_pago,
            'comprobante_pago' => $comprobantePath,
            'pagado_por' => Auth::id(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'El pago del adelanto fue registrado correctamente.');
    }

    public function imprimir($id)
{
    $adelanto = Adelanto::with([
        'empleado',
        'movimientos',
        'aprobadoPor'
    ])->findOrFail($id);

    return view(
        'rrhh.adelantos.imprimir',
        compact('adelanto')
    );
}
}