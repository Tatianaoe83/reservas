<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\ProductoController;
use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Models\Reserva;

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
        $totalClientes = Cliente::count();
        $totalVehiculos = Vehiculo::count();
        $totalReservas = Reserva::count();
        $reservasHoy = Reserva::whereDate('fecha', today())->count();
        $reservasEstaSemana = Reserva::whereBetween('fecha', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $reservasEsteMes = Reserva::whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)
            ->count();
        
        // Reservas entregadas y no entregadas
        $reservasEntregadas = Reserva::where('estatus', Reserva::ESTATUS_ENTREGADO)->count();
        $reservasNoEntregadas = Reserva::where('estatus', Reserva::ESTATUS_NO_ENTREGADO)->count();
        
        return view('dashboard', compact(
            'totalClientes', 
            'totalVehiculos', 
            'totalReservas', 
            'reservasHoy', 
            'reservasEstaSemana', 
            'reservasEsteMes',
            'reservasEntregadas',
            'reservasNoEntregadas'
        ));
    })->name('dashboard');

    // Rutas de Clientes
    Route::resource('clientes', ClienteController::class);

    // Rutas de Vehículos
    Route::resource('vehiculos', VehiculoController::class);

    // Rutas de Reservas
    // IMPORTANTE: Estas rutas deben ir ANTES del resource para evitar conflictos
    Route::get('reservas-calendario', [ReservaController::class, 'calendario'])->name('reservas.calendario');
    Route::get('reservas/horarios-disponibles', [ReservaController::class, 'getHorariosDisponibles'])->name('reservas.horarios-disponibles');
    Route::get('reservas/{reserva}/ticket', [ReservaController::class, 'ticket'])->name('reservas.ticket');
    Route::resource('reservas', ReservaController::class);

    // Rutas de Reportes
    Route::prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/', [ReporteController::class, 'index'])->name('index');
        Route::get('general', [ReporteController::class, 'general'])->name('general');
        Route::get('general/export', [ReporteController::class, 'exportGeneral'])->name('general.export');
        Route::get('ventas-acumuladas', [ReporteController::class, 'ventasAcumuladas'])->name('ventas-acumuladas');
        Route::get('ventas-acumuladas/export', [ReporteController::class, 'exportVentasAcumuladas'])->name('ventas-acumuladas.export');
        Route::get('insumos', [ReporteController::class, 'insumos'])->name('insumos');
        Route::get('insumos/export', [ReporteController::class, 'exportInsumos'])->name('insumos.export');
        Route::get('rentabilidad', [ReporteController::class, 'rentabilidad'])->name('rentabilidad');
    });

    // Rutas de Insumos
    Route::resource('insumos', InsumoController::class);

    // Rutas de Productos
    Route::resource('productos', ProductoController::class);
});
