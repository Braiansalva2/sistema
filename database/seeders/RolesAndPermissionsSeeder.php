<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Roles base
        $roles = [
            'admin',
            'rrhh',
            'comercial',
            'trafico',
            'operaciones',
            'contable',
            'proveedores',
            'cobranza',
            'salud',
            'hys',
            'documentacion',
            'pañol',
            'sistemas',
            'calidad',
            'empleado',
            'mecanico_pesado',
            'mecanico_liviano',
            'project_manager',
            'gerente_general', 
            'chofer'
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'web',
            ]);
        }

        // ==============================
        // PERMISOS GENERALES
        // ==============================
        $permisos = [
            // RRHH / empleados
            'ver empleados',
            'crear empleados',
            'editar empleados',
            'eliminar empleados',

            // Vacaciones
            'ver vacaciones',
            'solicitar vacaciones',
            'aprobar vacaciones',
            'rechazar vacaciones',

            // Adelantos
            'ver adelantos',
            'solicitar adelantos',
            'aprobar adelantos',
            'rechazar adelantos',

            // Asistencia
            'ver asistencias',
            'registrar asistencia',
            'gestionar asistencias',

            // Perfil empleado
            'ver mi perfil',
            'editar mi perfil',

            // Sistemas
            'ver panel sistemas',
            'gestionar usuarios',
            'gestionar roles',
            'gestionar permisos',

            // Calidad
            'ver panel calidad',
            'ver auditorias',
            'gestionar auditorias',
            
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate([
                'name' => $permiso,
                'guard_name' => 'web',
            ]);
        }

        // ==============================
        // ROLES Y PERMISOS
        // ==============================

        // Admin
        $admin = Role::findByName('admin', 'web');
        $admin->givePermissionTo(Permission::all());

        // RRHH
        $rrhh = Role::findByName('rrhh', 'web');
        $rrhh->givePermissionTo([
            'ver empleados',
            'crear empleados',
            'editar empleados',
            'eliminar empleados',
            'ver vacaciones',
            'aprobar vacaciones',
            'rechazar vacaciones',
            'ver adelantos',
            'aprobar adelantos',
            'rechazar adelantos',
            'ver asistencias',
            'gestionar asistencias',
        ]);

        // EMPLEADO
        $empleado = Role::findByName('empleado', 'web');
        $empleado->givePermissionTo([
            'ver mi perfil',
            'editar mi perfil',
            'ver vacaciones',
            'solicitar vacaciones',
            'ver adelantos',
            'solicitar adelantos',
            'ver asistencias',
            'registrar asistencia',
        ]);

        // SISTEMAS
        $sistemas = Role::findByName('sistemas', 'web');
        $sistemas->givePermissionTo([
            'ver empleados',
            'editar empleados',
            'ver panel sistemas',
            'gestionar usuarios',
            'gestionar roles',
            'gestionar permisos',
        ]);

        // CALIDAD
        $calidad = Role::findByName('calidad', 'web');
        $calidad->givePermissionTo([
            'ver empleados',
            'ver panel calidad',
            'ver auditorias',
            'gestionar auditorias',
        ]);
    }
}