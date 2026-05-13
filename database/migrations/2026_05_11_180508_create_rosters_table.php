<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRostersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('rosters', function (Blueprint $table) {
                $table->id();

                $table->string('nombre');

                $table->integer('modalidad_trabajo');
                $table->integer('modalidad_descanso');

                $table->date('fecha_subida');
                $table->date('fecha_bajada');

                $table->enum('estado', ['Activo', 'Inactivo'])
                    ->default('Activo');

                $table->text('observaciones')->nullable();

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
        Schema::dropIfExists('rosters');
    }
}
