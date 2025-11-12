<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecutamos el seeder de roles primero
        $this->call(RolesAndPermissionsSeeder::class);

        // Crear usuario ADMIN
        $admin = User::firstOrCreate(
            ['email' => 'admin@gvh.com'],
            [
                'name' => 'Administrador General',
                'password' => Hash::make('admin123'),
            ]
        );
        $admin->assignRole('admin');

        // Crear usuario RRHH
        $rrhh = User::firstOrCreate(
            ['email' => 'rrhh@gvh.com'],
            [
                'name' => 'Recursos Humanos',
                'password' => Hash::make('rrhh123'),
            ]
        );
        $rrhh->assignRole('rrhh');

        $this->command->info('Usuarios base creados correctamente: admin@gvh.com y rrhh@gvh.com');
    }
}
