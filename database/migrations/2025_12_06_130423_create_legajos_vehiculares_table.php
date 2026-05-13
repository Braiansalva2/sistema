<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLegajosVehicularesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
{
    Schema::create('legajos_vehiculares', function (Blueprint $table) {
        $table->id();

        // La unidad a la que pertenece este documento
        $table->foreignId('unidad_id')
              ->constrained('unidades')
              ->onDelete('cascade'); // Si se elimina la unidad, se elimina su documentación

        // Tipo de documento (libre y escalable)
        // Ej: 'seguro', 'vtv', 'cedula_verde', 'mantenimiento', etc.
        $table->string('tipo_documento');

        // Información general del documento
        $table->string('descripcion')->nullable();

        // Fechas útiles para vencimientos
        $table->date('fecha_emision')->nullable();
        $table->date('fecha_vencimiento')->nullable();

        // Archivo subido (PDF, imagen, etc.)
        $table->string('archivo')->nullable(); // Se guardará la ruta/storage

        // Estado opcional (vigente, vencido, en proceso)
        $table->enum('estado', ['vigente', 'proximo_vencer', 'vencido'])->nullable();

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
        Schema::dropIfExists('legajos_vehiculares');
    }
}
