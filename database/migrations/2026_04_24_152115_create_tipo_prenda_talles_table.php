<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoPrendaTallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('tipo_prenda_talles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tipo_prenda_id')->constrained('tipos_prenda')->cascadeOnDelete();
                $table->string('nombre'); // S, M, L, 40, 41...
                $table->integer('orden')->nullable();
                $table->boolean('estado')->default(true);
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
        Schema::dropIfExists('tipo_prenda_talles');
    }
}
