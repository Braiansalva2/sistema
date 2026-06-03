<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateEstadoAdelantosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        DB::statement("
            ALTER TABLE adelantos
            MODIFY estado ENUM(
                'pendiente',
                'aprobado',
                'rechazado',
                'pagado',
                'saldado'
            ) DEFAULT 'pendiente'
        ");
    }

    public function down()
    {
        DB::statement("
            ALTER TABLE adelantos
            MODIFY estado ENUM(
                'pendiente',
                'aprobado',
                'rechazado',
                'pagado'
            ) DEFAULT 'pendiente'
        ");
    }
}
