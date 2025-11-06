<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reportes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Reporte Semanal -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Reporte Semanal</h3>
                    <p class="text-sm text-gray-500 mb-4">Visualiza las reservas por semana</p>
                    <a href="{{ route('reportes.semanal') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block">
                        Ver Reporte Semanal
                    </a>
                </div>

                <!-- Reporte Mensual -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Reporte Mensual</h3>
                    <p class="text-sm text-gray-500 mb-4">Visualiza las reservas por mes</p>
                    <a href="{{ route('reportes.mensual') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-block">
                        Ver Reporte Mensual
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

