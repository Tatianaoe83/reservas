<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalle de Reserva') }}
            </h2>
            <a href="{{ route('reservas.index') }}"
               class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 px-4 rounded transition">
                ← Regresar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Cliente</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $reserva->cliente->negocio }}</p>
                    </div>
                    @if($reserva->cliente->producto)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Producto</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $reserva->cliente->producto->nombre }}</p>
                    </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Vehículo</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $reserva->vehiculo->nombre }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fecha</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $reserva->fecha->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Hora</label>
                        <p class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($reserva->hora)->format('h:i A') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Cantidad</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $reserva->cantidad }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipo de Pago</label>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ \App\Models\Reserva::TIPOS_PAGO[$reserva->tipo_pago ?? \App\Models\Reserva::TIPO_PAGO_CONTADO] ?? 'Contado' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Estatus</label>
                        @php
                            $estatusColors = [
                                'programado' => 'bg-yellow-100 text-yellow-800',
                                'entregado' => 'bg-green-100 text-green-800',
                                'no entregado' => 'bg-red-100 text-red-800',
                            ];
                            $color = $estatusColors[$reserva->estatus ?? 'programado'] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <p class="mt-1">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $color }}">
                                {{ \App\Models\Reserva::ESTATUS[$reserva->estatus ?? 'programado'] ?? 'Programado' }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Precio Unitario</label>
                        <p class="mt-1 text-sm text-gray-900">${{ number_format($precioUnitario, 2) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Total a Pagar</label>
                        <p class="mt-1 text-lg font-bold text-gray-900">${{ number_format($total, 2) }}</p>
                    </div>
                    @if($reserva->observaciones)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Observaciones</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $reserva->observaciones }}</p>
                    </div>
                    @endif
                </div>

                <div class="mt-6 flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('reservas.index') }}" class="inline-flex items-center justify-center bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Volver
                    </a>
                    <a href="{{ route('reservas.edit', $reserva) }}" class="inline-flex items-center justify-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Editar
                    </a>
                    @if($reserva->estatus === \App\Models\Reserva::ESTATUS_PROGRAMADO)
                        <a href="{{ route('reservas.ticket', $reserva) }}" target="_blank" class="inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Imprimir Ticket
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

