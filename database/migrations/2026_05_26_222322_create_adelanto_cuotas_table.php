<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdelantoCuotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('adelanto_cuotas', function (Blueprint $table) {

            $table->id();

            $table->foreignId('adelanto_id')
                ->constrained()
                ->cascadeOnDelete();

            // cuota
            $table->integer('numero_cuota');

            // monto cuota
            $table->decimal('monto', 10, 2);

            // vencimiento
            $table->date('fecha_vencimiento')->nullable();

            // pago real
            $table->date('fecha_pago')->nullable();

            // estado cuota
            $table->enum('estado', [
                'pendiente',
                'pagada'
            ])->default('pendiente');

            // observaciones
            $table->text('observaciones')->nullable();

            // usuario RRHH/finanzas
            $table->foreignId('registrado_por')
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
        Schema::dropIfExists('adelanto_cuotas');
    }
}
