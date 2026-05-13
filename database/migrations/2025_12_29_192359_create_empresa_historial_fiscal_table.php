<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('empresa_historial_fiscal', function (Blueprint $table) {
            $table->id();

            // Relación con empresas
            $table->foreignId('empresa_id')
                  ->constrained('empresas')
                  ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | DATOS FISCALES (CAMBIO DETECTADO)
            |--------------------------------------------------------------------------
            */

            // Razón social
            $table->string('razon_social_anterior')->nullable();
            $table->string('razon_social_nueva')->nullable();

            // Condición frente al IVA
            // Responsable Inscripto / Monotributo / Exento / etc.
            $table->string('condicion_iva')->nullable();

            // Estado fiscal ante ARCA
            // Activo / Baja / Suspendido / Limitado
            $table->string('estado_fiscal')->nullable();

            // Tipo de persona
            // FISICA / JURIDICA
            $table->string('tipo_persona')->nullable();

            // Actividad principal declarada
            $table->string('actividad_principal')->nullable();

            /*
            |--------------------------------------------------------------------------
            | CONTROL Y AUDITORÍA
            |--------------------------------------------------------------------------
            */

            // Fecha en la que ARCA informó el cambio
            $table->timestamp('fecha_cambio')->nullable();

            // Origen del cambio
            // ARCA / MANUAL / IMPORTADO
            $table->enum('origen', ['ARCA', 'MANUAL', 'IMPORTADO'])
                  ->default('ARCA');

            // Usuario que confirmó o registró el cambio (si aplica)
            $table->unsignedBigInteger('registrado_por')->nullable();

            /*
            |--------------------------------------------------------------------------
            | CAMPOS TÉCNICOS
            |--------------------------------------------------------------------------
            */

            // Hash fiscal para detectar diferencias (opcional pero recomendado)
            $table->string('arca_hash', 64)->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | ÍNDICES
            |--------------------------------------------------------------------------
            */
            $table->index('empresa_id');
            $table->index('fecha_cambio');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresa_historial_fiscal');
    }
};
