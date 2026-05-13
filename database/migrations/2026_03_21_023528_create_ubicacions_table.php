<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUbicacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ubicaciones', function (Blueprint $table) {
                    $table->id();
                    $table->string('nombre');
                    $table->string('tipo')->nullable(); // ciudad, proyecto, planta, base, campamento, etc.
                    $table->text('descripcion')->nullable();
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
        Schema::dropIfExists('ubicacions');
    }
}
