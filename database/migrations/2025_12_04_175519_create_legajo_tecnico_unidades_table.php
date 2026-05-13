<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLegajoTecnicoUnidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('legajo_tecnico_unidades', function (Blueprint $table) {
         $table->id();

            $table->foreignId('unidad_id')->constrained('unidades')->onDelete('cascade');

            // Tipo de documento técnico (seguro, vtv, inspección, etc)
            $table->string('tipo_documento');

            // Nombre archivo guardado
            $table->string('archivo')->nullable();

            // Fechas importantes
            $table->date('fecha_emision')->nullable();
            $table->date('fecha_vencimiento')->nullable();

            // Observación
            $table->text('detalle')->nullable();

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
        Schema::dropIfExists('legajo_tecnico_unidades');
    }
}
