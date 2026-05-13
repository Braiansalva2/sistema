<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PermisoController extends Controller
{
    public function index()
{
    $permisos = \App\Models\Permiso::with('empleado')
        ->latest()
        ->get();

    return view('rrhh.permisos.index', compact('permisos'));
}


public function aprobar(\App\Models\Permiso $permiso)
{
    $permiso->update([
        'estado' => 'aprobado',
        'fecha_aprobacion' => now(),
        'aprobado_por' => auth()->id()
    ]);

    return back()->with('success', 'Permiso aprobado');
}

public function rechazar(\App\Models\Permiso $permiso)
{
    $permiso->update([
        'estado' => 'rechazado',
        'fecha_aprobacion' => now(),
        'aprobado_por' => auth()->id()
    ]);

    return back()->with('success', 'Permiso rechazado');
}
}
