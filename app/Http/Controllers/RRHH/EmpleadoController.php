<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\{
    Empleado,
    Banco,
    ObraSocial,
    Art,
    RolPuesto,
    CondicionLaboral,
    Contrato,
    ContactoEmergencia
};

class EmpleadoController extends Controller
{
    /**
     * Mostrar listado de empleados
     */
    public function index()
    {
           $empleados = Empleado::with(['banco', 'obraSocial', 'contrato'])->get();
    return view('rrhh.empleados.index', compact('empleados'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $bancos = Banco::orderBy('nombre_banco')->get();
        $obras = ObraSocial::orderBy('nombre')->get();
        $arts = Art::orderBy('nombre_art')->get();
        $roles = RolPuesto::orderBy('nombre_puesto')->get();
        $condiciones = CondicionLaboral::orderBy('nombre_condicion')->get();
        $contratos = Contrato::orderBy('tipo_contrato')->get();

        return view('rrhh.empleados.create', compact(
            'bancos', 'obras', 'arts', 'roles', 'condiciones', 'contratos'
        ));
    }

    /**
     * Guardar nuevo empleado
     */
    public function store(Request $request)
    {
        // ✅ Validaciones básicas
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'dni' => 'required|string|max:20|unique:empleados,dni',
            'cuil' => 'nullable|string|max:20',
            'fecha_nacimiento' => 'nullable|date',
            'telefono' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:150',
            'direccion' => 'nullable|string|max:255',
            'banco_id' => 'nullable|integer|exists:bancos,id',
            'cbu' => 'nullable|string|max:50',
            'obra_social_id' => 'nullable|integer|exists:obra_social,id',
            'condicion_laboral_id' => 'nullable|integer|exists:condiciones_laborales,id',
            'contrato_id' => 'nullable|integer|exists:contratos,id',
            'foto_perfil' => 'nullable|image|max:2048',
        ]);

        // ✅ Guardar foto de perfil (si existe)
        if ($request->hasFile('foto_perfil')) {
            $path = $request->file('foto_perfil')->store('public/fotos_empleados');
            $validated['foto_perfil'] = basename($path);
        }

        // ✅ Crear empleado
        $empleado = Empleado::create($validated);

        // ✅ Guardar contactos de emergencia
        if ($request->has('contactos')) {
            foreach ($request->contactos as $contacto) {
                if (!empty($contacto['nombre'])) {
                    ContactoEmergencia::create([
                        'empleado_id' => $empleado->id,
                        'nombre_contacto' => $contacto['nombre'],
                        'parentesco' => $contacto['parentesco'] ?? null,
                        'telefono' => $contacto['telefono'] ?? null,
                        'domicilio' => $contacto['domicilio'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado registrado correctamente.');
    }

    /**
     * Mostrar perfil del empleado
     */
    public function show($id)
    {
        $empleado = Empleado::with(['banco', 'obraSocial', 'contactosEmergencia'])->findOrFail($id);
        return view('rrhh.empleados.show', compact('empleado'));
    }

    /**
     * Editar empleado
     */
    public function edit($id)
    {
        $empleado = Empleado::findOrFail($id);

        $bancos = Banco::orderBy('nombre_banco')->get();
        $obras = ObraSocial::orderBy('nombre')->get();
        $arts = Art::orderBy('nombre_art')->get();
        $roles = RolPuesto::orderBy('nombre_puesto')->get();
        $condiciones = CondicionLaboral::orderBy('nombre_condicion')->get();
        $contratos = Contrato::orderBy('tipo_contrato')->get();

        return view('rrhh.empleados.edit', compact(
            'empleado', 'bancos', 'obras', 'arts', 'roles', 'condiciones', 'contratos'
        ));
    }

    /**
     * Actualizar empleado
     */
    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'dni' => 'required|string|max:20|unique:empleados,dni,' . $empleado->id,
            'cuil' => 'nullable|string|max:20',
            'fecha_nacimiento' => 'nullable|date',
            'telefono' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:150',
            'direccion' => 'nullable|string|max:255',
            'banco_id' => 'nullable|integer|exists:bancos,id',
            'cbu' => 'nullable|string|max:50',
            'obra_social_id' => 'nullable|integer|exists:obra_social,id',
            'condicion_laboral_id' => 'nullable|integer|exists:condiciones_laborales,id',
            'contrato_id' => 'nullable|integer|exists:contratos,id',
            'foto_perfil' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto_perfil')) {
            // eliminar foto anterior
            if ($empleado->foto_perfil && Storage::exists('public/fotos_empleados/' . $empleado->foto_perfil)) {
                Storage::delete('public/fotos_empleados/' . $empleado->foto_perfil);
            }

            $path = $request->file('foto_perfil')->store('public/fotos_empleados');
            $validated['foto_perfil'] = basename($path);
        }

        $empleado->update($validated);

        return redirect()->route('empleados.show', $empleado->id)
            ->with('success', 'Datos del empleado actualizados correctamente.');
    }

    /**
     * Eliminar empleado
     */
    public function destroy($id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->delete();

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado eliminado correctamente.');
    }
}
