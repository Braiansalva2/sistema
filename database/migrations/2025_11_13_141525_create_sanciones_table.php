<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSancionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up(): void
    {
        Schema::create('sanciones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');

            $table->string('tipo_sancion', 100); // Ej: "Amonestación", "Suspensión"
            $table->date('fecha_sancion');
            $table->text('motivo');
            $table->string('documento_path', 255)->nullable(); // PDF de sanción si aplica
            $table->enum('estado', ['vigente', 'cumplida'])->default('vigente');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sanciones');
    }
}
