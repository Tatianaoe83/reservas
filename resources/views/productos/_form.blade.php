@props(['producto' => null])

<div class="space-y-6">
    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre *</label>
            <input
                type="text"
                name="nombre"
                id="nombre"
                value="{{ old('nombre', optional($producto)->nombre) }}"
                required
                placeholder="Ej: Marqueta"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
            >
            @error('nombre')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="cantidad" class="block text-sm font-medium text-gray-700">Cantidad *</label>
            <input
                type="number"
                name="cantidad"
                id="cantidad"
                min="1"
                step="1"
                value="{{ old('cantidad', optional($producto)->cantidad) }}"
                required
                placeholder="Ej: 80"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
            >
            @error('cantidad')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>

