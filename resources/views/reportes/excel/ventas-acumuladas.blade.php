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
        <td>Total ventas (cantidad)</td>
        <td>{{ number_format($totalVentas, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td>Total ganancia</td>
        <td>{{ number_format($totalGanancia, 2, ',', '.') }}</td>
    </tr>
</table>

@forelse($ventasPorCliente as $clienteData)
<table>
    <tr>
        <th colspan="9">{{ $clienteData['cliente'] }}</th>
    </tr>
    <tr>
        <th>Mes</th>
        <th>Semana 1 (Cantidad / Ganancia)</th>
        <th>Semana 2 (Cantidad / Ganancia)</th>
        <th>Semana 3 (Cantidad / Ganancia)</th>
        <th>Semana 4 (Cantidad / Ganancia)</th>
        <th>Semana 5 (Cantidad / Ganancia)</th>
        <th>Venta total</th>
        <th>Ganancia total</th>
    </tr>
    @foreach($clienteData['meses'] as $mes => $mesData)
        @php
            $mesCarbon = \Carbon\Carbon::parse($mes . '-01');
            $meses = [
                'January' => 'Enero', 'February' => 'Febrero', 'March' => 'Marzo',
                'April' => 'Abril', 'May' => 'Mayo', 'June' => 'Junio',
                'July' => 'Julio', 'August' => 'Agosto', 'September' => 'Septiembre',
                'October' => 'Octubre', 'November' => 'Noviembre', 'December' => 'Diciembre'
            ];
            $nombreMes = $meses[$mesCarbon->format('F')] ?? $mesCarbon->format('F');
        @endphp
        <tr>
            <td>{{ $nombreMes }} {{ $mesCarbon->format('Y') }}</td>
            @for($semana = 1; $semana <= 5; $semana++)
                <td>
                    @if($mesData['semanas'][$semana]['cantidad'] > 0)
                        {{ number_format($mesData['semanas'][$semana]['cantidad'], 0, ',', '.') }} /
                        {{ number_format($mesData['semanas'][$semana]['ganancia'], 2, ',', '.') }}
                    @else
                        -
                    @endif
                </td>
            @endfor
            <td>{{ number_format($mesData['venta_total'], 0, ',', '.') }}</td>
            <td>{{ number_format($mesData['ganancia_total'], 2, ',', '.') }}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="6">Total {{ $clienteData['cliente'] }}</td>
        <td>{{ number_format($clienteData['venta_total'], 0, ',', '.') }}</td>
        <td>{{ number_format($clienteData['ganancia_total'], 2, ',', '.') }}</td>
    </tr>
</table>
@empty
<table>
    <tr>
        <td>No hay ventas registradas en el rango de fechas seleccionado.</td>
    </tr>
</table>
@endforelse

