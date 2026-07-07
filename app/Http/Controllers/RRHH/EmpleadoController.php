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
    ContactoEmergencia,
    User,
    Sucursal 
};
use App\Models\GrupoFamiliar;

class EmpleadoController extends Controller
{
    /**
     * VISTA INDEX (solo devuelve la vista)
     */
    public function index()
    {
        return view('rrhh.empleados.index'); // 👈 No hace falta mandar datos
    }

    public function dashboard()
    {
        return view('rrhh.dashboard'); // 👈 No hace falta mandar datos
    }
    /**
     * DATA EN JSON PARA LAS CARDS + DATATABLE
     */
   public function data(Request $request)
{
    $porPagina = (int) $request->input('por_pagina', 12);

    // Solo permitimos valores definidos en el selector.
    $porPagina = in_array($porPagina, [8, 12, 20, 50], true)
        ? $porPagina
        : 12;

    $buscar = trim((string) $request->input('buscar', ''));


    $estado = $request->input('estado', 'Activo');

    $estado = in_array($estado, ['Activo', 'Inactivo', 'Todos'], true)
        ? $estado
        : 'Activo';

    $empleados = Empleado::query()
        ->with([
            'banco:id,nombre_banco',
            'obraSocial:id,nombre',
            'rolPuesto:id,nombre_puesto',
            'contrato:id,tipo_contrato',
        ])
        ->when($estado !== 'Todos', function ($query) use ($estado) {
                    $query->where('estado', $estado);
                })
        ->when($buscar !== '', function ($query) use ($buscar) {
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', '%' . $buscar . '%')
                    ->orWhere('apellido', 'like', '%' . $buscar . '%')
                    ->orWhere('dni', 'like', '%' . $buscar . '%')
                    ->orWhereHas('rolPuesto', function ($rolQuery) use ($buscar) {
                        $rolQuery->where('nombre_puesto', 'like', '%' . $buscar . '%');
                    })
                    ->orWhereHas('obraSocial', function ($obraQuery) use ($buscar) {
                        $obraQuery->where('nombre', 'like', '%' . $buscar . '%');
                    });
            });
        })
        ->orderBy('apellido')
        ->orderBy('nombre')
        ->paginate($porPagina);

    $empleados->getCollection()->transform(function (Empleado $e) {
        return [
            'id' => $e->id,
            'nombre' => $e->nombre,
            'apellido' => $e->apellido,
            'dni' => $e->dni,
            'rol' => optional($e->rolPuesto)->nombre_puesto ?? '-',
            'obra' => optional($e->obraSocial)->nombre ?? '-',
            'banco' => optional($e->banco)->nombre_banco ?? '-',
            'contrato' => optional($e->contrato)->tipo_contrato ?? '-',
            'estado' => $e->estado,
            'foto' => $e->foto_perfil
                ? Storage::url('fotos_empleados/' . $e->foto_perfil)
                : asset('img/default-user.png'),
        ];
    });

    return response()->json($empleados);
}


    /**
     * FORMULARIO CREAR EMPLEADO
     */
    public function create()
    {
        $bancos = Banco::orderBy('nombre_banco')->get();
        $obras = ObraSocial::orderBy('nombre')->get();
        $arts = Art::orderBy('nombre_art')->get();
        $roles = RolPuesto::orderBy('nombre_puesto')->get();
        $condiciones = CondicionLaboral::orderBy('nombre_condicion')->get();
        $contratos = Contrato::orderBy('tipo_contrato')->get();
        $sucursales = Sucursal::where('estado', 'Activo')->orderBy('nombre')->get();


        return view('rrhh.empleados.create', compact(
            'bancos', 'obras', 'arts', 'roles', 'condiciones', 'contratos', 'sucursales'
        ));
    }


    /**
     * GUARDAR NUEVO EMPLEADO
     */
   public function store(Request $request)
{
    $validated = $request->validate([
        'nombre' => 'required|string|max:100',
        'apellido' => 'required|string|max:100',
        'dni' => 'required|string|max:20|unique:empleados,dni',
        'cuil' => 'nullable|string|max:20',
        'fecha_nacimiento' => 'nullable|date',
        'fecha_ingreso' => 'nullable|date',
        'telefono' => 'nullable|string|max:30',
        'email' => 'nullable|email|max:150',
        'direccion' => 'nullable|string|max:255',
        'banco_id' => 'nullable|integer|exists:bancos,id',
        'cbu' => 'nullable|string|max:50',
        'numero_cuenta' => 'nullable|string|max:50',
        'obra_social_id' => 'nullable|integer|exists:obra_social,id',
        'rol_puesto_id' => 'nullable|integer|exists:roles_puestos,id',
        'condicion_laboral_id' => 'nullable|integer|exists:condiciones_laborales,id',
        'contrato_id' => 'nullable|integer|exists:contratos,id',
        'sucursal_id' => 'required|exists:sucursales,id',
        'foto_perfil' => 'nullable|image|max:2048',
        'sexo' => 'nullable|string|max:20',
        'estado_civil' => 'nullable|string|max:50',
        'nacionalidad' => 'nullable|string|max:100',
        'lugar_nacimiento' => 'nullable|string|max:150',
        'tipo_empleado' => 'required|in:base,chofer,roster,mixto',
    ]);

    if ($request->hasFile('foto_perfil')) {
        $path = $request->file('foto_perfil')->store('public/fotos_empleados');
        $validated['foto_perfil'] = basename($path);
    }



 $empleado = Empleado::create($validated);

// 🔹 CONTACTOS (corregido)
if ($request->has('contactos')) {
    foreach ($request->contactos as $c) {
        if (!empty($c['nombre'])) {
            ContactoEmergencia::create([
                'empleado_id' => $empleado->id, // ✅ CORREGIDO
                'nombre_contacto' => $c['nombre'],
                'parentesco' => $c['parentesco'] ?? null,
                'telefono' => $c['telefono'] ?? null,
                'domicilio' => $c['domicilio'] ?? null,
            ]);
        }
    }
}

// GRUPO FAMILIAR
$this->guardarGrupoFamiliar($request, $empleado->id);

    return redirect()->route('rrhh.empleados.index')
        ->with('success', 'Empleado registrado correctamente.');
}

