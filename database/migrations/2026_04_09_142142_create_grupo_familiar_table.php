<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrupoFamiliarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('grupo_familiar', function (Blueprint $table) {
                $table->id();

                $table->foreignId('empleado_id')
                    ->constrained('empleados')
                    ->cascadeOnDelete();

                $table->string('nombre', 100);
                $table->string('apellido', 100);

                $table->enum('parentesco', [
                                    'Padre',
                                    'Madre',
                                    'Hijo',
                                    'Hija',
                                    'Hermano',
                                    'Hermana',
                                    'Cónyuge',
                                    'Otro'
                                ]);
                $table->boolean('a_cargo')->default(0);

                $table->date('fecha_nacimiento')->nullable();
                $table->string('dni', 20)->nullable();

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
        Schema::dropIfExists('grupo_familiar');
    }
}
