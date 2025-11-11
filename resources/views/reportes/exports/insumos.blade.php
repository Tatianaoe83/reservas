<table>
    <tr>
        <td colspan="7" style="font-weight: bold; font-size: 16px;">Reporte de Insumos</td>
    </tr>
    <tr>
        <td colspan="7">
            Periodo: {{ $fechaInicio->format('d/m/Y') }} al {{ $fechaFin->format('d/m/Y') }}
        </td>
    </tr>
    <tr></tr>
    @foreach ($secciones as $categoria => $datos)
        <tr>
            <td colspan="7" style="font-weight: bold; background-color: #E2E8F0;">{{ $categoria }}</td>
        </tr>
        @if ($categoria === 'Combustible')
            <tr>
                <th>Nombre</th>
                <th>Fecha</th>
                <th>Lectura inicial</th>
                <th>Lectura final</th>
                <th>Consumo (L)</th>
                <th>Precio x Lt</th>
                <th>Costo semanal</th>
            </tr>
            @forelse ($datos['items'] as $insumo)
                <tr>
                    <td>{{ $insumo->nombre }}</td>
                    <td>{{ $insumo->fecha?->format('d/m/Y') }}</td>
                    <td>{{ number_format($insumo->lectura_inicial_combustible ?? 0, 2) }}</td>
                    <td>{{ number_format($insumo->lectura_final_combustible ?? 0, 2) }}</td>
                    <td>{{ number_format($insumo->litros_consumidos ?? 0, 2) }}</td>
                    <td>{{ number_format($insumo->precio_combustible ?? 0, 2) }}</td>
                    <td>{{ number_format($insumo->importe_semanal, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Sin registros en este periodo.</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="4" style="font-weight: bold; text-align: right;">Totales</td>
                <td style="font-weight: bold;">{{ number_format($datos['totales']['consumo_combustible'], 2) }}</td>
                <td></td>
                <td style="font-weight: bold;">{{ number_format($datos['totales']['importe'], 2) }}</td>
            </tr>
        @else
            <tr>
                <th>Nombre</th>
                <th>Fecha</th>
                <th>Cantidad</th>
                <th>Sueldo semanal</th>
                <th colspan="3">Importe</th>
            </tr>
            @forelse ($datos['items'] as $insumo)
                <tr>
                    <td>{{ $insumo->nombre }}</td>
                    <td>{{ $insumo->fecha?->format('d/m/Y') }}</td>
                    <td>{{ number_format($insumo->cantidad) }}</td>
                    <td>{{ number_format($insumo->sueldo_semana, 2) }}</td>
                    <td colspan="3">{{ number_format($insumo->importe_semanal, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Sin registros en este periodo.</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="2" style="font-weight: bold; text-align: right;">Totales</td>
                <td style="font-weight: bold;">{{ number_format($datos['totales']['cantidad']) }}</td>
                <td style="font-weight: bold;">{{ number_format($datos['totales']['sueldo'], 2) }}</td>
                <td colspan="3" style="font-weight: bold;">{{ number_format($datos['totales']['importe'], 2) }}</td>
            </tr>
        @endif
        <tr></tr>
    @endforeach
    <tr>
        <td colspan="4" style="font-weight: bold; text-align: right;">Total combustible (L)</td>
        <td colspan="3">{{ number_format($totalCombustibleLitros, 2) }}</td>
    </tr>
    <tr>
        <td colspan="4" style="font-weight: bold; text-align: right;">Total semanal</td>
        <td colspan="3">{{ number_format($totalGeneral, 2) }}</td>
    </tr>
</table>

