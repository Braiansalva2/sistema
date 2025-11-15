<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLegajosEmpleadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('legajos_empleados', function (Blueprint $table) {
            $table->id();

            // Relación con empleado
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');

            // Datos del documento
            $table->string('nombre_archivo', 150); // Ej: "DNI frente", "Carnet conducir"
            $table->string('descripcion')->nullable();
            $table->string('archivo_path', 255); // Ruta del PDF o imagen

            // Control de vigencia
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->enum('estado', ['vigente', 'por_vencer', 'vencido'])->default('vigente');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legajos_empleados');
    }
}
