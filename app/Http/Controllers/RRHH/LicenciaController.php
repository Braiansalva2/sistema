<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Models\Licencia;
use Illuminate\Http\Request;

class LicenciaController extends Controller
{
    public function index()
{
    $licencias = Licencia::with('empleado')
        ->orderBy('created_at', 'desc')
        ->paginate(6); 

    return view('rrhh.licencias.index', compact('licencias'));
}

    public function aprobar($id)
    {
        $licencia = Licencia::findOrFail($id);

        $licencia->update([
            'estado' => 'aprobada',
            'aprobado_por' => auth()->id(),
            'fecha_aprobacion' => now()
        ]);

        return back()->with('success', 'Licencia aprobada');
    }

    public function rechazar($id)
    {
        $licencia = Licencia::findOrFail($id);

        $licencia->update([
            'estado' => 'rechazada',
            'aprobado_por' => auth()->id(),
            'fecha_aprobacion' => now()
        ]);

        return back()->with('success', 'Licencia rechazada');
    }
}