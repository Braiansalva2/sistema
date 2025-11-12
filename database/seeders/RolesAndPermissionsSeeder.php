<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
    // Roles base
    $roles = [
        'admin',
        'rrhh',
        'comercial',
        'operaciones',
        'contable',
        'proveedores',
        'cobranza',
        'salud',
        'hys',
        'documentacion',
        'pañol',
        'mecanico_pesado',
        'mecanico_liviano',
        'project_manager',
        'gerente_general',
    ];

    foreach ($roles as $role) {
        Role::firstOrCreate(['name' => $role]);
    }

    // Ejemplo permisos RRHH
    Permission::firstOrCreate(['name' => 'ver empleados']);
    Permission::firstOrCreate(['name' => 'crear empleados']);
    Permission::firstOrCreate(['name' => 'editar empleados']);
    Permission::firstOrCreate(['name' => 'eliminar empleados']);

    $rrhh = Role::findByName('rrhh');
    $rrhh->givePermissionTo(['ver empleados', 'crear empleados', 'editar empleados', 'eliminar empleados']);
    }
}
