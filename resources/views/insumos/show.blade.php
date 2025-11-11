<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle de Insumo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl">
                <div class="px-8 py-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-sm text-gray-500 uppercase tracking-wide">Insumo</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $insumo->nombre }}</h3>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-slate-100 text-slate-800">
                        {{ $insumo->categoria }}
                    </span>
                </div>

                <div class="px-8 py-6 space-y-8">
                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500">Cantidad</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ number_format($insumo->cantidad) }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500">Fecha</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $insumo->fecha?->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500">Sueldo semanal</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900">${{ number_format($insumo->sueldo_semana, 2) }} MXN</p>
                        </div>
                    </div>

                    @if ($insumo->esCombustible())
                        <div class="border border-amber-200 bg-amber-50 rounded-xl p-6 space-y-4">
                            <h4 class="text-lg font-semibold text-amber-700">Información de combustible</h4>
                            <div class="grid gap-6 sm:grid-cols-2">
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-gray-500">Lectura inicial</p>
                                    <p class="mt-1 text-lg font-semibold text-gray-900">
                                        {{ number_format($insumo->lectura_inicial_combustible ?? 0, 2) }} L
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-gray-500">Lectura final</p>
                                    <p class="mt-1 text-lg font-semibold text-gray-900">
                                        {{ number_format($insumo->lectura_final_combustible ?? 0, 2) }} L
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-gray-500">Consumo registrado</p>
                                    <p class="mt-1 text-lg font-semibold text-gray-900">
                                        @if (!is_null($insumo->litros_consumidos))
                                            {{ number_format($insumo->litros_consumidos, 2) }} L
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-gray-500">Precio gasolina</p>
                                    <p class="mt-1 text-lg font-semibold text-gray-900">
                                        @if (!is_null($insumo->precio_combustible))
                                            ${{ number_format($insumo->precio_combustible, 2) }} MXN
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                                <div class="sm:col-span-2">
                                    <p class="text-xs uppercase tracking-wide text-gray-500">Costo calculado</p>
                                    <p class="mt-1 text-lg font-semibold text-gray-900">
                                        @if (!is_null($insumo->costo_combustible))
                                            ${{ number_format($insumo->costo_combustible, 2) }} MXN
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500">Creado</p>
                            <p class="mt-1 text-sm font-medium text-gray-700">{{ $insumo->created_at?->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500">Actualizado</p>
                            <p class="mt-1 text-sm font-medium text-gray-700">{{ $insumo->updated_at?->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <div class="px-8 py-6 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <a href="{{ route('insumos.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 font-semibold transition-colors duration-150">
                        Volver al listado
                    </a>
                    <div class="flex gap-3">
                        <a href="{{ route('insumos.edit', $insumo) }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-500 text-white hover:bg-blue-600 font-semibold transition-colors duration-150">
                            Editar insumo
                        </a>
                        <form action="{{ route('insumos.destroy', $insumo) }}" method="POST" onsubmit="return confirm('¿Deseas eliminar este insumo?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600 font-semibold transition-colors duration-150">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

