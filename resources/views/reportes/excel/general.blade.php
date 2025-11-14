<table>
    <tr>
        <th colspan="2">Resumen General</th>
    </tr>
    <tr>
        <td>Fecha inicio</td>
        <td>{{ $fechaInicio }}</td>
    </tr>
    <tr>
        <td>Fecha fin</td>
        <td>{{ $fechaFin }}</td>
    </tr>
    <tr>
        <td>Total de reservas</td>
        <td>{{ $totalReservas }}</td>
    </tr>
    <tr>
        <td>Total de cantidad</td>
        <td>{{ number_format($totalCantidad, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td>Total de ganancia</td>
        <td>{{ number_format($totalGanancia, 2, ',', '.') }}</td>
    </tr>
</table>

@if($reservasPorVehiculo->count())
<table>
    <tr>
        <th colspan="4">Resumen por Vehículo</th>
    </tr>
    <tr>
        <th>Vehículo</th>
        <th>Reservas</th>
        <th>Cantidad total</th>
        <th>Ganancia</th>
    </tr>
    @foreach($reservasPorVehiculo as $vehiculoData)
        <tr>
            <td>{{ $vehiculoData['vehiculo']->nombre }}</td>
            <td>{{ $vehiculoData['reservas'] }}</td>
            <td>{{ number_format($vehiculoData['cantidad'], 0, ',', '.') }}</td>
            <td>{{ number_format($vehiculoData['ganancia'], 2, ',', '.') }}</td>
        </tr>
    @endforeach
</table>
@endif

@if($reservasPorCliente->count())
<table>
    <tr>
        <th colspan="4">Resumen por Cliente</th>
    </tr>
    <tr>
        <th>Cliente</th>
        <th>Reservas</th>
        <th>Cantidad total</th>
        <th>Ganancia</th>
    </tr>
    @foreach($reservasPorCliente as $clienteData)
        <tr>
            <td>{{ $clienteData['cliente']->negocio }}</td>
            <td>{{ $clienteData['reservas'] }}</td>
            <td>{{ number_format($clienteData['cantidad'], 0, ',', '.') }}</td>
            <td>{{ number_format($clienteData['ganancia'], 2, ',', '.') }}</td>
        </tr>
    @endforeach
</table>
@endif

<table>
    <tr>
        <th colspan="7">Detalle de Reservas</th>
    </tr>
    <tr>
        <th>Fecha</th>
        <th>Hora</th>
        <th>Cliente</th>
        <th>Producto</th>
        <th>Vehículo</th>
        <th>Método de pago</th>
        <th>Estatus</th>
        <th>Cantidad</th>
        <th>Precio unitario</th>
        <th>Ganancia</th>
    </tr>
    @forelse($reservas as $reserva)
        <tr>
            <td>{{ $reserva->fecha->format('d/m/Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($reserva->hora)->format('h:i A') }}</td>
            <td>{{ $reserva->cliente->negocio }}</td>
            <td>{{ $reserva->cliente->producto->nombre ?? 'Sin producto' }}</td>
            <td>{{ $reserva->vehiculo->nombre }}</td>
            <td>{{ \App\Models\Reserva::TIPOS_PAGO[$reserva->tipo_pago ?? \App\Models\Reserva::TIPO_PAGO_CONTADO] ?? 'Contado' }}</td>
            <td>{{ \App\Models\Reserva::ESTATUS[$reserva->estatus ?? \App\Models\Reserva::ESTATUS_PROGRAMADO] ?? 'Programado' }}</td>
            <td>{{ number_format($reserva->cantidad, 0, ',', '.') }}</td>
            <td>{{ number_format($reserva->cliente->precio_venta, 2, ',', '.') }}</td>
            <td>{{ number_format($reserva->cliente->precio_venta * $reserva->cantidad, 2, ',', '.') }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="10">No hay reservas en el rango de fechas seleccionado.</td>
        </tr>
    @endforelse
</table>

