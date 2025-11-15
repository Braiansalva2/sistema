<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CustomAuthenticatedSessionController;
use App\Http\Controllers\RRHH\ObraSocialController;

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



Route::middleware(['auth', 'role:Admin|rrhh'])->prefix('rrhh')->group(function () {
    // CRUD empleados
    Route::resource('empleados', App\Http\Controllers\RRHH\EmpleadoController::class);

    // Rutas para selects dinámicos
    Route::resource('bancos', App\Http\Controllers\RRHH\BancoController::class)->only(['index', 'store']);
    Route::resource('contratos', App\Http\Controllers\RRHH\ContratoController::class)->only(['index', 'store']);
    Route::resource('condiciones', App\Http\Controllers\RRHH\CondicionLaboralController::class)->only(['index', 'store']);

 Route::resource('obras-sociales', ObraSocialController::class);


});

});
