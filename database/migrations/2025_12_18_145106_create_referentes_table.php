<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferentesTable extends Migration
{
    public function up()
    {
        Schema::create('referentes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('empresa_id')
                  ->constrained('empresas')
                  ->onDelete('cascade');

            $table->string('nombre');
            $table->string('apellido');
            $table->string('cargo')->nullable();
            $table->string('telefono')->nullable();
            $table->string('correo')->nullable();

            $table->boolean('es_principal')->default(false);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('referentes');
    }
}
