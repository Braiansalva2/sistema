<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSueldosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('sueldos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('empleado_id')->constrained()->cascadeOnDelete();

            $table->decimal('sueldo_base', 10, 2);
            $table->decimal('valor_hora', 10, 2)->nullable();
            $table->decimal('porcentaje_hora_extra', 5, 2)->default(1.5);

            $table->date('fecha_desde');
            $table->date('fecha_hasta')->nullable();

            $table->boolean('activo')->default(true);

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
        Schema::dropIfExists('sueldos');
    }
}
