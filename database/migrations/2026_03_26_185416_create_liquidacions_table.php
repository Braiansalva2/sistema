<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiquidacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('liquidaciones', function (Blueprint $table) {
                    $table->id();

                    $table->foreignId('empleado_id')->constrained()->cascadeOnDelete();

                    $table->date('periodo_desde');
                    $table->date('periodo_hasta');

                    $table->decimal('sueldo_base', 10, 2);
                    $table->decimal('extras', 10, 2);
                    $table->decimal('viaticos', 10, 2);
                    $table->decimal('descuentos', 10, 2);
                    $table->decimal('anticipos', 10, 2);

                    $table->decimal('total', 10, 2);

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
        Schema::dropIfExists('liquidacions');
    }
}
