<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtensionFieldsToViaticos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
{
    Schema::table('viaticos', function (Blueprint $table) {

        
        $table->foreignId('viatico_padre_id')
            ->nullable()
            ->constrained('viaticos')
            ->nullOnDelete();

        $table->boolean('es_extension')->default(false);



        $table->integer('dias_extra')->nullable();

    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('viaticos', function (Blueprint $table) {
            //
        });
    }
}