/**
 * MOSTRAR PERFIL DEL EMPLEADO
 */
  public function show($id)
{
    $empleado = Empleado::with([
        'banco',
        'obraSocial',
        'rolPuesto',
        'contrato',
        'sucursal',
        'contactosEmergencia',
        'grupoFamiliar' // 🔥 NUEVO
    ])->findOrFail($id);

    return view('rrhh.empleados.show', compact('empleado'));
}


/**
 * FORMULARIO DE EDICIÓN
 */
public function edit($id)
{
    $empleado = Empleado::with([
    'grupoFamiliar',
    'contactosEmergencia',
    'banco',
    'obraSocial',
    'rolPuesto',
    'contrato',
    'sucursal'
])->findOrFail($id);

    $bancos = Banco::orderBy('nombre_banco')->get();
    $obras = ObraSocial::orderBy('nombre')->get();
    $arts = Art::orderBy('nombre_art')->get();
    $roles = RolPuesto::orderBy('nombre_puesto')->get();
    $condiciones = CondicionLaboral::orderBy('nombre_condicion')->get();
    $contratos = Contrato::orderBy('tipo_contrato')->get();
    $sucursales = Sucursal::where('estado', 'Activo')->orderBy('nombre')->get();

    return view('rrhh.empleados.edit', compact(
        'empleado', 'bancos', 'obras', 'arts', 'roles', 'condiciones', 'contratos', 'sucursales'
    ));
}


