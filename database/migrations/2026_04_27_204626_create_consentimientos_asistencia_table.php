<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsentimientosAsistenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consentimientos_asistencia', function (Blueprint $table) {

            $table->id();

            $table->foreignId('empleado_id')
                ->constrained('empleados')
                ->onDelete('cascade');

            $table->text('texto_aceptado');

            $table->string('version')->default('1.0');

            $table->boolean('aceptado')->default(true);

            $table->dateTime('fecha_aceptacion');

            $table->string('ip', 45)->nullable();

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
        Schema::dropIfExists('consentimientos_asistencia');
    }
}
