<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposPrendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
   public function run()
    {
        DB::table('tipos_prenda')->insert([
            ['nombre' => 'Camisa', 'estado' => true],
            ['nombre' => 'Campera', 'estado' => true],
            ['nombre' => 'Pantalón', 'estado' => true],
            ['nombre' => 'Botas', 'estado' => true],
        ]);
    }
}
