<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Reporte de Insumos') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Consulta los insumos registrados por categoría dentro de un rango de fechas específico.
                </p>
            </div>
            <a href="{{ route('insumos.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-100 text-emerald-700 hover:bg-emerald-200 font-semibold transition-colors duration-150">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Registrar insumo
            </a>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="bg-white shadow-xl sm:rounded-2xl p-6 sm:p-8 border border-gray-100">
                <form method="GET" class="grid gap-6 md:grid-cols-4 items-end">
                    <div class="md:col-span-2">
                        <label for="fecha_inicio" class="block text-sm font-medium text-gray-700">Fecha inicio</label>
                        <input
                            type="date"
                            id="fecha_inicio"
                            name="fecha_inicio"
                            value="{{ request('fecha_inicio', $fechaInicio) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                        >
                    </div>
                    <div class="md:col-span-2">
                        <label for="fecha_fin" class="block text-sm font-medium text-gray-700">Fecha fin</label>
                        <input
                            type="date"
                            id="fecha_fin"
                            name="fecha_fin"
                            value="{{ request('fecha_fin', $fechaFin) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                        >
                    </div>
                    <div class="md:col-span-4 flex flex-wrap items-center gap-3">
                        <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 font-semibold transition-colors duration-150">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Aplicar filtros
                        </button>
                        <a href="{{ route('reportes.insumos') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 font-semibold transition-colors duration-150">
                            Limpiar
                        </a>
                        <a
                            href="{{ route('reportes.insumos.export', ['fecha_inicio' => request('fecha_inicio', $fechaInicio), 'fecha_fin' => request('fecha_fin', $fechaFin)]) }}"
                            class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-100 text-emerald-700 hover:bg-emerald-200 font-semibold transition-colors duration-150"
                        >
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Exportar Excel
                        </a>
                        <span class="text-sm text-gray-500">
                            Mostrando registros del <strong class="text-gray-700">{{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }}</strong> al <strong class="text-gray-700">{{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</strong>.
                        </span>
                    </div>
                </form>
            </div>

            @foreach ($secciones as $categoria => $datos)
                <div class="bg-white shadow-xl sm:rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $categoria }}</h3>
                            <p class="text-sm text-gray-500">
                                {{ $datos['items']->isEmpty() ? 'Sin registros en el periodo seleccionado.' : $datos['items']->count() . ' registros encontrados.' }}
                            </p>
                        </div>
                        <div class="flex flex-wrap items-center gap-4">
                            <div>
                                <p class="text-xs uppercase text-gray-500">Total semanal</p>
                                <p class="text-lg font-semibold text-gray-900">${{ number_format($datos['totales']['importe'], 2) }}</p>
                            </div>
                            @if ($categoria === 'Combustible')
                                <div>
                                    <p class="text-xs uppercase text-gray-500">Consumo total (L)</p>
                                    <p class="text-lg font-semibold text-gray-900">
                                        {{ number_format($datos['totales']['consumo_combustible'], 2) }}
                                    </p>
                                </div>
                            @else
                                <div>
                                    <p class="text-xs uppercase text-gray-500">Total cantidad</p>
                                    <p class="text-lg font-semibold text-gray-900">
                                        {{ number_format($datos['totales']['cantidad']) }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if ($categoria === 'Combustible')
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Concepto</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Lectura inicial</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Lectura final</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Consumo (L)</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Precio x Lt</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Costo semanal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($datos['items'] as $insumo)
                                        <tr>
                                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $insumo->nombre }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">{{ $insumo->fecha?->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 text-sm text-right text-gray-600">{{ number_format($insumo->lectura_inicial_combustible ?? 0, 2) }}</td>
                                            <td class="px-6 py-4 text-sm text-right text-gray-600">{{ number_format($insumo->lectura_final_combustible ?? 0, 2) }}</td>
                                            <td class="px-6 py-4 text-sm text-right text-gray-900 font-semibold">
                                                {{ number_format($insumo->litros_consumidos ?? 0, 2) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-right text-gray-600">${{ number_format($insumo->precio_combustible ?? 0, 2) }}</td>
                                            <td class="px-6 py-4 text-sm text-right text-gray-900 font-semibold">
                                                ${{ number_format($insumo->importe_semanal, 2) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-6 text-center text-sm text-gray-500">
                                                No se registraron insumos de combustible en este periodo.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <th colspan="4" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Totales</th>
                                        <th class="px-6 py-3 text-right text-sm font-bold text-gray-900">{{ number_format($datos['totales']['consumo_combustible'], 2) }} L</th>
                                        <th class="px-6 py-3"></th>
                                        <th class="px-6 py-3 text-right text-sm font-bold text-gray-900">${{ number_format($datos['totales']['importe'], 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Concepto</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Cantidad</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Sueldo semanal</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Importe</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($datos['items'] as $insumo)
                                        <tr>
                                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $insumo->nombre }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">{{ $insumo->fecha?->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 text-sm text-right text-gray-600">{{ number_format($insumo->cantidad) }}</td>
                                            <td class="px-6 py-4 text-sm text-right text-gray-600">${{ number_format($insumo->sueldo_semana, 2) }}</td>
                                            <td class="px-6 py-4 text-sm text-right text-gray-900 font-semibold">${{ number_format($insumo->importe_semanal, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-6 text-center text-sm text-gray-500">
                                                No se registraron insumos en esta categoría durante el periodo seleccionado.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <th colspan="2" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Totales</th>
                                        <th class="px-6 py-3 text-right text-sm font-bold text-gray-900">{{ number_format($datos['totales']['cantidad']) }}</th>
                                        <th class="px-6 py-3 text-right text-sm font-bold text-gray-900">${{ number_format($datos['totales']['sueldo'], 2) }}</th>
                                        <th class="px-6 py-3 text-right text-sm font-bold text-gray-900">${{ number_format($datos['totales']['importe'], 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
            @endforeach

            <div class="bg-white shadow-xl sm:rounded-2xl border border-gray-100 p-6 sm:p-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h4 class="text-lg font-semibold text-gray-900">Total semanal consolidado</h4>
                    <p class="text-sm text-gray-500">Suma de todas las categorías en el rango de fechas seleccionado.</p>
                </div>
                <div class="text-right">
                    <p class="text-xs uppercase text-gray-500">Total combustible (L)</p>
                    <p class="text-lg font-semibold text-gray-900">{{ number_format($totalCombustibleLitros, 2) }} litros</p>
                </div>
                <div class="text-right">
                    <p class="text-xs uppercase text-gray-500">Total semanal</p>
                    <p class="text-2xl font-bold text-emerald-600">${{ number_format($totalGeneral, 2) }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

