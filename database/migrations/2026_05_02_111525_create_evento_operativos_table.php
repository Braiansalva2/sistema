<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventoOperativosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('eventos_operativos', function (Blueprint $table) {
                $table->id();

                // RELACIÓN
                $table->foreignId('empleado_id')->constrained()->onDelete('cascade');

                // TIPO DE EVENTO
                $table->enum('tipo_evento', [
                    'inicio_jornada',
                    'punto_control',
                    'fin_jornada'
                ]);

                // TIEMPO
                $table->dateTime('fecha_hora');

                // GPS
                $table->decimal('latitud', 10, 7)->nullable();
                $table->decimal('longitud', 10, 7)->nullable();

                // DATOS DEL VIAJE (solo se usan en inicio)
                $table->string('origen')->nullable();
                $table->string('destino')->nullable();
                $table->string('vehiculo')->nullable();

                // EXTRA
                $table->text('descripcion')->nullable();
                //para el lugar
                $table->string('lugar')->nullable();
                // CONTROL
                $table->string('ip')->nullable();
                $table->text('device')->nullable();

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
        Schema::dropIfExists('evento_operativos');
    }
}
