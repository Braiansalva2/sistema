<?php

namespace App\Http\Controllers\Admin\ACL;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('admin.acl.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.acl.roles.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:roles']);
        Role::create(['name' => $request->name]);

        return redirect()->route('acl.roles.index')
            ->with('success', 'Rol creado correctamente');
    }

    public function edit(Role $role)
    {
        return view('admin.acl.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate(['name' => 'required']);
        $role->update(['name' => $request->name]);

        return redirect()->route('acl.roles.index')
            ->with('success', 'Rol actualizado correctamente');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('acl.roles.index')
            ->with('success', 'Rol eliminado');
    }
}