/**
 * ACTUALIZAR EMPLEADO
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
        'sucursal_id' => 'required|exists:sucursales,id',
        'cbu' => 'nullable|string|max:50',
        'numero_cuenta' => 'nullable|string|max:50',
        'obra_social_id' => 'nullable|integer|exists:obra_social,id',
        'rol_puesto_id' => 'nullable|integer|exists:roles_puestos,id',
        'condicion_laboral_id' => 'nullable|integer|exists:condiciones_laborales,id',
        'contrato_id' => 'nullable|integer|exists:contratos,id',
        'foto_perfil' => 'nullable|image|max:2048',
        'fecha_ingreso' => 'nullable|date',
        'sexo' => 'nullable|string|max:20',
        'estado_civil' => 'nullable|string|max:50',
        'nacionalidad' => 'nullable|string|max:100',
        'lugar_nacimiento' => 'nullable|string|max:150',
        'tipo_empleado' => 'required|in:base,chofer,roster,mixto',

    ]);

    if ($request->hasFile('foto_perfil')) {

        if ($empleado->foto_perfil && Storage::exists('public/fotos_empleados/' . $empleado->foto_perfil)) {
            Storage::delete('public/fotos_empleados/' . $empleado->foto_perfil);
        }

        $path = $request->file('foto_perfil')->store('public/fotos_empleados');
        $validated['foto_perfil'] = basename($path);
    }

    $empleado->update($validated);
// 🔹 Borrar contactos actuales
ContactoEmergencia::where('empleado_id', $empleado->id)->delete();

// 🔹 Crear los nuevos contactos enviados
if ($request->has('contactos')) {
    foreach ($request->contactos as $c) {
        if (!empty($c['nombre'])) {
            ContactoEmergencia::create([
                'empleado_id' => $empleado->id,
                'nombre_contacto' => $c['nombre'],
                'parentesco' => $c['parentesco'] ?? null,
                'telefono' => $c['telefono'] ?? null,
                'domicilio' => $c['domicilio'] ?? null,
            ]);
        }
    }
}

$this->actualizarGrupoFamiliar($request, $empleado->id);


    return redirect()->route('rrhh.empleados.show', $empleado->id)
        ->with('success', 'Datos actualizados correctamente.');
}

    /**
     * DAR DE BAJA
     */
    public function destroy($id)
    {
        $empleado = Empleado::findOrFail($id);

        $empleado->estado = 'Inactivo';
        $empleado->save();

        if ($empleado->user_id && $empleado->user) {
            $empleado->user->active = 0;
            $empleado->user->save();
        }

        return redirect()->route('rrhh.empleados.index')
            ->with('success', 'Empleado dado de baja correctamente.');
    }


    /**
     * REACTIVAR
     */
    public function reactivar($id)
    {
        $empleado = Empleado::findOrFail($id);

        $empleado->estado = 'Activo';
        $empleado->save();  

        if ($empleado->user_id && $empleado->user) {
            $empleado->user->active = 1;
            $empleado->user->save();
        }

        return redirect()->route('rrhh.empleados.index')
            ->with('success', 'Empleado reactivado correctamente.');
    }


    public function createUser(Empleado $empleado)
{
    if ($empleado->user_id) {
        return redirect()->route('rrhh.empleados.show', $empleado)
            ->with('error', 'Este empleado ya tiene un usuario asignado.');
    }

    $roles = \Spatie\Permission\Models\Role::pluck('name', 'id');

    return view('rrhh.empleados.create-user', compact('empleado', 'roles'));
}


public function storeUser(Request $request, Empleado $empleado)
{
    if ($empleado->user_id) {
        return back()->with('error', 'El empleado ya tiene un usuario.');
    }

    $data = $request->validate([
        'email' => 'required|string|max:20|unique:users,email',
        'password' => 'required|min:6|confirmed',
        'role' => 'required|exists:roles,name',
    ]);

    // Crear usuario del sistema
    $user = \App\Models\User::create([
        'name' => $empleado->nombre . ' ' . $empleado->apellido,
        'email' => $data['email'],
        'password' => bcrypt($data['password']),
    ]);

    // Asignar rol con Spatie
    $user->assignRole($data['role']);

    // aqui relacionamos al usuario con el rol
    $empleado->update(['user_id' => $user->id]);

    return redirect()->route('rrhh.empleados.show', $empleado)
        ->with('success', 'Usuario creado correctamente.');
}
  public function deshabilitarUsuario(User $user)
{
    $user->active = 0;
    $user->save();

    return back()->with('success', 'Usuario deshabilitado.');
}

public function habilitarUsuario(User $user)
{
    $user->active = 1;
    $user->save();

    return back()->with('success', 'Usuario habilitado.');
}

public function cambiarPassword(Request $request, User $user)
{
    $data = $request->validate([
        'password' => 'required|min:6|confirmed',
    ]);

    $user->password = bcrypt($data['password']);
    $user->save();
  
    return back()->with('success', 'Contraseña actualizada correctamente.');
}

public static function generarPassword($longitud = 12)
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?';
    return substr(str_shuffle($chars), 0, $longitud);
}



