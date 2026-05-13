<?php

namespace App\Http\Controllers\Comercial;

use App\Http\Controllers\Controller;
use App\Models\Referente;
use App\Models\Empresa;
use Illuminate\Http\Request;

class ReferenteController extends Controller
{
    public function update(Request $request, Referente $referente)
    {
        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
        ]);

        $referente->update($request->only([
            'nombre',
            'apellido',
            'cargo',
            'telefono',
            'correo',
        ]));

        return back()->with('success', 'Referente actualizado');
    }

    public function destroy(Referente $referente)
    {
        $referente->delete();

        return back()->with('success', 'Referente eliminado');
    }

    public function store(Request $request, Empresa $empresa)
{
    $request->validate([
        'nombre' => 'required',
        'apellido' => 'required',
    ]);

    $empresa->referentes()->create([
        'nombre' => $request->nombre,
        'apellido' => $request->apellido,
        'cargo' => $request->cargo,
        'telefono' => $request->telefono,
        'correo' => $request->correo,
        'es_principal' => false,
    ]);

    return back()->with('success', 'Referente agregado');
    
}

}
