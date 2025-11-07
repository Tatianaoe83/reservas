<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reportes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Reporte General (Unificado) -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 hover:shadow-2xl transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-lg p-3">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-bold text-gray-900">Reporte General</h3>
                            <p class="text-sm text-gray-500">Visualiza reservas con filtros de fecha personalizados</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">
                        Filtra reservas por rango de fechas, ver por día, vehículo, cliente y ganancias.
                    </p>
                    <a href="{{ route('reportes.general') }}" class="w-full bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-bold py-3 px-6 rounded-lg inline-flex items-center justify-center transition-all transform hover:-translate-y-1">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Ver Reporte General
                    </a>
                </div>

                <!-- Reporte Ventas Acumuladas -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 hover:shadow-2xl transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg p-3">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-bold text-gray-900">Ventas Acumuladas</h3>
                            <p class="text-sm text-gray-500">Reporte detallado por cliente, mes y semanas</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">
                        Analiza ventas y ganancias agrupadas por cliente, con desglose mensual y semanal.
                    </p>
                    <a href="{{ route('reportes.ventas-acumuladas') }}" class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-bold py-3 px-6 rounded-lg inline-flex items-center justify-center transition-all transform hover:-translate-y-1">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Ver Ventas Acumuladas
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

