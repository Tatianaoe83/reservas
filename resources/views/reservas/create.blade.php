<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('reservas.index') }}" class="mr-2 sm:mr-4 text-gray-600 hover:text-gray-900">
                <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-gray-900">
                    {{ __('Nueva Reserva') }}
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">Crea una nueva reserva</p>
            </div>
        </div>
    </x-slot>

    @once
        @push('styles')
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
            <style>
                .select2-container--default .select2-selection--single {
                    display: flex;
                    align-items: center;
                    height: 2.75rem;
                    border: 1px solid #d1d5db;
                    border-radius: 0.375rem;
                    padding: 0 0.75rem;
                    transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
                }

                .select2-container--default.select2-container--focus .select2-selection--single {
                    border-color: #6366f1;
                    box-shadow: 0 0 0 1px rgba(99, 102, 241, 0.2);
                }

                .select2-container--default .select2-selection--single .select2-selection__rendered {
                    line-height: 1.75rem;
                    padding-left: 0;
                    color: #111827;
                }

                .select2-container--default .select2-selection--single .select2-selection__arrow {
                    height: 2.75rem;
                    right: 0.75rem;
                }

                .select2-dropdown {
                    border-color: #d1d5db;
                }

                .select2-results__option--highlighted.select2-results__option--selectable {
                    background-color: #4f46e5;
                }
            </style>
        @endpush
    @endonce

    @once
        @push('scripts')
            <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        @endpush
    @endonce

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-xl sm:rounded-lg p-4 sm:p-6">
                <form action="{{ route('reservas.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="cliente_id" class="block text-sm font-medium text-gray-700">Cliente *</label>
                            <select name="cliente_id" id="cliente_id" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Seleccione un cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
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
                                    <option value="{{ $vehiculo->id }}" {{ old('vehiculo_id') == $vehiculo->id ? 'selected' : '' }}>
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
                            <input type="date" name="fecha" id="fecha" required min="{{ date('Y-m-d') }}"
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
                                    <option value="{{ $horario }}" {{ old('hora') == $horario ? 'selected' : '' }}>
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
                            <input type="number" name="cantidad" id="cantidad" value="{{ old('cantidad') }}" required min="1"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('cantidad')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="estatus" class="block text-sm font-medium text-gray-700">Estatus *</label>
                            <select name="estatus" id="estatus" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach(\App\Models\Reserva::ESTATUS as $key => $label)
                                    <option value="{{ $key }}" {{ old('estatus', \App\Models\Reserva::ESTATUS_PROGRAMADO) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('estatus')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="observaciones" class="block text-sm font-medium text-gray-700">Observaciones</label>
                            <textarea name="observaciones" id="observaciones" rows="3"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('observaciones') }}</textarea>
                            @error('observaciones')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col-reverse sm:flex-row justify-end gap-3 sm:space-x-3">
                        <a href="{{ route('reservas.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 sm:py-2.5 px-4 sm:px-6 rounded-lg text-sm sm:text-base transition-colors">
                            Cancelar
                        </a>
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-bold py-2 sm:py-2.5 px-4 sm:px-6 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 text-sm sm:text-base">
                            <svg class="h-4 w-4 sm:h-5 sm:w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Guardar Reserva
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        (function() {
            const initSelects = () => {
                if (!window.jQuery || !$.fn.select2) {
                    return;
                }

                const vehiculoSelect = document.getElementById('vehiculo_id');
                const fechaInput = document.getElementById('fecha');
                const horaSelect = document.getElementById('hora');
                const horarioInfo = document.getElementById('horario-info');
                const $clienteSelect = $('#cliente_id');
                const $vehiculoSelect = $('#vehiculo_id');
                const $horaSelect = $('#hora');

                if ($clienteSelect.hasClass('select2-hidden-accessible')) {
                    return;
                }

                const commonSelect2Config = {
                    width: '100%',
                    allowClear: true,
                    language: {
                        noResults: () => 'Sin resultados',
                        searching: () => 'Buscando…'
                    }
                };

                $clienteSelect.select2({
                    ...commonSelect2Config,
                    placeholder: 'Seleccione un cliente'
                });

                $vehiculoSelect.select2({
                    ...commonSelect2Config,
                    placeholder: 'Seleccione un vehículo'
                });

                $horaSelect.select2({
                    ...commonSelect2Config,
                    placeholder: 'Seleccione una hora',
                    dropdownParent: $horaSelect.parent()
                });
                
                // Guardar todos los horarios iniciales
                const todosHorarios = Array.from(horaSelect.options).map(option => ({
                    value: option.value,
                    text: option.textContent
                })).filter(opt => opt.value !== '');

            const actualizarHorariosDisponibles = () => {
                const vehiculoId = vehiculoSelect.value;
                const fecha = fechaInput.value;

                // Si no hay vehículo o fecha seleccionada, limpiar horarios
                    if (!vehiculoId || !fecha) {
                        if (!vehiculoId && !fecha) {
                            $horaSelect.html('<option value="">Seleccione vehículo y fecha primero</option>');
                        } else if (!vehiculoId) {
                            $horaSelect.html('<option value="">Seleccione un vehículo primero</option>');
                        } else {
                            $horaSelect.html('<option value="">Seleccione una fecha primero</option>');
                        }
                        $horaSelect.trigger('change.select2');
                    horarioInfo.classList.add('hidden');
                    return;
                }

                // Mostrar carga
                $horaSelect.prop('disabled', true);
                    $horaSelect.html('<option value="">Cargando horarios...</option>').trigger('change.select2');
                horarioInfo.textContent = 'Cargando disponibilidad...';
                horarioInfo.classList.remove('hidden');

                console.log('Solicitando horarios disponibles', { vehiculoId, fecha });

                // Hacer petición AJAX
                fetch(`{{ route('reservas.horarios-disponibles') }}?vehiculo_id=${vehiculoId}&fecha=${fecha}`, {
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
                    console.log('Respuesta horarios', data);
                    
                    // Limpiar el select
                    $horaSelect.html('<option value="">Seleccione una hora</option>');

                    if (data.horarios.length === 0) {
                        if (!data.horarios_ocupados || data.horarios_ocupados.length === 0) {
                            todosHorarios.forEach(horario => {
                                const option = new Option(horario.text, horario.value, false, horario.value === '{{ old("hora") }}');
                                $horaSelect.append(option);
                            });
                            horarioInfo.textContent = '⚠️ No se recibieron horarios disponibles desde el servidor. Se muestran todos los horarios estándar.';
                            horarioInfo.classList.remove('hidden');
                            horarioInfo.classList.add('text-orange-600');
                            horarioInfo.classList.remove('text-gray-500', 'text-red-600', 'text-blue-600', 'text-green-600');
                        } else {
                            $horaSelect.html('<option value="">No hay horarios disponibles</option>').trigger('change.select2');
                            horarioInfo.textContent = '⚠️ No hay horarios disponibles para este vehículo en la fecha seleccionada.';
                            horarioInfo.classList.remove('hidden');
                            horarioInfo.classList.add('text-red-600');
                            horarioInfo.classList.remove('text-gray-500');
                        }
                    } else {
                        // Agregar SOLO horarios disponibles (los ocupados ya vienen filtrados del servidor)
                        data.horarios.forEach(horario => {
                            // Asegurarse de que el horario esté en formato H:i
                            const horarioFormato = horario.length >= 5 ? horario.substring(0, 5) : horario;
                            
                            // Verificar que no esté en la lista de ocupados (doble verificación)
                            if (data.horarios_ocupados && data.horarios_ocupados.includes(horarioFormato)) {
                                console.warn('Horario ocupado detectado pero enviado como disponible:', horarioFormato);
                                return; // Saltar este horario
                            }

                            let etiquetaHorario = horarioFormato;
                            const [h, m] = horarioFormato.split(':');
                            if (h && m) {
                                const horaFormato = new Date(2000, 0, 1, parseInt(h), parseInt(m));
                                etiquetaHorario = horaFormato.toLocaleTimeString('es-ES', {
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: true
                                });
                            }

                            const isSelected = horarioFormato === '{{ old("hora") }}' || horarioFormato === '{{ old("hora") }}'.substring(0, 5);
                            const option = new Option(etiquetaHorario, horarioFormato, false, isSelected);
                            $horaSelect.append(option);
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

                        $horaSelect.prop('disabled', false).trigger('change.select2');
                })
                .catch(error => {
                    console.error('Error:', error);
                    $horaSelect.html('<option value="">Error al cargar horarios</option>');
                    
                    // Mostrar todos los horarios como fallback
                    todosHorarios.forEach(horario => {
                        const option = new Option(horario.text, horario.value, false, horario.value === '{{ old("hora") }}');
                        $horaSelect.append(option);
                    });
                    $horaSelect.trigger('change');
                    
                    horarioInfo.textContent = '⚠️ No se pudieron cargar los horarios disponibles. Por favor, verifique su conexión o recargue la página.';
                    horarioInfo.classList.remove('hidden');
                    horarioInfo.classList.add('text-orange-600');
                    horarioInfo.classList.remove('text-gray-500', 'text-red-600', 'text-green-600', 'text-blue-600');
                    $horaSelect.prop('disabled', false);
                });
            };

            // Event listeners
            vehiculoSelect.addEventListener('change', () => {
                if (!fechaInput.value) {
                    $horaSelect.html('<option value="">Primero seleccione una fecha</option>').trigger('change.select2');
                    horarioInfo.classList.add('hidden');
                    return;
                }

                actualizarHorariosDisponibles();
            });

            fechaInput.addEventListener('change', () => {
                if (!vehiculoSelect.value) {
                    $horaSelect.html('<option value="">Primero seleccione un vehículo</option>').trigger('change.select2');
                    horarioInfo.classList.add('hidden');
                    return;
                }

                actualizarHorariosDisponibles();
            });

            // Inicializar: si ya hay valores seleccionados (después de un error de validación), actualizar horarios
            if (vehiculoSelect.value && fechaInput.value) {
                actualizarHorariosDisponibles();
            } else {
                // Si no hay ambos valores, limpiar el select de horarios inicialmente
                $horaSelect.html('<option value="">Seleccione vehículo y fecha primero</option>').trigger('change.select2');
            }
            };

            const state = document.readyState;
            if (state === 'complete' || state === 'interactive') {
                initSelects();
            } else {
                document.addEventListener('DOMContentLoaded', initSelects, { once: true });
            }

            window.addEventListener('load', initSelects, { once: true });
        })();
    </script>
    @endpush
</x-app-layout>

