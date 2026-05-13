<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up(): void
{
    Schema::create('empleados', function (Blueprint $table) {
        $table->id();
        $table->string('nombre', 100);
        $table->string('apellido', 100);
        $table->string('dni', 20)->unique();
        $table->string('cuil', 20)->nullable();
        $table->date('fecha_nacimiento')->nullable();
        $table->string('direccion', 255)->nullable();
        $table->string('telefono', 30)->nullable();
        $table->string('email', 150)->nullable();
        $table->date('fecha_ingreso')->nullable();
        $table->string('foto_perfil', 255)->nullable();     
        $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
        $table->enum('tipo_empleado', ['base', 'chofer', 'roster', 'mixto'])->default('base');
        $table->boolean('cumple_roster')->default(0);
        $table->string('nacionalidad', 100)->nullable();
        $table->string('lugar_nacimiento', 150)->nullable();
         $table->enum('estado_civil', [
                        'Soltero',
                        'Casado',
                        'Divorciado',
                        'Viudo',
                        'Unión libre',
                        'Separado',
                        'Otro'
                    ])->nullable();
        $table->enum('sexo', ['Masculino', 'Femenino', 'Otro'])->nullable();
        // Relación con banco
        $table->foreignId('banco_id')->nullable()->constrained('bancos')->nullOnDelete();
        $table->string('cbu', 50)->nullable();
         $table->string('numero_cuenta', 30)->nullable(); 
        // Relación con obra social
        $table->foreignId('obra_social_id')->nullable()->constrained('obra_social')->nullOnDelete();
        $table->date('fecha_cambio_obra_social')->nullable();
        $table->string('constancia_cambio_path', 255)->nullable();
        // Relación con ART
        $table->foreignId('art_id')->nullable()->constrained('arts')->nullOnDelete();
        // Relación con rol o puesto laboral
        $table->foreignId('rol_puesto_id')->nullable()->constrained('roles_puestos')->nullOnDelete();
        // Relación con condición laboral
        $table->foreignId('condicion_laboral_id')->nullable()->constrained('condiciones_laborales')->nullOnDelete();
        // Relación con tipo de contrato
        $table->foreignId('contrato_id')->nullable()->constrained('contratos')->nullOnDelete();

        // Relación opcional con usuario del sistema (Jetstream)
        $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
          //NUEVO: sucursal 
       $table->foreignId('sucursal_id')
          ->constrained('sucursales')
          ->cascadeOnUpdate();

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
        Schema::dropIfExists('empleados');
    }
}
