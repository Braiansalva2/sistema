<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovimientoEmpleadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
                Schema::create('movimientos_empleado', function (Blueprint $table) {
                $table->id();

                $table->foreignId('empleado_id')->constrained()->cascadeOnDelete();

                // 🔥 NUEVO → relación con adelanto
                $table->foreignId('adelanto_id')
                    ->nullable()
                    ->constrained('adelantos')
                    ->nullOnDelete();

                $table->enum('tipo', [
                    'hora_extra',
                    'viatico',
                    'anticipo',
                    'descuento'
                ]);

                $table->decimal('monto', 10, 2);
                $table->integer('cantidad')->nullable();

                $table->date('fecha');

                $table->text('descripcion')->nullable();

                // 🔥 NUEVO → control de cuotas
                $table->enum('estado', ['pendiente', 'pagado'])
                    ->default('pendiente');

                // 🔥 NUEVO → comprobante por cuota
                $table->string('comprobante_pago')->nullable();

                // 🔥 NUEVO → auditoría
                $table->foreignId('pagado_por')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movimiento_empleados');
    }
}
