<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-gray-900">
            {{ __('Dashboard') }}
        </h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">Sistema de Reservas - Venta de Hielo</p>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 px-6 py-4 rounded-lg shadow-md flex items-center">
                    <svg class="h-6 w-6 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Tarjetas de Acceso Rápido -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Card Clientes -->
                <a href="{{ route('clientes.index') }}" class="group bg-gradient-to-br from-blue-500 to-blue-600 overflow-hidden shadow-xl rounded-2xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex-shrink-0 bg-white/20 backdrop-blur-sm rounded-xl p-3 group-hover:scale-110 transition-transform duration-300">
                                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <svg class="h-5 w-5 text-white/80 group-hover:text-white group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-1">Clientes</h3>
                        <p class="text-sm text-blue-100 mb-3">Gestionar clientes</p>
                        <div class="flex items-center justify-between">
                            <span class="text-3xl font-black text-white">{{ $totalClientes ?? 0 }}</span>
                            <span class="text-xs text-blue-100 uppercase tracking-wider">Registros</span>
                        </div>
                    </div>
                </a>

                <!-- Card Vehículos -->
                <a href="{{ route('vehiculos.index') }}" class="group bg-gradient-to-br from-green-500 to-emerald-600 overflow-hidden shadow-xl rounded-2xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex-shrink-0 bg-white/20 backdrop-blur-sm rounded-xl p-3 group-hover:scale-110 transition-transform duration-300">
                                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </div>
                            <svg class="h-5 w-5 text-white/80 group-hover:text-white group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-1">Vehículos</h3>
                        <p class="text-sm text-green-100 mb-3">Gestionar vehículos</p>
                        <div class="flex items-center justify-between">
                            <span class="text-3xl font-black text-white">{{ $totalVehiculos ?? 0 }}</span>
                            <span class="text-xs text-green-100 uppercase tracking-wider">Registros</span>
                        </div>
                    </div>
                </a>

                <!-- Card Reservas -->
                <a href="{{ route('reservas.index') }}" class="group bg-gradient-to-br from-purple-500 to-pink-600 overflow-hidden shadow-xl rounded-2xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex-shrink-0 bg-white/20 backdrop-blur-sm rounded-xl p-3 group-hover:scale-110 transition-transform duration-300">
                                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <svg class="h-5 w-5 text-white/80 group-hover:text-white group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-1">Reservas</h3>
                        <p class="text-sm text-purple-100 mb-3">Gestionar reservas</p>
                        <div class="flex items-center justify-between">
                            <span class="text-3xl font-black text-white">{{ $totalReservas ?? 0 }}</span>
                            <span class="text-xs text-purple-100 uppercase tracking-wider">Registros</span>
                        </div>
                    </div>
                </a>

                <!-- Card Reportes -->
                <a href="{{ route('reportes.index') }}" class="group bg-gradient-to-br from-red-500 to-orange-600 overflow-hidden shadow-xl rounded-2xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex-shrink-0 bg-white/20 backdrop-blur-sm rounded-xl p-3 group-hover:scale-110 transition-transform duration-300">
                                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <svg class="h-5 w-5 text-white/80 group-hover:text-white group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-1">Reportes</h3>
                        <p class="text-sm text-red-100 mb-3">Ver reportes</p>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-white">{{ $reservasEstaSemana ?? 0 }}</span>
                            <span class="text-xs text-red-100 uppercase tracking-wider">Esta Semana</span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Estadísticas Adicionales -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- Reservas Entregadas -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-green-700 uppercase tracking-wider mb-2">Entregadas</p>
                            <p class="text-4xl font-black text-green-900">{{ $reservasEntregadas ?? 0 }}</p>
                        </div>
                        <div class="bg-green-500 rounded-xl p-4">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Reservas No Entregadas -->
                <div class="bg-gradient-to-br from-red-50 to-orange-50 border-2 border-red-200 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-red-700 uppercase tracking-wider mb-2">No Entregadas</p>
                            <p class="text-4xl font-black text-red-900">{{ $reservasNoEntregadas ?? 0 }}</p>
                        </div>
                        <div class="bg-red-500 rounded-xl p-4">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Reservas de Hoy -->
                <div class="bg-gradient-to-br from-cyan-50 to-blue-50 border-2 border-cyan-200 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-cyan-700 uppercase tracking-wider mb-2">Reservas Hoy</p>
                            <p class="text-4xl font-black text-cyan-900">{{ $reservasHoy ?? 0 }}</p>
                        </div>
                        <div class="bg-cyan-500 rounded-xl p-4">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accesos Rápidos -->
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100">
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="h-6 w-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Accesos Rápidos
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <a href="{{ route('reservas.create') }}" class="group relative p-6 bg-gradient-to-br from-blue-50 to-cyan-50 border-2 border-blue-200 rounded-xl hover:border-blue-400 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-blue-500 rounded-lg p-3 group-hover:scale-110 transition-transform">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors">Nueva Reserva</h4>
                                    <p class="text-sm text-gray-600 mt-1">Crear una nueva reserva</p>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('reservas.calendario') }}" class="group relative p-6 bg-gradient-to-br from-purple-50 to-pink-50 border-2 border-purple-200 rounded-xl hover:border-purple-400 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-purple-500 rounded-lg p-3 group-hover:scale-110 transition-transform">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-bold text-gray-900 group-hover:text-purple-600 transition-colors">Calendario</h4>
                                    <p class="text-sm text-gray-600 mt-1">Ver calendario de reservas</p>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('reportes.general') }}" class="group relative p-6 bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl hover:border-green-400 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-500 rounded-lg p-3 group-hover:scale-110 transition-transform">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-bold text-gray-900 group-hover:text-green-600 transition-colors">Reporte General</h4>
                                    <p class="text-sm text-gray-600 mt-1">Ver reportes con filtros</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
