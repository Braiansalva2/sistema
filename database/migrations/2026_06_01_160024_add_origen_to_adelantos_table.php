<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrigenToAdelantosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('adelantos', function (Blueprint $table) {
            $table->enum('origen', ['portal', 'rrhh'])
                  ->default('portal')
                  ->after('motivo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adelantos', function (Blueprint $table) {
            $table->dropColumn('origen');
        });
    }
}
