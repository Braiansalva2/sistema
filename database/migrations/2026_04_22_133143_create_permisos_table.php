<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermisosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permisos', function (Blueprint $table) {
    $table->id();

    $table->foreignId('empleado_id')->constrained()->cascadeOnDelete();

    $table->enum('tipo', ['dias', 'horas']);

    // Para días
    $table->date('fecha_desde')->nullable();
    $table->date('fecha_hasta')->nullable();
    $table->integer('total_dias')->nullable();

    // Para horas
    $table->date('fecha_horas')->nullable();
    $table->time('hora_desde')->nullable();
    $table->time('hora_hasta')->nullable();
    $table->integer('total_horas')->nullable();

    $table->text('motivo');

    // Estado del permiso
    $table->enum('estado', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');

    // Auditoría
    $table->timestamp('fecha_aprobacion')->nullable();
    $table->foreignId('aprobado_por')->nullable()->constrained('users');

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
        Schema::dropIfExists('permisos');
    }
}
