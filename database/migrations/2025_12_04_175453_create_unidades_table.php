<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unidades', function (Blueprint $table) {
            $table->id();

            /* =============================
                RELACIONES PRINCIPALES
               ============================= */
            $table->foreignId('marca_id')
                  ->constrained('marcas')
                  ->onDelete('restrict');

            $table->foreignId('modelo_id')
                  ->constrained('modelos')
                  ->onDelete('restrict');

            $table->foreignId('tipo_vehiculo_id')
                  ->constrained('tipos_vehiculos')
                  ->onDelete('restrict');

            /* =============================
                DATOS GENERALES DE LA UNIDAD
               ============================= */
            $table->string('cod_interno')->unique();

            //  Patente principal
            $table->string('dominio')->unique()->nullable();

            //  Patente secundaria (solo si aplica: acoplado, semi, carretones, etc.)
            $table->string('dominio_secundario')->nullable();

            $table->integer('anio')->nullable();
            $table->string('color')->nullable();
            $table->integer('km_actual')->nullable();

            /* =============================
                ORIGEN DE LA UNIDAD
               ============================= */
            $table->enum('origen', ['propio', 'tercerizado'])
                  ->default('propio');

            // FK → empresa dueña (solo si es tercerizado)
            $table->foreignId('empresa_tercerizada_id')
                  ->nullable()
                  ->constrained('empresas_tercerizadas')
                  ->nullOnDelete();

            /* =============================
               DATOS TÉCNICOS
               ============================= */

            // Capacidad de carga expresada en kg
            $table->integer('capacidad_kg')->nullable();

            // Dimensiones
            $table->decimal('largo_total', 8, 2)->nullable();   // metros
            $table->decimal('alto', 8, 2)->nullable();          // metros
            $table->decimal('ancho', 8, 2)->nullable();         // metros

            
          

            /* =============================
                ESTADO DE LA UNIDAD
               ============================= */
            $table->enum('estado', ['activo', 'inactivo', 'baja', 'taller'])
                  ->default('activo');

            /* =============================
                FECHAS
               ============================= */
            $table->date('fecha_alta')->nullable();
            $table->date('fecha_baja')->nullable();

            /* =============================
                OBSERVACIONES
               ============================= */
            $table->text('observaciones')->nullable();

            // ID del vehículo en STRIX
                $table->string('strix_thing_id')->nullable();

                // Indica si la unidad tiene GPS asociado
                $table->boolean('tiene_gps')->default(false);

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
        Schema::dropIfExists('unidades');
    }
}
