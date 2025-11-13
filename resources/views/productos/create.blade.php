<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nuevo Producto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl p-8 space-y-6">
                <form action="{{ route('productos.store') }}" method="POST" class="space-y-8">
                    @csrf

                    @include('productos._form')

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('productos.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 font-semibold transition-colors duration-150">
                            Cancelar
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 font-semibold transition-colors duration-150">
                            Guardar producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

