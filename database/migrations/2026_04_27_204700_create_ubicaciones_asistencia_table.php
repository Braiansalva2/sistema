<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUbicacionesAsistenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('ubicaciones_asistencia', function (Blueprint $table) {

            $table->id();

            $table->string('nombre');

            $table->decimal('latitud', 10, 7);

            $table->decimal('longitud', 10, 7);

            // RADIO EN METROS
            $table->integer('radio_metros')->default(300);

            $table->boolean('estado')->default(true);


         $table->foreignId('sucursal_id')
                ->constrained('sucursales')
                ->onDelete('cascade');

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
        Schema::dropIfExists('ubicaciones_asistencia');
    }
}
