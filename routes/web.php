<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ReporteController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Rutas de Clientes
    Route::resource('clientes', ClienteController::class);

    // Rutas de Vehículos
    Route::resource('vehiculos', VehiculoController::class);

    // Rutas de Reservas
    Route::resource('reservas', ReservaController::class);
    Route::get('reservas-calendario', [ReservaController::class, 'calendario'])->name('reservas.calendario');

    // Rutas de Reportes
    Route::prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/', [ReporteController::class, 'index'])->name('index');
        Route::get('semanal', [ReporteController::class, 'semanal'])->name('semanal');
        Route::get('mensual', [ReporteController::class, 'mensual'])->name('mensual');
    });
});
