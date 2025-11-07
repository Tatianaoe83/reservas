<?php

namespace App\Http\Controllers;

use App\Exports\ReporteGeneralExport;
use App\Exports\VentasAcumuladasExport;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ReporteController extends Controller
{
    public function index(): View
    {
        return view('reportes.index');
    }

    public function general(Request $request): View
    {
        [$fechaInicio, $fechaFin] = $this->resolverRangoFechas($request);

        $datos = $this->obtenerDatosReporteGeneral($fechaInicio, $fechaFin);

        return view('reportes.general', array_merge($datos, [
            'fechaInicio' => $fechaInicio->format('Y-m-d'),
            'fechaFin' => $fechaFin->format('Y-m-d'),
        ]));
    }

    public function exportGeneral(Request $request)
    {
        [$fechaInicio, $fechaFin] = $this->resolverRangoFechas($request);

        $datos = $this->obtenerDatosReporteGeneral($fechaInicio, $fechaFin);

        $datos['fechaInicio'] = $fechaInicio->format('Y-m-d');
        $datos['fechaFin'] = $fechaFin->format('Y-m-d');

        $nombreArchivo = sprintf(
            'reporte-general-%s-%s.xlsx',
            $fechaInicio->format('Ymd'),
            $fechaFin->format('Ymd')
        );

        return Excel::download(new ReporteGeneralExport($datos), $nombreArchivo);
    }

    public function ventasAcumuladas(Request $request): View
    {
        [$fechaInicioCarbon, $fechaFinCarbon] = $this->resolverRangoFechasVentas($request);

        [$ventasPorCliente, $totalVentas, $totalGanancia] = $this->obtenerDatosVentasAcumuladas($fechaInicioCarbon, $fechaFinCarbon);

        $fechaInicio = $fechaInicioCarbon->format('Y-m-d');
        $fechaFin = $fechaFinCarbon->format('Y-m-d');

        return view('reportes.ventas-acumuladas', compact(
            'ventasPorCliente',
            'fechaInicio',
            'fechaFin',
            'totalVentas',
            'totalGanancia'
        ));
    }

    public function exportVentasAcumuladas(Request $request)
    {
        [$fechaInicioCarbon, $fechaFinCarbon] = $this->resolverRangoFechasVentas($request);

        [$ventasPorCliente, $totalVentas, $totalGanancia] = $this->obtenerDatosVentasAcumuladas($fechaInicioCarbon, $fechaFinCarbon);

        $datos = [
            'fechaInicio' => $fechaInicioCarbon->format('Y-m-d'),
            'fechaFin' => $fechaFinCarbon->format('Y-m-d'),
            'ventasPorCliente' => $ventasPorCliente,
            'totalVentas' => $totalVentas,
            'totalGanancia' => $totalGanancia,
        ];

        $nombreArchivo = sprintf(
            'ventas-acumuladas-%s-%s.xlsx',
            $fechaInicioCarbon->format('Ymd'),
            $fechaFinCarbon->format('Ymd')
        );

        return Excel::download(new VentasAcumuladasExport($datos), $nombreArchivo);
    }

    private function resolverRangoFechas(Request $request): array
    {
        $inicioPorDefecto = Carbon::now()->startOfWeek();
        $finPorDefecto = Carbon::now()->endOfWeek()->endOfDay();

        $fechaInicioInput = $request->get('fecha_inicio');
        $fechaFinInput = $request->get('fecha_fin');

        try {
            $fechaInicio = $fechaInicioInput
                ? Carbon::createFromFormat('Y-m-d', $fechaInicioInput)->startOfDay()
                : $inicioPorDefecto->copy();
        } catch (\Exception $e) {
            $fechaInicio = $inicioPorDefecto->copy();
        }

        try {
            $fechaFin = $fechaFinInput
                ? Carbon::createFromFormat('Y-m-d', $fechaFinInput)->endOfDay()
                : $finPorDefecto->copy();
        } catch (\Exception $e) {
            $fechaFin = $finPorDefecto->copy();
        }

        if ($fechaInicio->gt($fechaFin)) {
            $fechaInicio = $inicioPorDefecto->copy();
            $fechaFin = $finPorDefecto->copy();
        }

        return [$fechaInicio, $fechaFin];
    }

    private function resolverRangoFechasVentas(Request $request): array
    {
        $inicioPorDefecto = Carbon::now()->startOfMonth();
        $finPorDefecto = Carbon::now()->endOfMonth()->endOfDay();

        $fechaInicioInput = $request->get('fecha_inicio');
        $fechaFinInput = $request->get('fecha_fin');

        try {
            $fechaInicio = $fechaInicioInput
                ? Carbon::createFromFormat('Y-m-d', $fechaInicioInput)->startOfDay()
                : $inicioPorDefecto->copy();
        } catch (\Exception $e) {
            $fechaInicio = $inicioPorDefecto->copy();
        }

        try {
            $fechaFin = $fechaFinInput
                ? Carbon::createFromFormat('Y-m-d', $fechaFinInput)->endOfDay()
                : $finPorDefecto->copy();
        } catch (\Exception $e) {
            $fechaFin = $finPorDefecto->copy();
        }

        if ($fechaInicio->gt($fechaFin)) {
            $fechaInicio = $inicioPorDefecto->copy();
            $fechaFin = $finPorDefecto->copy();
        }

        return [$fechaInicio, $fechaFin];
    }

    private function obtenerDatosReporteGeneral(Carbon $fechaInicio, Carbon $fechaFin): array
    {
        $reservas = Reserva::with(['cliente', 'vehiculo'])
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();

        $totalReservas = $reservas->count();
        $totalCantidad = $reservas->sum('cantidad');
        $totalGanancia = $reservas->sum(function ($reserva) {
            return $reserva->cliente->precio_venta * $reserva->cantidad;
        });

        $reservasPorVehiculo = $reservas->groupBy('vehiculo_id')->map(function ($grupo) {
            return [
                'vehiculo' => $grupo->first()->vehiculo,
                'cantidad' => $grupo->sum('cantidad'),
                'ganancia' => $grupo->sum(function ($r) {
                    return $r->cliente->precio_venta * $r->cantidad;
                }),
                'reservas' => $grupo->count(),
            ];
        })->sortByDesc('ganancia');

        $reservasPorDia = $reservas->groupBy(function ($reserva) {
            return Carbon::parse($reserva->fecha)->format('Y-m-d');
        })->map(function ($grupo) {
            return [
                'fecha' => Carbon::parse($grupo->first()->fecha),
                'cantidad' => $grupo->sum('cantidad'),
                'ganancia' => $grupo->sum(function ($r) {
                    return $r->cliente->precio_venta * $r->cantidad;
                }),
                'reservas' => $grupo->count(),
                'items' => $grupo,
            ];
        })->sortBy('fecha');

        $reservasPorCliente = $reservas->groupBy('cliente_id')->map(function ($grupo) {
            return [
                'cliente' => $grupo->first()->cliente,
                'cantidad' => $grupo->sum('cantidad'),
                'ganancia' => $grupo->sum(function ($r) {
                    return $r->cliente->precio_venta * $r->cantidad;
                }),
                'reservas' => $grupo->count(),
            ];
        })->sortByDesc('ganancia');

        return [
            'reservas' => $reservas,
            'totalReservas' => $totalReservas,
            'totalCantidad' => $totalCantidad,
            'totalGanancia' => $totalGanancia,
            'reservasPorVehiculo' => $reservasPorVehiculo,
            'reservasPorDia' => $reservasPorDia,
            'reservasPorCliente' => $reservasPorCliente,
        ];
    }

    private function obtenerDatosVentasAcumuladas(Carbon $fechaInicio, Carbon $fechaFin): array
    {
        $reservas = Reserva::with(['cliente', 'vehiculo'])
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();

        $ventasPorCliente = [];

        foreach ($reservas as $reserva) {
            $clienteId = $reserva->cliente_id;
            $clienteNombre = $reserva->cliente->negocio;
            $precioVenta = $reserva->cliente->precio_venta;
            $cantidad = $reserva->cantidad;
            $ganancia = $precioVenta * $cantidad;
            $fecha = Carbon::parse($reserva->fecha);
            $mes = $fecha->format('Y-m');

            $diaDelMes = $fecha->day;
            $numeroSemana = ceil($diaDelMes / 7);

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

            if ($numeroSemana > 5) {
                $numeroSemana = 5;
            }

            $ventasPorCliente[$clienteId]['meses'][$mes]['semanas'][$numeroSemana]['cantidad'] += $cantidad;
            $ventasPorCliente[$clienteId]['meses'][$mes]['semanas'][$numeroSemana]['ganancia'] += $ganancia;

            $ventasPorCliente[$clienteId]['meses'][$mes]['venta_total'] += $cantidad;
            $ventasPorCliente[$clienteId]['meses'][$mes]['ganancia_total'] += $ganancia;

            $ventasPorCliente[$clienteId]['venta_total'] += $cantidad;
            $ventasPorCliente[$clienteId]['ganancia_total'] += $ganancia;
        }

        $totalVentas = $reservas->sum('cantidad');
        $totalGanancia = $reservas->sum(function ($reserva) {
            return $reserva->cliente->precio_venta * $reserva->cantidad;
        });

        return [$ventasPorCliente, $totalVentas, $totalGanancia];
    }
}
