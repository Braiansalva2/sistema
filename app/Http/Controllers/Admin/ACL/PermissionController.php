<?php

namespace App\Http\Controllers\Admin\ACL;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        // Solo permisos web (evita errores)
        $permisos = Permission::where('guard_name', 'web')->get();
        return view('admin.acl.permisos.index', compact('permisos'));
    }

    public function create()
    {
        return view('admin.acl.permisos.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:permissions']);

        Permission::create([
            'name' => $request->name,
            'guard_name' => 'web'  
        ]);

        return redirect()->route('acl.permisos.index')
            ->with('success', 'Permiso creado correctamente');
    }

    public function edit(Permission $permiso)
    {
        return view('admin.acl.permisos.edit', compact('permiso'));
    }

    public function update(Request $request, Permission $permiso)
    {
        $request->validate(['name' => 'required']);

        $permiso->update([
            'name' => $request->name,
            'guard_name' => 'web' 
        ]);

        return redirect()->route('acl.permisos.index')
            ->with('success', 'Permiso actualizado');
    }

    public function destroy(Permission $permiso)
    {
        $permiso->delete();
        return redirect()->route('acl.permisos.index')
            ->with('success', 'Permiso eliminado');
    }
}
