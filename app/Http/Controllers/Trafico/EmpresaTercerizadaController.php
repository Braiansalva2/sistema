<?php

namespace App\Http\Controllers\Trafico;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmpresaTercerizada;

class EmpresaTercerizadaController extends Controller
{
    /**
     * Guardar una nueva empresa tercerizada (desde modal, vía AJAX).
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'     => 'required|string|max:255|unique:empresas_tercerizadas,nombre',
            'cuit'       => 'nullable|string|max:50',
            'telefono'   => 'nullable|string|max:50',
            'correo'     => 'nullable|email|max:255',
            'responsable'=> 'nullable|string|max:255',
        ]);

        $empresa = EmpresaTercerizada::create([
            'nombre'      => $request->nombre,
            'cuit'        => $request->cuit,
            'telefono'    => $request->telefono,
            'correo'      => $request->correo,
            'responsable' => $request->responsable,
        ]);

        // Devolvemos JSON para que el JS la agregue al <select>
        return response()->json([
            'success' => true,
            'empresa' => $empresa,
        ]);
    }
}
