<?php

namespace App\Http\Controllers\Trafico;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TraficoController extends Controller
{
    public function index()
    {
        // Si querés pasar las últimas unidades al panel:
        // $unidades = Unidad::latest()->take(5)->get();

        return view('trafico.index'); // , compact('unidades'));
    }
}
