<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdelantosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
                    {
                Schema::create('adelantos', function (Blueprint $table) {
                    $table->id();

                    $table->foreignId('empleado_id')->constrained()->cascadeOnDelete();

                    // Datos
                    $table->decimal('monto_total', 10, 2);
                    $table->integer('cuotas_total');
                    $table->text('motivo')->nullable();
             
                    // Estado
                    $table->enum('estado', ['pendiente', 'aprobado', 'rechazado', 'pagado'])
                        ->default('pendiente');

                    // Fechas
                    $table->date('fecha_solicitud');
                    $table->timestamp('fecha_aprobacion')->nullable();

                    // Auditoría
                    $table->foreignId('aprobado_por')->nullable()->constrained('users')->nullOnDelete();

                    // Pago
                    $table->date('fecha_pago')->nullable();
                    $table->string('metodo_pago')->nullable();
                    $table->string('comprobante_pago')->nullable();
                    $table->string('nro_transferencia')->nullable();
                    $table->foreignId('pagado_por')->nullable()->constrained('users')->nullOnDelete();

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
        Schema::dropIfExists('adelantos');
    }
}
