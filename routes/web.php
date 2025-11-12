<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CustomAuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí se registran las rutas web.

|
*/

Route::get('/', function () {
    return view('welcome');
});



// 🔹 Rutas protegidas por autenticación estándar de Jetstream
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Ruta genérica por defecto (solo por compatibilidad)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

   // Panel ADMIN
Route::get('/admin', function () {
    return view('admin.dashboard');
})->middleware('role:admin')->name('admin.panel');

// Panel RRHH
Route::get('/rrhh', function () {
    return view('rrhh.dashboard');
})->middleware('role:rrhh')->name('rrhh.panel');

});
