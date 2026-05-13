<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use App\Models\UbicacionAsistencia;


class SucursalController extends Controller
{
    public function index()
    {
        $sucursales = Sucursal::orderBy('nombre')->get();
        return view('rrhh.sucursales.index', compact('sucursales'));
    }

  public function store(Request $request)
{

    $data = $request->validate([

        'nombre'         => 'required|string|max:150',
        'codigo'         => 'nullable|string|max:20|unique:sucursales,codigo',
        'direccion'      => 'nullable|string|max:255',
        'localidad'      => 'nullable|string|max:100',
        'provincia'      => 'nullable|string|max:100',
        'telefono'       => 'nullable|string|max:30',
        'email'          => 'nullable|email|max:150',
        'estado'         => 'required|in:Activo,Inactivo',
        'observaciones'  => 'nullable|string',

        // 📍 UBICACIÓN
        'latitud'        => 'nullable',
        'longitud'       => 'nullable',
        'radio_metros'   => 'nullable|integer',

    ]);

    // 🔥 CREAR SUCURSAL
    $sucursal = Sucursal::create([

        'nombre'         => $data['nombre'],
        'codigo'         => $data['codigo'] ?? null,
        'direccion'      => $data['direccion'] ?? null,
        'localidad'      => $data['localidad'] ?? null,
        'provincia'      => $data['provincia'] ?? null,
        'telefono'       => $data['telefono'] ?? null,
        'email'          => $data['email'] ?? null,
        'estado'         => $data['estado'],
        'observaciones'  => $data['observaciones'] ?? null,

    ]);

    // 📍 CREAR UBICACIÓN
    UbicacionAsistencia::create([

        'sucursal_id' => $sucursal->id,

        'nombre' => $sucursal->nombre,

        'latitud' => $request->latitud,

        'longitud' => $request->longitud,

        'radio_metros' => $request->radio_metros ?? 300,

        'estado' => true,

    ]);

    return redirect()->route('rrhh.sucursales.index')
        ->with('success', 'Sucursal creada correctamente');
}

   public function update(Request $request, Sucursal $sucursal)
{
    $data = $request->validate([

        'nombre'         => 'required|string|max:150',

        'codigo'         => 'nullable|string|max:20|unique:sucursales,codigo,' . $sucursal->id,

        'direccion'      => 'nullable|string|max:255',

        'localidad'      => 'nullable|string|max:100',

        'provincia'      => 'nullable|string|max:100',

        'telefono'       => 'nullable|string|max:30',

        'email'          => 'nullable|email|max:150',

        'estado'         => 'required|in:Activo,Inactivo',

        'observaciones'  => 'nullable|string',

        //  GPS
        'latitud'        => 'nullable',

        'longitud'       => 'nullable',

        'radio_metros'   => 'nullable|integer',

    ]);

    //  ACTUALIZAR SUCURSAL
    $sucursal->update([

        'nombre'         => $data['nombre'],

        'codigo'         => $data['codigo'] ?? null,

        'direccion'      => $data['direccion'] ?? null,

        'localidad'      => $data['localidad'] ?? null,

        'provincia'      => $data['provincia'] ?? null,

        'telefono'       => $data['telefono'] ?? null,

        'email'          => $data['email'] ?? null,

        'estado'         => $data['estado'],

        'observaciones'  => $data['observaciones'] ?? null,

    ]);

    // 🔥 BUSCAR UBICACIÓN
    $ubicacion = UbicacionAsistencia::where(
        'sucursal_id',
        $sucursal->id
    )->first();

    // 🔥 SI EXISTE → ACTUALIZA
    if ($ubicacion) {

        $ubicacion->update([

            'nombre' => $sucursal->nombre,

            'latitud' => $request->latitud,

            'longitud' => $request->longitud,

            'radio_metros' => $request->radio_metros ?? 300,

        ]);

    } else {

        // 🔥 SI NO EXISTE → CREA
        UbicacionAsistencia::create([

            'sucursal_id' => $sucursal->id,

            'nombre' => $sucursal->nombre,

            'latitud' => $request->latitud,

            'longitud' => $request->longitud,

            'radio_metros' => $request->radio_metros ?? 300,

            'estado' => true,

        ]);
    }

    return redirect()->route('rrhh.sucursales.index')
        ->with('success', 'Sucursal actualizada correctamente');
}
 public function destroy(Sucursal $sucursal)
{
    $sucursal->estado = 'Inactivo';
    $sucursal->save(); 

    return redirect()->route('rrhh.sucursales.index')
        ->with('success', 'Sucursal dada de baja correctamente.');
}

}