public function panelUsuarios()
{
      $usuarios = User::with(['empleado', 'roles'])->get();
    $roles = \Spatie\Permission\Models\Role::pluck('name', 'id');

    return view('rrhh.usuarios.index', compact('usuarios', 'roles'));
}
public function toggleEstado(User $user)
{
    $user->active = !$user->active;
    $user->save();

    return back()->with('success', 'Estado actualizado correctamente.');
}
public function editUsuario(User $user)
{
    $roles = \Spatie\Permission\Models\Role::pluck('name', 'id');

    return view('rrhh.usuarios.edit', compact('user', 'roles'));
}
public function updateUsuario(Request $request, User $user)
{
    $data = $request->validate([
        'email' => 'required|email|unique:users,email,' . $user->id,
        'role' => 'required|exists:roles,name',
    ]);

    $user->email = $data['email'];
    $user->save();

    // quitar roles actuales y asignar el nuevo
    $user->syncRoles([$data['role']]);

    return redirect()->route('rrhh.usuarios.index')->with('success', 'Usuario actualizado correctamente.');
}
public function destroyUsuario(User $user)
{
    // Desvincular del empleado si tiene
    if ($user->empleado) {
        $user->empleado->update(['user_id' => null]);
    }

    $user->delete();

    return back()->with('success', 'Usuario eliminado correctamente.');
}

// ================grupo familiar=======

private function guardarGrupoFamiliar($request, $empleado_id)
{
    if ($request->has('familiares')) {

        foreach ($request->familiares as $familiar) {

            if (!empty($familiar['nombre'])) {

                // 🔥 lógica de "Otro"
                $parentesco = $familiar['parentesco'];

                if ($parentesco === 'Otro' && !empty($familiar['parentesco_otro'])) {
                    $parentesco = $familiar['parentesco_otro'];
                }

                \App\Models\GrupoFamiliar::create([
                    'empleado_id' => $empleado_id,
                    'nombre' => $familiar['nombre'],
                    'apellido' => $familiar['apellido'],
                    'parentesco' => $parentesco,
                    'a_cargo' => isset($familiar['a_cargo']),
                    'fecha_nacimiento' => $familiar['fecha_nacimiento'] ?? null,
                    'dni' => $familiar['dni'] ?? null,
                ]);
            }
        }
    }
}

private function actualizarGrupoFamiliar($request, $empleado_id)
{
    if ($request->has('familiares')) {

        $idsEnviados = [];

        foreach ($request->familiares as $familiar) {

            if (!empty($familiar['nombre'])) {

                // lógica de "Otro"
                $parentesco = $familiar['parentesco'];

                if ($parentesco === 'Otro' && !empty($familiar['parentesco_otro'])) {
                    $parentesco = $familiar['parentesco_otro'];
                }

                if (isset($familiar['id'])) {

                    // UPDATE
                    \App\Models\GrupoFamiliar::where('id', $familiar['id'])->update([
                        'nombre' => $familiar['nombre'],
                        'apellido' => $familiar['apellido'],
                        'parentesco' => $parentesco,
                        'a_cargo' => isset($familiar['a_cargo']),
                        'fecha_nacimiento' => $familiar['fecha_nacimiento'] ?? null,
                        'dni' => $familiar['dni'] ?? null,
                    ]);

                    $idsEnviados[] = $familiar['id'];

                } else {

                    // CREATE NUEVO
                    $nuevo = \App\Models\GrupoFamiliar::create([
                        'empleado_id' => $empleado_id,
                        'nombre' => $familiar['nombre'],
                        'apellido' => $familiar['apellido'],
                        'parentesco' => $parentesco,
                        'a_cargo' => isset($familiar['a_cargo']),
                        'fecha_nacimiento' => $familiar['fecha_nacimiento'] ?? null,
                        'dni' => $familiar['dni'] ?? null,
                    ]);

                    $idsEnviados[] = $nuevo->id;
                }
            }
        }

        // ELIMINAR LOS QUE NO VIENEN
        \App\Models\GrupoFamiliar::where('empleado_id', $empleado_id)
            ->whereNotIn('id', $idsEnviados)
            ->delete();
    }
}



// imprimir ddjj
public function ddjj($id)
{
    $empleado = Empleado::with(['grupoFamiliar'])
        ->findOrFail($id);

    return view('rrhh.empleados.ddjj', compact('empleado'));
}
}
