<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViaticoDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
{
    Schema::create('viatico_detalles', function (Blueprint $table) {
        $table->id();

        $table->foreignId('viatico_id')->constrained()->cascadeOnDelete();

        $table->string('concepto'); // almuerzo, cena, peajes
        $table->integer('cantidad')->default(1);

        $table->decimal('precio', 10, 2)->default(0);
        $table->decimal('subtotal', 10, 2)->default(0);
        $table->text('observaciones')->nullable();

        $table->text('comentario')->nullable();

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
        Schema::dropIfExists('viatico_detalles');
    }
}
