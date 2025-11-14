<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-gray-900">
                    {{ __('Reservas') }}
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">Gestiona las reservas de venta de hielo</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 sm:space-x-3">
                <a href="{{ route('reservas.calendario') }}" class="inline-flex items-center justify-center px-4 py-2 sm:px-5 sm:py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 text-sm sm:text-base">
                    <svg class="h-4 w-4 sm:h-5 sm:w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Calendario
                </a>
                <a href="{{ route('reservas.create') }}" class="inline-flex items-center justify-center px-4 py-2 sm:px-6 sm:py-3 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 text-sm sm:text-base">
                    <svg class="h-4 w-4 sm:h-5 sm:w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="hidden sm:inline">Nueva Reserva</span>
                    <span class="sm:hidden">Nueva</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 px-6 py-4 rounded-lg shadow-md flex items-center">
                    <svg class="h-6 w-6 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100">
                <div class="p-6">
                    <table id="reservasTable" class="display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Cliente</th>
                                <th>Vehículo</th>
                                <th>Cantidad</th>
                                <th>Tipo de Pago</th>
                                <th>Total</th>
                                <th>Estatus</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reservas as $reserva)
                                @php
                                    $precioUnitario = $reserva->cliente->precio_venta ?? 0;
                                    $total = $precioUnitario * $reserva->cantidad;
                                    $tipoPago = \App\Models\Reserva::TIPOS_PAGO[$reserva->tipo_pago ?? \App\Models\Reserva::TIPO_PAGO_CONTADO] ?? 'Contado';
                                    $tipoPagoColors = [
                                        'contado' => 'bg-slate-100 text-slate-800',
                                        'credito' => 'bg-indigo-100 text-indigo-800',
                                    ];
                                    $tipoColor = $tipoPagoColors[$reserva->tipo_pago ?? \App\Models\Reserva::TIPO_PAGO_CONTADO] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <tr>
                                    <td data-order="{{ $reserva->fecha->format('Y-m-d') }}">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center mr-3">
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">{{ $reserva->fecha->format('d/m/Y') }}</div>
                                                <div class="text-xs text-gray-500">{{ $reserva->fecha->locale('es')->isoFormat('dddd') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-order="{{ \Carbon\Carbon::parse($reserva->hora)->format('H:i') }}">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-blue-100 text-blue-800">
                                            {{ \Carbon\Carbon::parse($reserva->hora)->format('h:i A') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-sm font-semibold text-gray-900">{{ $reserva->cliente->negocio }}</div>
                                        <div class="text-xs text-gray-500">{{ $reserva->cliente->contacto }}</div>
                                    </td>
                                    <td>
                                        <div class="text-sm font-semibold text-gray-900">{{ $reserva->vehiculo->nombre }}</div>
                                    </td>
                                    <td data-order="{{ $reserva->cantidad }}">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-800">
                                            {{ $reserva->cantidad }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $tipoColor }}">
                                            {{ $tipoPago }}
                                        </span>
                                    </td>
                                    <td data-order="{{ $total }}">
                                        <div class="text-sm font-bold text-gray-900">
                                            ${{ number_format($total, 2) }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            ({{ $reserva->cantidad }} x ${{ number_format($precioUnitario, 2) }})
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $estatusColors = [
                                                'programado' => 'bg-yellow-100 text-yellow-800',
                                                'entregado' => 'bg-green-100 text-green-800',
                                                'no entregado' => 'bg-red-100 text-red-800',
                                            ];
                                            $color = $estatusColors[$reserva->estatus ?? 'programado'] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $color }}">
                                            {{ \App\Models\Reserva::ESTATUS[$reserva->estatus ?? 'programado'] ?? 'Programado' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('reservas.edit', $reserva) }}" class="inline-flex items-center px-3 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 font-semibold transition-colors duration-150">
                                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Editar
                                            </a>
                                            @if($reserva->estatus === \App\Models\Reserva::ESTATUS_PROGRAMADO)
                                                <a href="{{ route('reservas.ticket', $reserva) }}" target="_blank" class="inline-flex items-center px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 font-semibold transition-colors duration-150">
                                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                                    </svg>
                                                    Ticket
                                                </a>
                                            @endif
                                            <form action="{{ route('reservas.destroy', $reserva) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 font-semibold transition-colors duration-150" onclick="return confirm('¿Está seguro de eliminar esta reserva?')">
                                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-8">
                                        <div class="flex flex-col items-center">
                                            <svg class="h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="text-lg font-semibold text-gray-500">No hay reservas registradas</p>
                                            <p class="text-sm text-gray-400 mt-1">Comienza creando tu primera reserva</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <style>
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            margin-top: 1rem;
        }
        .dataTables_wrapper .dataTables_filter input {
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            margin-left: 0.5rem;
        }
        .dataTables_wrapper .dataTables_filter input:focus {
            outline: none;
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        }
        .dataTables_wrapper .dataTables_length select {
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.5rem;
            margin: 0 0.5rem;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5rem 1rem;
            margin: 0 0.25rem;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #8b5cf6;
            color: white !important;
            border-color: #8b5cf6;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #8b5cf6;
            color: white !important;
            border-color: #8b5cf6;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#reservasTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
                },
                responsive: true,
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
                order: [[0, 'asc'], [1, 'asc']], // Ordenar por fecha y luego por hora
                columnDefs: [
                    { orderable: false, targets: 8 }, // Deshabilitar ordenamiento en columna de acciones
                    { type: 'date', targets: 0 } // Tipo fecha para la columna de fecha
                ]
            });
        });
    </script>
    @endpush
</x-app-layout>
