<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle de Producto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl">
                <div class="px-8 py-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-sm text-gray-500 uppercase tracking-wide">Producto</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $producto->nombre }}</h3>
                    </div>
                </div>

                <div class="px-8 py-6 space-y-8">
                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500">Nombre</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $producto->nombre }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500">Cantidad</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-50 text-blue-700">
                                    {{ number_format($producto->cantidad) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500">Creado</p>
                            <p class="mt-1 text-sm font-medium text-gray-700">{{ $producto->created_at?->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500">Actualizado</p>
                            <p class="mt-1 text-sm font-medium text-gray-700">{{ $producto->updated_at?->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <div class="px-8 py-6 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <a href="{{ route('productos.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 font-semibold transition-colors duration-150">
                        Volver al listado
                    </a>
                    <div class="flex gap-3">
                        <a href="{{ route('productos.edit', $producto) }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-500 text-white hover:bg-blue-600 font-semibold transition-colors duration-150">
                            Editar producto
                        </a>
                        <form action="{{ route('productos.destroy', $producto) }}" method="POST" onsubmit="return confirm('¿Deseas eliminar este producto?')">
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

