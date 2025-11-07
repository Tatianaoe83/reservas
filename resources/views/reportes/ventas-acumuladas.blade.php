<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900">
                    {{ __('Ventas Acumuladas') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Reporte de ventas con ganancias por cliente</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filtros -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                <form method="GET" action="{{ route('reportes.ventas-acumuladas') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha Inicio
                        </label>
                        <input type="date" 
                               name="fecha_inicio" 
                               id="fecha_inicio" 
                               value="{{ $fechaInicio }}" 
                               required
                               class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha Fin
                        </label>
                        <input type="date" 
                               name="fecha_fin" 
                               id="fecha_fin" 
                               value="{{ $fechaFin }}" 
                               required
                               class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors">
                            <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filtrar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Resumen Total -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 overflow-hidden shadow-xl rounded-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium uppercase tracking-wider">Total Ventas (Cantidad)</p>
                            <p class="text-3xl font-bold mt-2">{{ number_format($totalVentas, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-white/20 rounded-full p-4">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-green-500 to-green-600 overflow-hidden shadow-xl rounded-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium uppercase tracking-wider">Total Ganancia</p>
                            <p class="text-3xl font-bold mt-2">${{ number_format($totalGanancia, 2, ',', '.') }}</p>
                        </div>
                        <div class="bg-white/20 rounded-full p-4">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Semanas -->
            <div class="mb-4">
                <div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm text-blue-900">
                    <svg class="w-5 h-5 text-blue-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="font-semibold">¿Cómo se calculan las semanas?</p>
                        <p class="mt-1">Cada reserva se asigna a una de las cinco semanas del mes según el día registrado: Semana 1 (días 1-7), Semana 2 (8-14), Semana 3 (15-21), Semana 4 (22-28) y Semana 5 agrupa los días restantes del mes.</p>
                    </div>
                </div>
            </div>

            <!-- Tabla de Ventas Acumuladas -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[960px] md:min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-blue-600 to-blue-700">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider border-r border-blue-500">
                                    Cliente
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                                    Mes
                                </th>
                                <th scope="col" class="px-4 py-4 text-center text-xs font-bold text-white uppercase tracking-wider bg-blue-500">
                                    Semana 1
                                </th>
                                <th scope="col" class="px-4 py-4 text-center text-xs font-bold text-white uppercase tracking-wider bg-blue-500">
                                    Semana 2
                                </th>
                                <th scope="col" class="px-4 py-4 text-center text-xs font-bold text-white uppercase tracking-wider bg-blue-500">
                                    Semana 3
                                </th>
                                <th scope="col" class="px-4 py-4 text-center text-xs font-bold text-white uppercase tracking-wider bg-blue-500">
                                    Semana 4
                                </th>
                                <th scope="col" class="px-4 py-4 text-center text-xs font-bold text-white uppercase tracking-wider bg-blue-500">
                                    Semana 5
                                </th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider border-l-2 border-blue-500">
                                    Venta Total
                                </th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">
                                    Ganancia Total
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($ventasPorCliente as $clienteId => $clienteData)
                                @php
                                    $mesesCount = count($clienteData['meses']);
                                    $rowspan = $mesesCount > 0 ? $mesesCount : 1;
                                    $firstRow = true;
                                @endphp
                                @foreach($clienteData['meses'] as $mes => $mesData)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        @if($firstRow)
                                            <td rowspan="{{ $rowspan }}" class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 align-top border-r-2 border-gray-200 bg-gray-50">
                                                {{ $clienteData['cliente'] }}
                                            </td>
                                            @php $firstRow = false; @endphp
                                        @endif
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-700">
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
                                            {{ $nombreMes }} {{ $mesCarbon->format('Y') }}
                                        </td>
                                        @for($semana = 1; $semana <= 5; $semana++)
                                            <td class="px-3 py-4 text-sm text-center {{ $semana % 2 == 0 ? 'bg-gray-50' : 'bg-white' }}">
                                                @if($mesData['semanas'][$semana]['cantidad'] > 0)
                                                    <div class="text-gray-900 font-bold mb-1">
                                                        {{ number_format($mesData['semanas'][$semana]['cantidad'], 0, ',', '.') }}
                                                    </div>
                                                    <div class="text-green-600 font-semibold text-xs">
                                                        ${{ number_format($mesData['semanas'][$semana]['ganancia'], 2, ',', '.') }}
                                                    </div>
                                                @else
                                                    <span class="text-gray-300">-</span>
                                                @endif
                                            </td>
                                        @endfor
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-bold text-gray-900 border-l-2 border-gray-200">
                                            {{ number_format($mesData['venta_total'], 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-bold text-green-600">
                                            ${{ number_format($mesData['ganancia_total'], 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                                @if($rowspan > 0)
                                    <tr class="bg-gradient-to-r from-blue-50 to-blue-100 font-bold border-t-2 border-blue-300">
                                        <td colspan="7" class="px-6 py-3 text-right text-sm text-gray-700">
                                            Total {{ $clienteData['cliente'] }}:
                                        </td>
                                        <td class="px-6 py-3 text-center text-sm font-bold text-gray-900 border-l-2 border-blue-300">
                                            {{ number_format($clienteData['venta_total'], 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-3 text-center text-sm font-bold text-blue-600">
                                            ${{ number_format($clienteData['ganancia_total'], 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <p class="text-sm font-medium text-gray-500">No hay ventas registradas en el rango de fechas seleccionado.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if(count($ventasPorCliente) > 0)
                        <tfoot class="bg-gradient-to-r from-gray-800 to-gray-900 font-bold">
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-right text-sm text-white">
                                    TOTAL GENERAL:
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-white border-l-2 border-gray-700">
                                    {{ number_format($totalVentas, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-green-400">
                                    ${{ number_format($totalGanancia, 2, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-end">
                <a href="{{ route('reportes.ventas-acumuladas.export', ['fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]) }}"
                   class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-colors text-center">
                    <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17l4 4m0 0l4-4m-4 4V3" />
                    </svg>
                    Exportar a Excel
                </a>
                <button onclick="window.print()" 
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition-colors">
                    <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Imprimir Reporte
                </button>
            </div>
        </div>
    </div>
</x-app-layout>

