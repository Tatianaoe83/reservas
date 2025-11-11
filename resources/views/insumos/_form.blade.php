@props(['insumo' => null, 'categorias' => []])

@php
    $oldCategoria = old('categoria', optional($insumo)->categoria);
    $fechaSeleccionada = old('fecha', optional($insumo?->fecha)->format('Y-m-d') ?? now()->format('Y-m-d'));
@endphp

<style>
    [x-cloak] {
        display: none !important;
    }
</style>

<div
    x-data="{
        categoria: @js($oldCategoria),
        esCombustible() {
            return this.categoria === 'Combustible';
        }
    }"
    class="space-y-6"
>
    <div class="grid gap-6 sm:grid-cols-3">
        <div>
            <label for="categoria" class="block text-sm font-medium text-gray-700">Categoría *</label>
            <select
                id="categoria"
                name="categoria"
                x-model="categoria"
                required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
                <option value="" disabled {{ $oldCategoria ? '' : 'selected' }}>Selecciona una categoría</option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria }}" @selected($oldCategoria === $categoria)>{{ $categoria }}</option>
                @endforeach
            </select>
            @error('categoria')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre *</label>
            <input
                type="text"
                name="nombre"
                id="nombre"
                value="{{ old('nombre', optional($insumo)->nombre) }}"
                required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
            @error('nombre')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha *</label>
            <input
                type="date"
                name="fecha"
                id="fecha"
                value="{{ $fechaSeleccionada }}"
                required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
            @error('fecha')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label for="cantidad" class="block text-sm font-medium text-gray-700">
                Cantidad <span x-show="!esCombustible()" class="text-red-500">*</span>
            </label>
            <input
                type="number"
                name="cantidad"
                id="cantidad"
                min="1"
                step="1"
                value="{{ old('cantidad', optional($insumo)->cantidad) }}"
                :required="!esCombustible()"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
            @error('cantidad')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="sueldo_semana" class="block text-sm font-medium text-gray-700">
                Sueldo semanal <span x-show="!esCombustible()" class="text-red-500">*</span>
            </label>
            <div class="relative mt-1 rounded-md shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <span class="text-gray-500 sm:text-sm">$</span>
                </div>
                <input
                    type="number"
                    name="sueldo_semana"
                    id="sueldo_semana"
                    min="0"
                    step="0.01"
                    value="{{ old('sueldo_semana', optional($insumo)->sueldo_semana) }}"
                    :required="!esCombustible()"
                    class="block w-full rounded-md border-gray-300 pl-7 pr-12 focus:border-indigo-500 focus:ring-indigo-500"
                >
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                    <span class="text-gray-500 sm:text-sm">MXN</span>
                </div>
            </div>
            @error('sueldo_semana')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div x-cloak x-show="esCombustible()" class="border border-amber-200 bg-amber-50 rounded-xl p-6 space-y-6">
        <div>
            <h3 class="text-base font-semibold text-amber-700">Detalles de combustible</h3>
            <p class="text-sm text-amber-600 mt-1">
                Registra las lecturas del tanque para calcular el consumo y el costo semanal de combustible.
            </p>
        </div>

        <div class="grid gap-6 sm:grid-cols-3">
            <div>
                <label for="lectura_inicial_combustible" class="block text-sm font-medium text-gray-700">Lectura inicial *</label>
                <input
                    type="number"
                    name="lectura_inicial_combustible"
                    id="lectura_inicial_combustible"
                    min="0"
                    step="0.01"
                    value="{{ old('lectura_inicial_combustible', optional($insumo)->lectura_inicial_combustible) }}"
                    :required="esCombustible()"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                @error('lectura_inicial_combustible')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="lectura_final_combustible" class="block text-sm font-medium text-gray-700">Lectura final *</label>
                <input
                    type="number"
                    name="lectura_final_combustible"
                    id="lectura_final_combustible"
                    min="0"
                    step="0.01"
                    value="{{ old('lectura_final_combustible', optional($insumo)->lectura_final_combustible) }}"
                    :required="esCombustible()"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                @error('lectura_final_combustible')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="precio_combustible" class="block text-sm font-medium text-gray-700">Precio gasolina *</label>
                <div class="relative mt-1 rounded-md shadow-sm">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="text-gray-500 sm:text-sm">$</span>
                    </div>
                    <input
                        type="number"
                        name="precio_combustible"
                        id="precio_combustible"
                        min="0"
                        step="0.01"
                        value="{{ old('precio_combustible', optional($insumo)->precio_combustible) }}"
                        :required="esCombustible()"
                        class="block w-full rounded-md border-gray-300 pl-7 pr-12 focus:border-indigo-500 focus:ring-indigo-500"
                    >
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                        <span class="text-gray-500 sm:text-sm">MXN</span>
                    </div>
                </div>
                @error('precio_combustible')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
</div>

