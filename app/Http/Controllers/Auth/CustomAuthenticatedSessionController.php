<?php

namespace App\Http\Controllers\Auth;

use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Illuminate\Http\Request;

class CustomAuthenticatedSessionController extends AuthenticatedSessionController
{
    protected function authenticated(Request $request, $user)
    {
        if ($user->hasRole('admin')) {
            return redirect('/admin');
        }

        if ($user->hasRole('rrhh')) {
            return redirect('/rrhh');
        }

        return redirect('/dashboard');
    }
}
