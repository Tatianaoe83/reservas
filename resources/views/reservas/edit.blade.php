<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Reserva') }}
        </h2>
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

                        <div>
                            <label for="estatus" class="block text-sm font-medium text-gray-700">Estatus *</label>
                            <select name="estatus" id="estatus" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach(\App\Models\Reserva::ESTATUS as $key => $label)
                                    <option value="{{ $key }}" {{ old('estatus', $reserva->estatus ?? \App\Models\Reserva::ESTATUS_PROGRAMADO) == $key ? 'selected' : '' }}>
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
        (function() {
            const initSelects = () => {
                if (!window.jQuery || !$.fn.select2) {
                    return;
                }

            const vehiculoSelect = document.getElementById('vehiculo_id');
            const fechaInput = document.getElementById('fecha');
            const horaSelect = document.getElementById('hora');
            const horarioInfo = document.getElementById('horario-info');
            const reservaId = {{ $reserva->id }};
                const horaActual = "{{ $reserva->hora }}";
                const horaActualNormalizada = horaActual ? horaActual.substring(0, 5) : '';
                const oldHoraRaw = "{{ old('hora') }}";
                const oldHoraNormalizada = oldHoraRaw ? oldHoraRaw.substring(0, 5) : '';
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

            const todosHorarios = Array.from(horaSelect.options).map(option => ({
                value: option.value,
                text: option.textContent
            })).filter(opt => opt.value !== '');

                const normalizarHora = valor => (valor ? valor.substring(0, 5) : '');
                const formatearEtiqueta = valor => {
                    const normalizado = normalizarHora(valor);
                    const [h, m] = normalizado.split(':');
                    if (h && m) {
                        const fechaReferencia = new Date(2000, 0, 1, parseInt(h, 10), parseInt(m, 10));
                        return fechaReferencia.toLocaleTimeString('es-ES', {
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: true
                        });
                    }
                    return normalizado;
                };

                const agregarOpcionHora = (valor, seleccionado = false) => {
                    const normalizado = normalizarHora(valor);
                    if (!normalizado) {
                        return;
                    }
                    const option = new Option(formatearEtiqueta(normalizado), normalizado, false, seleccionado);
                    $horaSelect.append(option);
                };

                const actualizarHorariosDisponibles = () => {
                const vehiculoId = vehiculoSelect.value;
                const fecha = fechaInput.value;

                if (!vehiculoId || !fecha) {
                    if (!vehiculoId && !fecha) {
                            $horaSelect.html('<option value="">Seleccione vehículo y fecha primero</option>');
                    } else if (!vehiculoId) {
                            $horaSelect.html('<option value="">Seleccione un vehículo primero</option>');
                    } else {
                            $horaSelect.html('<option value="">Seleccione una fecha primero</option>');
                        }
                        if (horaActualNormalizada) {
                            agregarOpcionHora(horaActualNormalizada, true);
                        }
                        $horaSelect.trigger('change.select2');
                    horarioInfo.classList.add('hidden');
                    return;
                }

                    $horaSelect.prop('disabled', true);
                    $horaSelect.html('<option value="">Cargando horarios...</option>').trigger('change.select2');
                horarioInfo.textContent = 'Cargando disponibilidad...';
                horarioInfo.classList.remove('hidden');

                    console.log('Solicitando horarios disponibles (edición)', { vehiculoId, fecha, reservaId });

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
                    if (!data || typeof data.horarios === 'undefined') {
                        throw new Error('Formato de respuesta inválido');
                    }

                        console.log('Respuesta horarios (edición)', data);

                    const horariosOcupados = Array.isArray(data.horarios_ocupados) ? data.horarios_ocupados : [];
                    let horariosDisponibles = Array.isArray(data.horarios) ? [...data.horarios] : [];

                    if (horaActualNormalizada && !horariosDisponibles.includes(horaActualNormalizada)) {
                        horariosDisponibles.push(horaActualNormalizada);
                    }

                    horariosDisponibles = horariosDisponibles
                        .map(normalizarHora)
                        .filter(Boolean)
                        .filter((valor, indice, self) => self.indexOf(valor) === indice)
                        .sort();

                    $horaSelect.html('<option value="">Seleccione una hora</option>');

                    if (horariosDisponibles.length === 0) {
                            if (horariosOcupados.length === 0) {
                                todosHorarios.forEach(horario => {
                                    agregarOpcionHora(horario.value, horario.value === oldHoraNormalizada || horario.value === horaActualNormalizada);
                                });
                                $horaSelect.trigger('change.select2');
                                horarioInfo.textContent = '⚠️ No se recibieron horarios disponibles desde el servidor. Se muestran todos los horarios estándar.';
                                horarioInfo.classList.remove('hidden');
                                horarioInfo.classList.add('text-orange-600');
                                horarioInfo.classList.remove('text-gray-500', 'text-red-600', 'text-blue-600', 'text-green-600');
                            } else {
                                $horaSelect.html('<option value="">No hay horarios disponibles</option>').trigger('change.select2');
                        horarioInfo.textContent = '⚠️ No hay horarios disponibles para este vehículo en la fecha seleccionada.';
                        horarioInfo.classList.remove('hidden');
                        horarioInfo.classList.add('text-red-600');
                                horarioInfo.classList.remove('text-gray-500', 'text-green-600', 'text-blue-600');
                            }
                    } else {
                        const valorPreferido = oldHoraNormalizada || horaActualNormalizada;
                        horariosDisponibles.forEach(horario => {
                            agregarOpcionHora(horario, horario === valorPreferido);
                        });

                        if (horariosOcupados.length > 0) {
                            const totalHorarios = horariosDisponibles.length + horariosOcupados.length;
                            horarioInfo.textContent = `ℹ️ ${horariosDisponibles.length} de ${totalHorarios} horarios disponibles. ${horariosOcupados.length} horario(s) ocupado(s) oculto(s).`;
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
                    
                    const valorPreferido = oldHoraNormalizada || horaActualNormalizada;
                    todosHorarios.forEach(horario => {
                        agregarOpcionHora(horario.value, horario.value === valorPreferido);
                    });
                    $horaSelect.trigger('change.select2');
                    
                    horarioInfo.textContent = '⚠️ No se pudieron cargar los horarios disponibles. Por favor, verifique su conexión o recargue la página.';
                    horarioInfo.classList.remove('hidden');
                    horarioInfo.classList.add('text-orange-600');
                    horarioInfo.classList.remove('text-gray-500', 'text-red-600', 'text-green-600', 'text-blue-600');
                    $horaSelect.prop('disabled', false);
                });
                };

                vehiculoSelect.addEventListener('change', () => {
                    if (!fechaInput.value) {
                        $horaSelect.html('<option value="">Primero seleccione una fecha</option>');
                        if (horaActualNormalizada) {
                            agregarOpcionHora(horaActualNormalizada, true);
                        }
                        $horaSelect.trigger('change.select2');
                        horarioInfo.classList.add('hidden');
                        return;
                    }

                    actualizarHorariosDisponibles();
                });

                fechaInput.addEventListener('change', () => {
                    if (!vehiculoSelect.value) {
                        $horaSelect.html('<option value="">Primero seleccione un vehículo</option>');
                        if (horaActualNormalizada) {
                            agregarOpcionHora(horaActualNormalizada, true);
                        }
                        $horaSelect.trigger('change.select2');
                        horarioInfo.classList.add('hidden');
                        return;
                    }

                actualizarHorariosDisponibles();
                });

            if (vehiculoSelect.value && fechaInput.value) {
                actualizarHorariosDisponibles();
            } else {
                $horaSelect.html('<option value="">Seleccione vehículo y fecha primero</option>');
                if (horaActualNormalizada) {
                    agregarOpcionHora(horaActualNormalizada, true);
                }
                $horaSelect.trigger('change.select2');
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

