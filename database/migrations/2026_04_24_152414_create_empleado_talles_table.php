<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadoTallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('empleado_talles', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('empleado_id')->constrained()->cascadeOnDelete();
                    $table->foreignId('tipo_prenda_id')->constrained('tipos_prenda')->cascadeOnDelete();
                    $table->foreignId('tipo_prenda_talle_id')->constrained('tipo_prenda_talles')->cascadeOnDelete();
                    $table->timestamps();

                    $table->unique(['empleado_id', 'tipo_prenda_id']); // 1 talle por prenda
                });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empleado_talles');
    }
}
