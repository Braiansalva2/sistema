<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactosEmergenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('contactos_emergencia', function (Blueprint $table) {
            $table->id();

            // Relación con empleados
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');

            $table->string('nombre_contacto', 100);
            $table->string('parentesco', 50)->nullable();
            $table->string('telefono', 30)->nullable();
            $table->string('domicilio', 255)->nullable();
            $table->boolean('es_principal')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contactos_emergencia');
    }
}
