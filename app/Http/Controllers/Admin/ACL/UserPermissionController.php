<?php

namespace App\Http\Controllers\Admin\ACL;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class UserPermissionController extends Controller
{
    public function index()
    {
        $usuarios = User::all();
        return view('admin.acl.usuarios.index', compact('usuarios'));
    }

    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        $roles = Role::all();
        $permisos = Permission::all();

        return view('admin.acl.usuarios.edit', compact('usuario', 'roles', 'permisos'));
    }

    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $usuario->syncRoles($request->roles ?? []);
        $usuario->syncPermissions($request->permisos ?? []);

        return redirect()->route('acl.usuarios.index')
            ->with('success', 'Roles y permisos actualizados');
    }


    public function updateRoles(Request $request, $id)
{
    $usuario = User::findOrFail($id);

    $usuario->syncRoles($request->roles ?? []);

    return back()->with('success', 'Roles actualizados correctamente.');
}

public function updatePermissions(Request $request, $id)
{
    $usuario = User::findOrFail($id);

    $permisos = $request->permisos ?? [];
    $usuario->syncPermissions($permisos);

    return back()->with('success', 'Permisos actualizados correctamente');
}


}
