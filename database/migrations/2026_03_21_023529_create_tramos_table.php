<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTramosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('tramos', function (Blueprint $table) {
    $table->id();

    $table->string('codigo')->nullable();
    $table->string('nombre');

    $table->foreignId('origen_id')->constrained('ubicaciones');
    $table->foreignId('destino_id')->constrained('ubicaciones');

    $table->foreignId('tipos_vehiculos_id')->nullable()->constrained('tipos_vehiculos');
    $table->foreignId('tipos_servicio_id')->nullable()->constrained('tipos_servicio');

    $table->decimal('distancia_km', 10, 2)->nullable();
    $table->integer('tiempo_estimado_min')->nullable();

    $table->text('observaciones')->nullable();
    $table->string('estado')->default('activo');

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
        Schema::dropIfExists('tramos');
    }
}
