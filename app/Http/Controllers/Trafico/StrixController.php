<?php

namespace App\Http\Controllers\Trafico;

use App\Http\Controllers\Controller;
use App\Models\Unidad;
use App\Services\StrixService;

class StrixController extends Controller
{
    /**
     * Sincroniza vehículos STRIX con unidades locales
     * Cruza por patente (dominio)
     */
    public function sync(StrixService $strix)
    {
        $things = $strix->listarThings();

        $vinculadas = 0;
        $dominiosStrix = [];

foreach ($things as $thing) {
    $dominiosStrix[] = $thing['info']['domain'] ?? '(sin dominio)';
}

dd($dominiosStrix);

        foreach ($things as $thing) {

            $dominio = $thing['info']['domain'] ?? null;

            if (!$dominio) {
                continue;
            }

            $unidad = Unidad::where('dominio', $dominio)->first();

            if ($unidad) {
                $unidad->update([
                    'strix_thing_id' => $thing['id'],
                    'tiene_gps' => true,
                ]);

                $vinculadas++;
            }
        }

        return response()->json([
            'total_strix' => count($things),
            'unidades_vinculadas' => $vinculadas,
        ]);
    }


     public function todasLasUnidades(StrixService $strix)
    {
        $unidades = $strix->listarTodasLasUnidades();
        dd(count($unidades));

        return response()->json([
            'total' => count($unidades),
            'unidades' => $unidades,
        ]);
    }

    public function monitoreo()
{
    return view('trafico.unidades.monitoreo');
}

}
