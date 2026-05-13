<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoPrendaTalleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
      public function run()
    {
        // Buscar IDs de prendas
        $camisa = DB::table('tipos_prenda')->where('nombre', 'Camisa')->first()->id;
        $campera = DB::table('tipos_prenda')->where('nombre', 'Campera')->first()->id;
        $pantalon = DB::table('tipos_prenda')->where('nombre', 'Pantalón')->first()->id;
        $botas = DB::table('tipos_prenda')->where('nombre', 'Botas')->first()->id;

        // Talles ropa
        $tallesRopa = ['XS','S','M','L','XL','XXL'];

        foreach ($tallesRopa as $index => $talle) {
            DB::table('tipo_prenda_talles')->insert([
                'tipo_prenda_id' => $camisa,
                'nombre' => $talle,
                'orden' => $index,
                'estado' => true
            ]);

            DB::table('tipo_prenda_talles')->insert([
                'tipo_prenda_id' => $campera,
                'nombre' => $talle,
                'orden' => $index,
                'estado' => true
            ]);

            DB::table('tipo_prenda_talles')->insert([
                'tipo_prenda_id' => $pantalon,
                'nombre' => $talle,
                'orden' => $index,
                'estado' => true
            ]);
        }

        // Talles botas
        $tallesBotas = ['38','39','40','41','42','43','44'];

        foreach ($tallesBotas as $index => $talle) {
            DB::table('tipo_prenda_talles')->insert([
                'tipo_prenda_id' => $botas,
                'nombre' => $talle,
                'orden' => $index,
                'estado' => true
            ]);
        }
    }
}
