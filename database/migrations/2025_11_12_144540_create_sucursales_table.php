<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSucursalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('sucursales', function (Blueprint $table) {
            $table->id();

            $table->string('nombre', 150);
            $table->string('codigo', 20)->unique()->nullable(); // SALTA, PUNA, etc
            $table->string('direccion', 255)->nullable();
            $table->string('localidad', 100)->nullable();
            $table->string('provincia', 100)->nullable();
            $table->string('telefono', 30)->nullable();
            $table->string('email', 150)->nullable();

            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
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
        Schema::dropIfExists('sucursales');
    }
}
