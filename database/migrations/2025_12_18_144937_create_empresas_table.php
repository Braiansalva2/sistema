<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | IDENTIDAD BÁSICA
            |--------------------------------------------------------------------------
            */

            // Nombre comercial (editable)
            $table->string('razon_social');

            // CUIT (clave fiscal)
            $table->string('cuit')->unique();

            // Logo / imagen
            $table->string('logo')->nullable();

            /*
            |--------------------------------------------------------------------------
            | DATOS FISCALES (ARCA - NO EDITABLES MANUALMENTE)
            |--------------------------------------------------------------------------
            */

            // Razón social legal según ARCA
            $table->string('razon_social_fiscal')->nullable();

            // Tipo de persona: FISICA / JURIDICA
            $table->string('tipo_persona')->nullable();

            // Condición frente al IVA: RI / MT / EXENTO / etc.
            $table->string('condicion_iva')->nullable();

            // Estado fiscal: ACTIVO / BAJA / SUSPENDIDO
            $table->string('estado_fiscal')->nullable();

            // Actividad principal declarada
            $table->string('actividad_principal')->nullable();

            /*
            |--------------------------------------------------------------------------
            | CONTROL DE VERIFICACIÓN ARCA
            |--------------------------------------------------------------------------
            */

            // Indica si la empresa fue verificada contra ARCA
            $table->boolean('verificada_arca')->default(false);

            // Última fecha de verificación ARCA
            $table->timestamp('arca_ultima_verificacion')->nullable();

            // Fuente de los datos fiscales
            $table->enum('fuente_datos', ['manual', 'arca'])
                  ->default('manual');

            // Hash fiscal para detectar cambios
            $table->string('arca_hash', 64)->nullable();

            // Alerta si ARCA detecta cambios
            $table->boolean('alerta_cambio_fiscal')->default(false);

            /*
            |--------------------------------------------------------------------------
            | ESTADO OPERATIVO DEL SISTEMA
            |--------------------------------------------------------------------------
            */

            // Estado interno del sistema
            $table->enum('estado', ['activa', 'inactiva'])
                  ->default('activa');

            /*
            |--------------------------------------------------------------------------
            | OBSERVACIONES
            |--------------------------------------------------------------------------
            */

            $table->text('observaciones')->nullable();

            /*
            |--------------------------------------------------------------------------
            | AUDITORÍA
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            /*
            |--------------------------------------------------------------------------
            | TIMESTAMPS
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | ÍNDICES
            |--------------------------------------------------------------------------
            */

            $table->index('cuit');
            $table->index('estado');
            $table->index('verificada_arca');
            $table->index('alerta_cambio_fiscal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
