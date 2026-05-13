<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViaticosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('viaticos', function (Blueprint $table) {
        $table->id();
        $table->string('codigo')->unique();
        $table->foreignId('empleado_id')->constrained()->cascadeOnDelete();

        // Datos del viaje
    
        $table->string('movil')->nullable();

        $table->string('origen');
        $table->string('destino');

        $table->date('fecha_salida');
        $table->date('fecha_regreso')->nullable();

        $table->integer('dias')->default(1);

        $table->enum('estado', ['pendiente','aprobado','rechazado'])->default('pendiente');

        // Total calculado
        $table->decimal('total', 10, 2)->default(0);

        $table->text('observaciones')->nullable();


        // Archivo firmado
        $table->string('archivo_firmado')->nullable();

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
        Schema::dropIfExists('viaticos');
    }
}
