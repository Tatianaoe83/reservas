<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-gray-900">
                    {{ __('Clientes') }}
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">Gestiona tu catálogo de clientes</p>
            </div>
            <a href="{{ route('clientes.create') }}" class="inline-flex items-center justify-center px-4 py-2 sm:px-6 sm:py-3 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 text-sm sm:text-base">
                <svg class="h-4 w-4 sm:h-5 sm:w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="hidden sm:inline">Nuevo Cliente</span>
                <span class="sm:hidden">Nuevo</span>
            </a>
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
                    <table id="clientesTable" class="display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Negocio</th>
                                <th>Dirección</th>
                                <th>Teléfono</th>
                                <th>Contacto</th>
                                <th>Precio Venta</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clientes as $cliente)
                                <tr>
                                    <td>
                                        <div class="text-sm font-bold text-gray-900">{{ $cliente->negocio }}</div>
                                    </td>
                                    <td>
                                        <div class="text-sm text-gray-600">{{ $cliente->direccion }}</div>
                                    </td>
                                    <td>
                                        <div class="text-sm text-gray-600">{{ $cliente->telefono }}</div>
                                    </td>
                                    <td>
                                        <div class="text-sm text-gray-600">{{ $cliente->contacto }}</div>
                                    </td>
                                    <td>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-800">
                                            ${{ number_format($cliente->precio_venta, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('clientes.edit', $cliente) }}" class="inline-flex items-center px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 font-semibold transition-colors duration-150">
                                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Editar
                                            </a>
                                            <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 font-semibold transition-colors duration-150" onclick="return confirm('¿Está seguro de eliminar este cliente?')">
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
                                    <td colspan="6" class="text-center py-8">
                                        <div class="flex flex-col items-center">
                                            <svg class="h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            <p class="text-lg font-semibold text-gray-500">No hay clientes registrados</p>
                                            <p class="text-sm text-gray-400 mt-1">Comienza agregando tu primer cliente</p>
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
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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
            background: #3b82f6;
            color: white !important;
            border-color: #3b82f6;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #3b82f6;
            color: white !important;
            border-color: #3b82f6;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#clientesTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
                },
                responsive: true,
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
                order: [[0, 'asc']],
                columnDefs: [
                    { orderable: false, targets: 5 } // Deshabilitar ordenamiento en columna de acciones
                ]
            });
        });
    </script>
    @endpush
</x-app-layout>
