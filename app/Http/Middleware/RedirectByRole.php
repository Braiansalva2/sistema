<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectByRole
{
    public function handle(Request $request, Closure $next): Response
{
    if (auth()->check()) {

        $user = auth()->user();

        // 🔥 EVITAR REDIRECCIÓN INFINITA
        if ($request->is('empleado/*')) {
            return $next($request);
        }

        if ($request->is('admin/*')) {
            return $next($request);
        }

        if ($request->is('rrhh/*')) {
            return $next($request);
        }

        if ($request->is('comercial/*')) {
            return $next($request);
        }

        if ($request->is('trafico/*')) {
            return $next($request);
        }

        if ($request->is('documentacion/*')) {
            return $next($request);
        }

        // 🔥 REDIRECCIÓN POR ROL
        if ($user->hasRole('empleado')) {
            return redirect()->route('empleado.index');
        }

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.panel');
        }

        if ($user->hasRole('rrhh')) {
            return redirect()->route('rrhh.panel');
        }

        if ($user->hasRole('comercial')) {
            return redirect()->route('comercial.dashboard');
        }

        if ($user->hasRole('trafico')) {
            return redirect()->route('trafico.panel');
        }

        if ($user->hasRole('documentacion')) {
            return redirect()->route('documentacion.viaticos.index');
        }
    }

    return $next($request);
}
}