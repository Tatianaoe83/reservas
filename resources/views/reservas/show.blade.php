<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle de Reserva') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Cliente</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $reserva->cliente->negocio }}</p>
                    </div>
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
                    @if($reserva->observaciones)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Observaciones</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $reserva->observaciones }}</p>
                    </div>
                    @endif
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('reservas.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Volver
                    </a>
                    <a href="{{ route('reservas.edit', $reserva) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Editar
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

