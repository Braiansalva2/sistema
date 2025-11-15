<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDescansosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up(): void
    {
        Schema::create('descansos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');

            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->string('motivo')->nullable(); // Ej: "Descanso por roster", "Licencia especial"
            $table->enum('tipo', ['roster', 'especial'])->default('roster');
            $table->enum('estado', ['programado', 'en_curso', 'finalizado'])->default('programado');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('descansos');
    }
}
