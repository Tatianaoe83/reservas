<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-gray-900">
                    {{ __('Ingresos vs Costos') }}
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">
                    Compara las ventas registradas en reservas contra los insumos para medir la rentabilidad.
                </p>
            </div>
            <a href="{{ route('reportes.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition-colors text-sm sm:text-base">
                Volver a Reportes
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Filtros -->
            <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-100">
                <form method="GET" class="grid gap-6 md:grid-cols-4 items-end">
                    <div class="md:col-span-2">
                        <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-1">Fecha inicio</label>
                        <input
                            type="date"
                            id="fecha_inicio"
                            name="fecha_inicio"
                            value="{{ request('fecha_inicio', $fechaInicio) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-orange-500 focus:ring-orange-500"
                        >
                    </div>
                    <div class="md:col-span-2">
                        <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-1">Fecha fin</label>
                        <input
                            type="date"
                            id="fecha_fin"
                            name="fecha_fin"
                            value="{{ request('fecha_fin', $fechaFin) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-orange-500 focus:ring-orange-500"
                        >
                    </div>
                    <div class="md:col-span-4 flex flex-wrap items-center gap-3">
                        <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg bg-orange-600 text-white hover:bg-orange-700 font-semibold transition-colors duration-150">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Aplicar filtros
                        </button>
                        <a href="{{ route('reportes.rentabilidad') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 font-semibold transition-colors duration-150">
                            Limpiar
                        </a>
                        <span class="text-sm text-gray-500">
                            Periodo analizado: <strong class="text-gray-700">{{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }}</strong> al <strong class="text-gray-700">{{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</strong>
                        </span>
                    </div>
                </form>
            </div>

            <!-- Resumen -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-2xl p-6 shadow-lg">
                    <p class="text-sm uppercase font-semibold text-emerald-100 tracking-wide">Ventas totales</p>
                    <p class="text-3xl font-bold mt-3">${{ number_format($totalVentas, 2) }}</p>
                </div>
                <div class="bg-gradient-to-r from-amber-500 to-amber-600 text-white rounded-2xl p-6 shadow-lg">
                    <p class="text-sm uppercase font-semibold text-amber-100 tracking-wide">Costos de insumos</p>
                    <p class="text-3xl font-bold mt-3">${{ number_format($totalCostos, 2) }}</p>
                </div>
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-2xl p-6 shadow-lg">
                    <p class="text-sm uppercase font-semibold text-blue-100 tracking-wide">Utilidad</p>
                    <p class="text-3xl font-bold mt-3">${{ number_format($utilidad, 2) }}</p>
                    <p class="mt-2 text-sm {{ $utilidad >= 0 ? 'text-blue-100' : 'text-red-100' }}">
                        {{ $utilidad >= 0 ? 'Resultado positivo' : 'Resultado negativo' }}
                    </p>
                </div>
                <div class="bg-gradient-to-r from-rose-500 to-rose-600 text-white rounded-2xl p-6 shadow-lg">
                    <p class="text-sm uppercase font-semibold text-rose-100 tracking-wide">Margen</p>
                    <p class="text-3xl font-bold mt-3">{{ number_format($margen, 2) }}%</p>
                    <p class="mt-2 text-sm text-rose-100">
                        Por cada $100 vendidos quedan ${{ number_format($totalVentas > 0 ? ($utilidad / $totalVentas) * 100 : 0, 2) }} de utilidad.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <!-- Costos por categoría -->
                <div class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Costos por Categoría de Insumos</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Categoría</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Cantidad</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Consumo (L)</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Costo</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($costosPorCategoria as $categoria)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $categoria['categoria'] }}</td>
                                        <td class="px-6 py-4 text-sm text-right text-gray-600">{{ number_format($categoria['cantidad']) }}</td>
                                        <td class="px-6 py-4 text-sm text-right text-gray-600">
                                            {{ $categoria['consumo'] > 0 ? number_format($categoria['consumo'], 2) . ' L' : '—' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-right font-semibold text-rose-600">${{ number_format($categoria['importe'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 font-semibold">
                                <tr>
                                    <td class="px-6 py-3 text-right text-gray-600" colspan="3">Total costos</td>
                                    <td class="px-6 py-3 text-right text-rose-600">${{ number_format($totalCostos, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                    <!-- Comparativo diario -->
                <div class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Comparativo diario</h3>
                            <p class="text-sm text-gray-500">Ventas vs costos por día dentro del rango seleccionado.</p>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Ventas</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Costos</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Utilidad</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($comparativoDiario as $dia)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $dia['fecha']->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 text-sm text-right text-emerald-600">${{ number_format($dia['ingresos'], 2) }}</td>
                                        <td class="px-6 py-4 text-sm text-right text-rose-600">${{ number_format($dia['costos'], 2) }}</td>
                                        <td class="px-6 py-4 text-sm text-right font-semibold {{ $dia['utilidad'] >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                                            ${{ number_format($dia['utilidad'], 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">
                                            No hay registros para mostrar en el periodo seleccionado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Extra: detalle -->
            <div class="bg-white shadow-xl rounded-2xl border border-gray-100 p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Detalle rápido</h3>
                        <p class="text-sm text-gray-500">Estos datos provienen de los reportes individuales de reservas e insumos.</p>
                    </div>
                    <div class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-100 text-emerald-700 font-semibold">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Ventas promedio por reserva: ${{ number_format($datosReservas['totalReservas'] > 0 ? $totalVentas / $datosReservas['totalReservas'] : 0, 2) }}
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div class="bg-gray-50 rounded-xl p-5 border border-dashed border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">Top clientes por ventas</h4>
                        <ul class="space-y-3">
                            @foreach ($datosReservas['reservasPorCliente']->take(5) as $cliente)
                                <li class="flex items-center justify-between text-sm">
                                    <span class="text-gray-700">{{ $cliente['cliente']->negocio }}</span>
                                    <span class="font-semibold text-emerald-600">${{ number_format($cliente['ganancia'], 2) }}</span>
                                </li>
                            @endforeach
                            @if ($datosReservas['reservasPorCliente']->isEmpty())
                                <li class="text-sm text-gray-500">Sin datos en este periodo.</li>
                            @endif
                        </ul>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-5 border border-dashed border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">Categorías más costosas</h4>
                        <ul class="space-y-3">
                            @foreach ($costosPorCategoria->sortByDesc('importe')->take(5) as $categoria)
                                <li class="flex items-center justify-between text-sm">
                                    <span class="text-gray-700">{{ $categoria['categoria'] }}</span>
                                    <span class="font-semibold text-rose-600">${{ number_format($categoria['importe'], 2) }}</span>
                                </li>
                            @endforeach
                            @if ($costosPorCategoria->isEmpty())
                                <li class="text-sm text-gray-500">Sin insumos registrados.</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

