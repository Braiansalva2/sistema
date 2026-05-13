<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasTercerizadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('empresas_tercerizadas', function (Blueprint $table) {
                $table->id();
                $table->string('nombre')->unique();
                $table->string('cuit')->nullable();
                $table->string('telefono')->nullable();
                $table->string('correo')->nullable();
                $table->string('responsable')->nullable();
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
        Schema::dropIfExists('empresas_tercerizadas');
    }
}
