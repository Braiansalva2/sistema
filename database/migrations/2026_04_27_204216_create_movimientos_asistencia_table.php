<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovimientosAsistenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimientos_asistencia', function (Blueprint $table) {

            $table->id();

            // EMPLEADO
            $table->foreignId('empleado_id')
                ->constrained('empleados')
                ->onDelete('cascade');

            // TIPO DE MOVIMIENTO
            $table->enum('tipo', ['entrada', 'salida']);
            $table->boolean('automatico')->default(false);

            // FECHA Y HORA
            $table->dateTime('fecha_hora');

            // GPS
            $table->decimal('latitud', 10, 7)->nullable();
            $table->decimal('longitud', 10, 7)->nullable();

            $table->string('precision_gps')->nullable();

            $table->boolean('con_ubicacion')->default(true);

            // INFORMACIÓN DEL DISPOSITIVO
            $table->string('ip', 45)->nullable();

            $table->text('device')->nullable();

            // FOTO
            $table->string('foto')->nullable();

            // OBSERVACIONES
            $table->text('observaciones')->nullable();

            // ESTADO GPS
            $table->enum('estado_gps', [
                'correcto',
                'sin_permiso',
                'sin_gps',
                'fuera_de_zona',
                'error'
            ])->nullable();

            // DISTANCIA A BASE
            $table->decimal('distancia_base_metros', 10, 2)->nullable();

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
        Schema::dropIfExists('movimientos_asistencia');
    }
}
