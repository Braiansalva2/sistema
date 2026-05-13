<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadoCapacitacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('empleado_capacitacion', function (Blueprint $table) {
                $table->id();

                $table->foreignId('empleado_id')->constrained()->cascadeOnDelete();
               $table->foreignId('capacitacion_id')->constrained('capacitaciones')->cascadeOnDelete();

                $table->date('fecha_realizada');
                $table->date('fecha_vencimiento')->nullable();

                $table->string('dictado_por', 150)->nullable(); 
                $table->string('constancia_path', 255)->nullable(); 
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
        Schema::dropIfExists('empleado_capacitacions');
    }
}
