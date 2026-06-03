<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFechasToDatetimeInViaticosTable extends Migration
{
    public function up()
    {
        Schema::table('viaticos', function (Blueprint $table) {
            $table->dateTime('fecha_salida')->change();
            $table->dateTime('fecha_regreso')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('viaticos', function (Blueprint $table) {
            $table->date('fecha_salida')->change();
            $table->date('fecha_regreso')->nullable()->change();
        });
    }
}