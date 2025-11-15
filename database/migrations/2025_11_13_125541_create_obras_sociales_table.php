<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObrasSocialesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
 public function up(): void
{
    Schema::create('obra_social', function (Blueprint $table) {
        $table->id();
        $table->string('codigo', 20)->unique();
        $table->string('nombre', 100);
        $table->date('vigencia')->nullable(); // Fecha hasta la que está vigente (opcional)
        $table->enum('estado', ['activa', 'inactiva'])->default('activa');
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
        Schema::dropIfExists('obras_sociales');
    }
}
