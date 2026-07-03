<?php

namespace App\Console\Commands;

use App\Models\Empleado;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CrearUsuariosEmpleados extends Command
{
    /**
     * Nombre del comando.
     */
    protected $signature = 'empleados:crear-usuarios {--dry-run : Simula la creación sin guardar cambios}';

    /**
     * Descripción.
     */
    protected $description = 'Crea automáticamente usuarios para empleados usando CUIL como usuario y DNI como contraseña.';

    public function handle()
    {
        $modoSimulacion = $this->option('dry-run');

        $this->info('');
        $this->info('===========================================');
        $this->info($modoSimulacion
            ? ' SIMULACIÓN DE CREACIÓN DE USUARIOS'
            : ' CREACIÓN DE USUARIOS');
        $this->info('===========================================');
        $this->newLine();

        $total = 0;
        $creados = 0;
        $yaTienenUsuario = 0;
        $sinCuil = 0;
        $sinDni = 0;
        $usuarioExistente = 0;
        $errores = 0;

        $empleados = Empleado::orderBy('apellido')
            ->orderBy('nombre')
            ->get();

        foreach ($empleados as $empleado) {

            $total++;

            $nombreCompleto = trim($empleado->nombre . ' ' . $empleado->apellido);

            // Ya tiene usuario asignado
            if (!empty($empleado->user_id)) {

                $yaTienenUsuario++;

                $this->line("↪ {$nombreCompleto} | Ya tiene usuario");

                continue;
            }

            // Sin CUIL
            if (empty($empleado->cuil)) {

                $sinCuil++;

                $this->warn("⚠ {$nombreCompleto} | Sin CUIL");

                continue;
            }

            // Sin DNI
            if (empty($empleado->dni)) {

                $sinDni++;

                $this->warn("⚠ {$nombreCompleto} | Sin DNI");

                continue;
            }

            // Ya existe usuario con ese CUIL
            if (User::where('email', $empleado->cuil)->exists()) {

                $usuarioExistente++;

                $this->warn("⚠ {$nombreCompleto} | Ya existe un usuario con ese CUIL");

                continue;
            }

            // SIMULACIÓN
            if ($modoSimulacion) {

                $creados++;

                $this->info("✔ {$nombreCompleto}");
                $this->line("   Usuario: {$empleado->cuil}");
                $this->line("   Contraseña: {$empleado->dni}");
                $this->newLine();

                continue;
            }

            // CREACIÓN REAL
            try {

                $user = User::create([
                    'name'     => $nombreCompleto,
                    'email'    => $empleado->cuil,
                    'password' => Hash::make($empleado->dni),
                    'active'   => 1,
                ]);

                $user->assignRole('empleado');

                $empleado->update([
                    'user_id' => $user->id
                ]);

                $creados++;

                $this->info("✔ Usuario creado: {$nombreCompleto}");

            } catch (\Throwable $e) {

                $errores++;

                $this->error("✘ Error con {$nombreCompleto}");
                $this->line($e->getMessage());
            }
        }

        $this->newLine();

        $this->info('===========================================');
        $this->info('RESUMEN');
        $this->info('===========================================');

        $this->table(
            ['Concepto', 'Cantidad'],
            [
                ['Total empleados', $total],
                [$modoSimulacion ? 'Se crearían' : 'Usuarios creados', $creados],
                ['Ya tienen usuario', $yaTienenUsuario],
                ['Sin CUIL', $sinCuil],
                ['Sin DNI', $sinDni],
                ['Usuario existente', $usuarioExistente],
                ['Errores', $errores],
            ]
        );

        if ($modoSimulacion) {
            $this->warn('SIMULACIÓN FINALIZADA. No se creó ningún usuario.');
        } else {
            $this->info('PROCESO FINALIZADO CORRECTAMENTE.');
        }

        return Command::SUCCESS;
    }
}