<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresaDocumentosTable extends Migration
{
    public function up()
    {
        Schema::create('empresa_documentos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('empresa_id')
                  ->constrained('empresas')
                  ->onDelete('cascade'); 

            $table->string('tipo_documento');
            $table->string('nombre_documento');
            $table->string('archivo');

            $table->date('fecha_emision')->nullable();
            $table->date('fecha_vencimiento')->nullable();

            $table->enum('estado', ['vigente', 'vencido', 'pendiente'])
                  ->default('pendiente');

            $table->text('observaciones')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('empresa_documentos');
    }
}

