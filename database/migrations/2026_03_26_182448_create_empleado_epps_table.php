<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadoEppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleado_epp', function (Blueprint $table) {
                    $table->id();

                    $table->foreignId('empleado_id')->constrained()->cascadeOnDelete();
                    $table->foreignId('tipo_epp_id')->constrained('tipos_epp');
                    $table->foreignId('talle_id')->nullable()->constrained('talles');

                    $table->date('fecha_entrega');
                    $table->date('fecha_vencimiento')->nullable();

                    $table->integer('cantidad')->default(1);

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
        Schema::dropIfExists('empleado_epps');
    }
}
