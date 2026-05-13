<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRosterEmpleadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('roster_empleado', function (Blueprint $table) {
                $table->id();

                $table->foreignId('roster_id')
                    ->constrained('rosters')
                    ->cascadeOnDelete();

                $table->foreignId('empleado_id')
                    ->constrained('empleados')
                    ->cascadeOnDelete();

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
        Schema::dropIfExists('roster_empleado');
    }
}
