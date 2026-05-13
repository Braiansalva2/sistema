<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLicenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('licencias', function (Blueprint $table) {
            $table->id();

            // RELACIÓN
            $table->foreignId('empleado_id')->constrained()->cascadeOnDelete();

            // TIPO DE LICENCIA
            $table->string('tipo'); // más flexible que ENUM

            // FECHAS
            $table->date('fecha_desde');
            $table->date('fecha_hasta')->nullable();

            // HORAS (para licencias ordinarias)
            $table->time('hora_desde')->nullable();
            $table->time('hora_hasta')->nullable();

            // CANTIDADES
            $table->integer('dias')->nullable();
            $table->integer('horas')->nullable();

            // ARCHIVO (certificados, etc)
            $table->string('archivo')->nullable();

            // OBSERVACIONES
            $table->text('observaciones')->nullable();

            // ESTADO
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada', 'finalizada'])
                  ->default('pendiente');

            // APROBACIÓN
            $table->foreignId('aprobado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->date('fecha_aprobacion')->nullable();

            
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
        Schema::dropIfExists('licencias');
    }
}
