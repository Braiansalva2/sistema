<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Empleado;

class TieneEmpleado
{
    public function handle(Request $request, Closure $next)
    {
        $empleado = Empleado::where('user_id', auth()->id())->first();

        if (!$empleado) {
            abort(403, 'No tenés un legajo de empleado asociado.');
        }

        return $next($request);
    }
}