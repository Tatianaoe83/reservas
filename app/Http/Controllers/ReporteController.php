<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class ReporteController extends Controller
{
    public function index(): View
    {
        return view('reportes.index');
    }

    public function semanal(Request $request): View
    {
        $fechaInicio = $request->get('fecha_inicio', Carbon::now()->startOfWeek()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', Carbon::parse($fechaInicio)->endOfWeek()->format('Y-m-d'));

        $reservas = Reserva::with(['cliente', 'vehiculo'])
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();

        $totalReservas = $reservas->count();
        $totalCantidad = $reservas->sum('cantidad');
        $reservasPorVehiculo = $reservas->groupBy('vehiculo_id');

        return view('reportes.semanal', compact('reservas', 'fechaInicio', 'fechaFin', 'totalReservas', 'totalCantidad', 'reservasPorVehiculo'));
    }

    public function mensual(Request $request): View
    {
        $mes = $request->get('mes', Carbon::now()->month);
        $anio = $request->get('anio', Carbon::now()->year);

        $fechaInicio = Carbon::create($anio, $mes, 1)->startOfMonth();
        $fechaFin = Carbon::create($anio, $mes, 1)->endOfMonth();

        $reservas = Reserva::with(['cliente', 'vehiculo'])
            ->whereBetween('fecha', [$fechaInicio->format('Y-m-d'), $fechaFin->format('Y-m-d')])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();

        $totalReservas = $reservas->count();
        $totalCantidad = $reservas->sum('cantidad');
        $reservasPorVehiculo = $reservas->groupBy('vehiculo_id');
        $reservasPorDia = $reservas->groupBy(function ($reserva) {
            return Carbon::parse($reserva->fecha)->format('Y-m-d');
        });

        return view('reportes.mensual', compact(
            'reservas',
            'mes',
            'anio',
            'fechaInicio',
            'fechaFin',
            'totalReservas',
            'totalCantidad',
            'reservasPorVehiculo',
            'reservasPorDia'
        ));
    }
}
