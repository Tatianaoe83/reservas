<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Reserva') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('reservas.update', $reserva) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="cliente_id" class="block text-sm font-medium text-gray-700">Cliente *</label>
                            <select name="cliente_id" id="cliente_id" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Seleccione un cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" {{ old('cliente_id', $reserva->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->negocio }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cliente_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="vehiculo_id" class="block text-sm font-medium text-gray-700">Vehículo *</label>
                            <select name="vehiculo_id" id="vehiculo_id" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Seleccione un vehículo</option>
                                @foreach($vehiculos as $vehiculo)
                                    <option value="{{ $vehiculo->id }}" {{ old('vehiculo_id', $reserva->vehiculo_id) == $vehiculo->id ? 'selected' : '' }}>
                                        {{ $vehiculo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('vehiculo_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha *</label>
                            <input type="date" name="fecha" id="fecha" value="{{ old('fecha', $reserva->fecha->format('Y-m-d')) }}" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('fecha')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="hora" class="block text-sm font-medium text-gray-700">Hora * (7:00 AM - 7:00 PM)</label>
                            <select name="hora" id="hora" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Seleccione una hora</option>
                                @foreach($horarios as $horario)
                                    <option value="{{ $horario }}" {{ old('hora', $reserva->hora) == $horario ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::parse($horario)->format('h:i A') }}
                                    </option>
                                @endforeach
                            </select>
                            <p id="horario-info" class="mt-1 text-xs text-gray-500 hidden"></p>
                            @error('hora')
                                <p class="mt-1 text-sm text-red-600 font-medium flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="cantidad" class="block text-sm font-medium text-gray-700">Cantidad a Llevar *</label>
                            <input type="number" name="cantidad" id="cantidad" value="{{ old('cantidad', $reserva->cantidad) }}" required min="1"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('cantidad')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="observaciones" class="block text-sm font-medium text-gray-700">Observaciones</label>
                            <textarea name="observaciones" id="observaciones" rows="3"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('observaciones', $reserva->observaciones) }}</textarea>
                            @error('observaciones')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('reservas.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const vehiculoSelect = document.getElementById('vehiculo_id');
            const fechaInput = document.getElementById('fecha');
            const horaSelect = document.getElementById('hora');
            const horarioInfo = document.getElementById('horario-info');
            const reservaId = {{ $reserva->id }};
            const horaActual = '{{ $reserva->hora }}';
            
            // Guardar todos los horarios iniciales
            const todosHorarios = Array.from(horaSelect.options).map(option => ({
                value: option.value,
                text: option.textContent
            })).filter(opt => opt.value !== '');

            function actualizarHorariosDisponibles() {
                const vehiculoId = vehiculoSelect.value;
                const fecha = fechaInput.value;

                // Si no hay vehículo o fecha seleccionada, limpiar horarios pero mantener la hora actual
                if (!vehiculoId || !fecha) {
                    if (!vehiculoId && !fecha) {
                        horaSelect.innerHTML = '<option value="">Seleccione vehículo y fecha primero</option>';
                    } else if (!vehiculoId) {
                        horaSelect.innerHTML = '<option value="">Seleccione un vehículo primero</option>';
                    } else {
                        horaSelect.innerHTML = '<option value="">Seleccione una fecha primero</option>';
                    }
                    // Mantener la hora actual si existe
                    if (horaActual) {
                        const option = document.createElement('option');
                        option.value = horaActual;
                        option.textContent = horaActual;
                        option.selected = true;
                        horaSelect.appendChild(option);
                    }
                    horarioInfo.classList.add('hidden');
                    return;
                }

                // Mostrar carga
                horaSelect.disabled = true;
                horaSelect.innerHTML = '<option value="">Cargando horarios...</option>';
                horarioInfo.textContent = 'Cargando disponibilidad...';
                horarioInfo.classList.remove('hidden');

                // Hacer petición AJAX (excluyendo la reserva actual)
                fetch(`{{ route('reservas.horarios-disponibles') }}?vehiculo_id=${vehiculoId}&fecha=${fecha}&reserva_id=${reservaId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    // Validar que data existe y tiene las propiedades esperadas
                    if (!data || typeof data.horarios === 'undefined') {
                        throw new Error('Formato de respuesta inválido');
                    }
                    // Limpiar el select
                    horaSelect.innerHTML = '<option value="">Seleccione una hora</option>';

                    // Agregar la hora actual siempre disponible (aunque esté ocupada, porque es esta reserva)
                    const horaActualFormateada = horaActual.substring(0, 5); // Formato H:i
                    if (data.horarios.indexOf(horaActualFormateada) === -1) {
                        data.horarios.push(horaActualFormateada);
                        data.horarios.sort();
                    }

                    if (data.horarios.length === 0) {
                        horaSelect.innerHTML = '<option value="">No hay horarios disponibles</option>';
                        horarioInfo.textContent = '⚠️ No hay horarios disponibles para este vehículo en la fecha seleccionada.';
                        horarioInfo.classList.remove('hidden');
                        horarioInfo.classList.add('text-red-600');
                        horarioInfo.classList.remove('text-gray-500');
                    } else {
                        // Agregar horarios disponibles
                        data.horarios.forEach(horario => {
                            const option = document.createElement('option');
                            option.value = horario;
                            
                            // Formatear la hora
                            const [h, m] = horario.split(':');
                            const horaFormato = new Date(2000, 0, 1, parseInt(h), parseInt(m));
                            const horaFormateada = horaFormato.toLocaleTimeString('es-ES', { 
                                hour: '2-digit', 
                                minute: '2-digit',
                                hour12: true 
                            });
                            
                            option.textContent = horaFormateada;
                            
                            // Mantener selección actual o anterior
                            const horaComparar = horario.substring(0, 5);
                            if (horaComparar === horaActualFormateada || option.value === '{{ old("hora") }}') {
                                option.selected = true;
                            }
                            
                            horaSelect.appendChild(option);
                        });

                        // Mostrar información de horarios ocupados
                        if (data.horarios_ocupados.length > 0) {
                            const totalHorarios = data.horarios.length + data.horarios_ocupados.length;
                            horarioInfo.textContent = `ℹ️ ${data.horarios.length} de ${totalHorarios} horarios disponibles. ${data.horarios_ocupados.length} horario(s) ocupado(s) oculto(s).`;
                            horarioInfo.classList.remove('hidden');
                            horarioInfo.classList.add('text-blue-600');
                            horarioInfo.classList.remove('text-red-600', 'text-gray-500', 'text-green-600');
                        } else {
                            horarioInfo.textContent = '✓ Todos los horarios están disponibles.';
                            horarioInfo.classList.remove('hidden');
                            horarioInfo.classList.add('text-green-600');
                            horarioInfo.classList.remove('text-red-600', 'text-blue-600', 'text-gray-500');
                        }
                    }

                    horaSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error:', error);
                    horaSelect.innerHTML = '<option value="">Error al cargar horarios</option>';
                    
                    // Mostrar todos los horarios como fallback, incluyendo la hora actual
                    todosHorarios.forEach(horario => {
                        const option = document.createElement('option');
                        option.value = horario.value;
                        option.textContent = horario.text;
                        if (option.value === horaActual || option.value === '{{ old("hora") }}') {
                            option.selected = true;
                        }
                        horaSelect.appendChild(option);
                    });
                    
                    horarioInfo.textContent = '⚠️ No se pudieron cargar los horarios disponibles. Por favor, verifique su conexión o recargue la página.';
                    horarioInfo.classList.remove('hidden');
                    horarioInfo.classList.add('text-orange-600');
                    horarioInfo.classList.remove('text-gray-500', 'text-red-600', 'text-green-600', 'text-blue-600');
                    horaSelect.disabled = false;
                });
            }

            // Event listeners
            vehiculoSelect.addEventListener('change', function() {
                // Si cambia el vehículo y hay fecha, actualizar horarios
                if (fechaInput.value) {
                    actualizarHorariosDisponibles();
                } else {
                    // Si no hay fecha, limpiar horarios pero mantener la hora actual
                    horaSelect.innerHTML = '<option value="">Primero seleccione una fecha</option>';
                    if (horaActual) {
                        const option = document.createElement('option');
                        option.value = horaActual;
                        option.textContent = horaActual;
                        option.selected = true;
                        horaSelect.appendChild(option);
                    }
                    horarioInfo.classList.add('hidden');
                }
            });
            
            fechaInput.addEventListener('change', function() {
                // Si cambia la fecha y hay vehículo, actualizar horarios
                if (vehiculoSelect.value) {
                    actualizarHorariosDisponibles();
                } else {
                    // Si no hay vehículo, limpiar horarios pero mantener la hora actual
                    horaSelect.innerHTML = '<option value="">Primero seleccione un vehículo</option>';
                    if (horaActual) {
                        const option = document.createElement('option');
                        option.value = horaActual;
                        option.textContent = horaActual;
                        option.selected = true;
                        horaSelect.appendChild(option);
                    }
                    horarioInfo.classList.add('hidden');
                }
            });

            // Inicializar: si ya hay valores seleccionados, actualizar horarios
            if (vehiculoSelect.value && fechaInput.value) {
                actualizarHorariosDisponibles();
            }
        });
    </script>
    @endpush
</x-app-layout>

