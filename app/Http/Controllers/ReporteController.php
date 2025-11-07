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

    public function general(Request $request): View
    {
        // Obtener filtros de fecha (por defecto: semana actual)
        $fechaInicio = $request->get('fecha_inicio', Carbon::now()->startOfWeek()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', Carbon::now()->endOfWeek()->format('Y-m-d'));

        // Validar que la fecha de inicio no sea mayor que la de fin
        if (Carbon::parse($fechaInicio)->gt(Carbon::parse($fechaFin))) {
            $fechaInicio = Carbon::now()->startOfWeek()->format('Y-m-d');
            $fechaFin = Carbon::now()->endOfWeek()->format('Y-m-d');
        }

        $reservas = Reserva::with(['cliente', 'vehiculo'])
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();

        // Calcular totales
        $totalReservas = $reservas->count();
        $totalCantidad = $reservas->sum('cantidad');
        $totalGanancia = $reservas->sum(function($reserva) {
            return $reserva->cliente->precio_venta * $reserva->cantidad;
        });

        // Agrupar por vehículo
        $reservasPorVehiculo = $reservas->groupBy('vehiculo_id')->map(function($grupo) {
            return [
                'vehiculo' => $grupo->first()->vehiculo,
                'cantidad' => $grupo->sum('cantidad'),
                'ganancia' => $grupo->sum(function($r) {
                    return $r->cliente->precio_venta * $r->cantidad;
                }),
                'reservas' => $grupo->count(),
            ];
        });

        // Agrupar por día
        $reservasPorDia = $reservas->groupBy(function ($reserva) {
            return Carbon::parse($reserva->fecha)->format('Y-m-d');
        })->map(function($grupo) {
            return [
                'fecha' => Carbon::parse($grupo->first()->fecha),
                'cantidad' => $grupo->sum('cantidad'),
                'ganancia' => $grupo->sum(function($r) {
                    return $r->cliente->precio_venta * $r->cantidad;
                }),
                'reservas' => $grupo->count(),
                'items' => $grupo,
            ];
        })->sortBy('fecha');

        // Agrupar por cliente
        $reservasPorCliente = $reservas->groupBy('cliente_id')->map(function($grupo) {
            return [
                'cliente' => $grupo->first()->cliente,
                'cantidad' => $grupo->sum('cantidad'),
                'ganancia' => $grupo->sum(function($r) {
                    return $r->cliente->precio_venta * $r->cantidad;
                }),
                'reservas' => $grupo->count(),
            ];
        })->sortByDesc('ganancia');

        return view('reportes.general', compact(
            'reservas',
            'fechaInicio',
            'fechaFin',
            'totalReservas',
            'totalCantidad',
            'totalGanancia',
            'reservasPorVehiculo',
            'reservasPorDia',
            'reservasPorCliente'
        ));
    }

    public function ventasAcumuladas(Request $request): View
    {
        // Obtener filtros de fecha (por defecto: mes actual)
        $fechaInicio = $request->get('fecha_inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Validar que la fecha de inicio no sea mayor que la de fin
        if (Carbon::parse($fechaInicio)->gt(Carbon::parse($fechaFin))) {
            $fechaInicio = Carbon::now()->startOfMonth()->format('Y-m-d');
            $fechaFin = Carbon::now()->endOfMonth()->format('Y-m-d');
        }

        // Obtener todas las reservas en el rango de fechas con relaciones
        $reservas = Reserva::with(['cliente', 'vehiculo'])
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();

        // Agrupar por cliente
        $ventasPorCliente = [];
        
        foreach ($reservas as $reserva) {
            $clienteId = $reserva->cliente_id;
            $clienteNombre = $reserva->cliente->negocio;
            $precioVenta = $reserva->cliente->precio_venta;
            $cantidad = $reserva->cantidad;
            $ganancia = $precioVenta * $cantidad;
            $fecha = Carbon::parse($reserva->fecha);
            $mes = $fecha->format('Y-m');
            
            // Calcular la semana del mes (1-5)
            // Día del mes
            $diaDelMes = $fecha->day;
            // Calcular qué semana es (1-5)
            $numeroSemana = ceil($diaDelMes / 7);
            // Asegurar que esté entre 1 y 5
            if ($numeroSemana > 5) {
                $numeroSemana = 5;
            }

            if (!isset($ventasPorCliente[$clienteId])) {
                $ventasPorCliente[$clienteId] = [
                    'cliente' => $clienteNombre,
                    'meses' => [],
                    'venta_total' => 0,
                    'ganancia_total' => 0,
                ];
            }

            // Agrupar por mes
            if (!isset($ventasPorCliente[$clienteId]['meses'][$mes])) {
                $meses = [
                    'January' => 'Enero', 'February' => 'Febrero', 'March' => 'Marzo',
                    'April' => 'Abril', 'May' => 'Mayo', 'June' => 'Junio',
                    'July' => 'Julio', 'August' => 'Agosto', 'September' => 'Septiembre',
                    'October' => 'Octubre', 'November' => 'Noviembre', 'December' => 'Diciembre'
                ];
                $nombreMes = $meses[$fecha->format('F')] ?? $fecha->format('F');
                $ventasPorCliente[$clienteId]['meses'][$mes] = [
                    'nombre_mes' => $nombreMes . ' ' . $fecha->format('Y'),
                    'semanas' => [
                        1 => ['cantidad' => 0, 'ganancia' => 0],
                        2 => ['cantidad' => 0, 'ganancia' => 0],
                        3 => ['cantidad' => 0, 'ganancia' => 0],
                        4 => ['cantidad' => 0, 'ganancia' => 0],
                        5 => ['cantidad' => 0, 'ganancia' => 0],
                    ],
                    'venta_total' => 0,
                    'ganancia_total' => 0,
                ];
            }

            // Asegurar que el número de semana esté entre 1 y 5
            if ($numeroSemana > 5) {
                $numeroSemana = 5;
            }

            // Sumar a la semana correspondiente
            $ventasPorCliente[$clienteId]['meses'][$mes]['semanas'][$numeroSemana]['cantidad'] += $cantidad;
            $ventasPorCliente[$clienteId]['meses'][$mes]['semanas'][$numeroSemana]['ganancia'] += $ganancia;
            
            // Sumar al total del mes
            $ventasPorCliente[$clienteId]['meses'][$mes]['venta_total'] += $cantidad;
            $ventasPorCliente[$clienteId]['meses'][$mes]['ganancia_total'] += $ganancia;
            
            // Sumar al total del cliente
            $ventasPorCliente[$clienteId]['venta_total'] += $cantidad;
            $ventasPorCliente[$clienteId]['ganancia_total'] += $ganancia;
        }

        // Calcular totales generales
        $totalVentas = $reservas->sum('cantidad');
        $totalGanancia = $reservas->sum(function($reserva) {
            return $reserva->cliente->precio_venta * $reserva->cantidad;
        });

        return view('reportes.ventas-acumuladas', compact(
            'ventasPorCliente',
            'fechaInicio',
            'fechaFin',
            'totalVentas',
            'totalGanancia'
        ));
    }
}
