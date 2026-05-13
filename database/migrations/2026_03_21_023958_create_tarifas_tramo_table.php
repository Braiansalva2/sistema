<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTarifasTramoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('tarifas_tramo', function (Blueprint $table) {
    $table->id();

    $table->foreignId('tramo_id')->constrained()->cascadeOnDelete();

    $table->enum('tipo', ['viaje','km','m3','tonelada','hora']);

    $table->decimal('precio', 10, 2);

    // HISTORIAL DE PRECIOS 
    $table->date('fecha_desde');
    $table->date('fecha_hasta')->nullable();

    $table->boolean('activo')->default(true);

    // opcional 
    $table->string('motivo')->nullable();

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
        Schema::dropIfExists('tarifas_tramo');
    }
}
