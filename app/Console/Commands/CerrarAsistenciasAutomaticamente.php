<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Empleado;
use App\Models\MovimientoAsistencia;

class CerrarAsistenciasAutomaticamente extends Command
{
    protected $signature = 'asistencias:cerrar-automaticamente';
    protected $description = 'Cierra automáticamente asistencias sin salida';

    public function handle()
    {
        $empleados = Empleado::whereIn('tipo_empleado', [
            'base',
            'chofer',
            'mixto'
        ])->get();

        foreach ($empleados as $empleado) {

            $ultimo = MovimientoAsistencia::where('empleado_id', $empleado->id)
                ->orderBy('fecha_hora', 'desc')
                ->first();

            // Si el último movimiento es entrada, se genera salida automática
            if ($ultimo && $ultimo->tipo === 'entrada') {

                MovimientoAsistencia::create([
                    'empleado_id' => $empleado->id,
                    'tipo' => 'salida',
                    'fecha_hora' => now()->setTime(23, 59, 59),
                    'automatico' => true,
                    'con_ubicacion' => false,
                    'estado_gps' => 'error',
                    'observaciones' => 'Salida generada automáticamente por cierre diario.',
                ]);

                $this->info("Salida automática creada para {$empleado->nombre} {$empleado->apellido}");
            }
        }

        return Command::SUCCESS;
    }
}