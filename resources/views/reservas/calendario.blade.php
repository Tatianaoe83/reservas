<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900">
                    {{ __('Calendario de Reservas') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Visualiza las reservas por vehículo y horario</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('reservas.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nueva Reserva
                </a>
                <a href="{{ route('reservas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition-colors duration-150">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver
                </a>
            </div>
        </div>
    </x-slot>

    @php
        // Paleta de colores para vehículos
        $palette = [
            '#6366F1', // Indigo
            '#10B981', // Emerald
            '#F59E0B', // Amber
            '#EC4899', // Pink
            '#0EA5E9', // Sky
            '#F97316', // Orange
            '#8B5CF6', // Violet
            '#22D3EE', // Cyan
            '#EF4444', // Red
            '#84CC16', // Lime
        ];

        $vehicleColors = [];
        $colorIndex = 0;

        // Asignar colores únicos a cada vehículo
        foreach ($reservas->pluck('vehiculo')->unique('id') as $vehiculo) {
            $vehicleColors[$vehiculo->id] = $palette[$colorIndex % count($palette)];
            $colorIndex++;
        }

        // Preparar eventos para FullCalendar
        $eventos = $reservas->map(function ($reserva) use ($vehicleColors) {
            try {
                $color = $vehicleColors[$reserva->vehiculo_id] ?? '#6366F1';
                
                // Formatear la hora correctamente
                // La hora puede venir como string (H:i o H:i:s) o como objeto Time
                $horaStr = $reserva->hora;
                
                // Convertir a string si es necesario
                if (is_object($horaStr) && method_exists($horaStr, 'format')) {
                    $horaStr = $horaStr->format('H:i:s');
                } elseif (!is_string($horaStr)) {
                    $horaStr = (string) $horaStr;
                }
                
                // Parsear la hora, intentando diferentes formatos
                try {
                    if (strlen($horaStr) == 5) {
                        // Formato H:i
                        $horaCarbon = \Carbon\Carbon::createFromFormat('H:i', $horaStr);
                    } else {
                        // Formato H:i:s
                        $horaCarbon = \Carbon\Carbon::createFromFormat('H:i:s', $horaStr);
                    }
                } catch (\Exception $e) {
                    // Si falla, intentar parsear directamente
                    $horaCarbon = \Carbon\Carbon::parse($horaStr);
                }
                
                // Crear fecha y hora combinada en formato ISO 8601
                $fechaInicio = $reserva->fecha->format('Y-m-d') . 'T' . $horaCarbon->format('H:i:s');
                
                // Agregar 30 minutos de duración para que se vea mejor en vistas de semana/día
                $fechaFin = $horaCarbon->copy()->addMinutes(30);
                $fechaFinCompleta = $reserva->fecha->format('Y-m-d') . 'T' . $fechaFin->format('H:i:s');
                
                // Título más corto para que se vea mejor en el calendario
                $titulo = $reserva->cliente->negocio . ' (' . $reserva->cantidad . ')';
                
                return [
                    'id' => $reserva->id,
                    'title' => $titulo,
                    'start' => $fechaInicio,
                    'end' => $fechaFinCompleta,
                    'url' => route('reservas.edit', $reserva->id),
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'textColor' => '#ffffff',
                    'display' => 'block',
                    'extendedProps' => [
                        'vehiculo' => $reserva->vehiculo->nombre,
                        'cantidad' => $reserva->cantidad,
                        'cliente' => $reserva->cliente->negocio,
                        'contacto' => $reserva->cliente->contacto,
                        'hora' => $horaCarbon->format('h:i A'),
                        'direccion' => $reserva->cliente->direccion,
                        'telefono' => $reserva->cliente->telefono,
                    ],
                ];
            } catch (\Exception $e) {
                // Si hay un error con alguna reserva, la omitimos
                \Log::error('Error procesando reserva ID ' . $reserva->id . ': ' . $e->getMessage());
                return null;
            }
        })->filter()->values()->toArray();
    @endphp

    <div class="py-8 bg-gradient-to-br from-gray-50 to-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Sidebar con información -->
                <div class="space-y-6">
                    <!-- Resumen -->
                    <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="h-5 w-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Resumen
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-xl">
                                <span class="text-sm font-semibold text-gray-700">Total Reservas</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-blue-600 text-white">
                                    {{ $reservas->count() }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-emerald-50 rounded-xl">
                                <span class="text-sm font-semibold text-gray-700">Clientes</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-emerald-600 text-white">
                                    {{ $reservas->pluck('cliente_id')->unique()->count() }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-purple-50 rounded-xl">
                                <span class="text-sm font-semibold text-gray-700">Vehículos</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-purple-600 text-white">
                                    {{ $reservas->pluck('vehiculo_id')->unique()->count() }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Leyenda de Vehículos -->
                    <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="h-5 w-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                            Vehículos
                        </h3>
                        <div class="space-y-2">
                            @forelse ($vehicleColors as $vehiculoId => $color)
                                @php
                                    $vehiculo = $reservas->firstWhere('vehiculo_id', $vehiculoId)?->vehiculo;
                                    $count = $reservas->where('vehiculo_id', $vehiculoId)->count();
                                @endphp
                                @if ($vehiculo)
                                    <div class="flex items-center justify-between p-3 rounded-xl border-2 hover:border-gray-300 transition-colors" style="border-color: {{ $color }}20; background-color: {{ $color }}08;">
                                        <div class="flex items-center">
                                            <div class="h-4 w-4 rounded-full mr-3 shadow-sm" style="background-color: {{ $color }}"></div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-900">{{ $vehiculo->nombre }}</p>
                                                <p class="text-xs text-gray-500">{{ $count }} reserva{{ $count != 1 ? 's' : '' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <p class="text-sm text-gray-400 text-center py-4">No hay vehículos con reservas</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Información -->
                    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 shadow-xl rounded-2xl p-6 text-white">
                        <h3 class="text-lg font-bold mb-2 flex items-center">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Información
                        </h3>
                        <p class="text-sm text-blue-100 leading-relaxed mt-2">
                            Haz clic en cualquier reserva para editarla. Los colores identifican cada vehículo. Cambia la vista usando los botones en la parte superior.
                        </p>
                    </div>
                </div>

                <!-- Calendario Principal -->
                <div class="lg:col-span-3">
                    <div class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden">
                        <div id="calendar" style="min-height: 700px; padding: 20px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css' rel='stylesheet' />
    <style>
        .fc {
            font-family: inherit;
        }
        .fc-button {
            background-color: #2563eb !important;
            border-color: #2563eb !important;
            color: white !important;
            padding: 0.5rem 1rem !important;
            border-radius: 0.5rem !important;
            font-weight: 600 !important;
            transition: all 0.2s !important;
        }
        .fc-button:hover {
            background-color: #1d4ed8 !important;
            border-color: #1d4ed8 !important;
        }
        .fc-button-primary:not(:disabled).fc-button-active {
            background-color: #1d4ed8 !important;
            border-color: #1d4ed8 !important;
        }
        .fc-toolbar-title {
            font-size: 1.5rem !important;
            font-weight: 700 !important;
            color: #111827 !important;
        }
        .fc-col-header-cell {
            background-color: #f9fafb !important;
            padding: 0.75rem !important;
            font-weight: 700 !important;
            color: #374151 !important;
            border-color: #e5e7eb !important;
        }
        .fc-daygrid-day {
            border-color: #e5e7eb !important;
        }
        .fc-day-today {
            background-color: #dbeafe !important;
        }
        .fc-event {
            border: none !important;
            border-radius: 0.5rem !important;
            padding: 0.25rem 0.5rem !important;
            cursor: pointer !important;
            transition: transform 0.2s !important;
        }
        .fc-event:hover {
            transform: scale(1.02);
        }
        .fc-event-title {
            font-weight: 600 !important;
            color: white !important;
        }
        .fc-timeGridWeek-view .fc-event-time {
            font-weight: 700 !important;
        }
        .fc-list-event:hover td {
            background-color: #f9fafb !important;
        }
    </style>
    @endpush

    @push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/locales/es.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const eventos = @json($eventos);
            
            console.log('Eventos cargados:', eventos);
            console.log('Cantidad de eventos:', eventos.length);
            
            // Validar eventos antes de renderizar
            if (eventos.length === 0) {
                console.warn('No hay eventos para mostrar en el calendario');
            } else {
                eventos.forEach(function(evento, index) {
                    console.log('Evento ' + index + ':', evento.title, 'Fecha/Hora:', evento.start);
                });
            }
            
            const calendarEl = document.getElementById('calendar');
            
            if (!calendarEl) {
                console.error('No se encontró el elemento calendar');
                return;
            }

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                locale: 'es',
                firstDay: 0, // Domingo
                events: eventos,
                eventDisplay: 'block',
                eventContent: function(arg) {
                    // Personalizar el contenido del evento para mostrar más información
                    const props = arg.event.extendedProps;
                    const title = arg.event.title;
                    
                    // Para vista de mes, mostrar título simple
                    if (arg.view.type === 'dayGridMonth') {
                        return {
                            html: '<div style="padding: 2px 4px; font-size: 0.75em; font-weight: 600;">' +
                                  title +
                                  '</div>'
                        };
                    }
                    
                    // Para vista de semana/día, mostrar más detalles
                    return {
                        html: '<div style="padding: 3px 6px;">' +
                              '<div style="font-weight: 700; font-size: 0.85em; margin-bottom: 2px;">' + title + '</div>' +
                              '<div style="font-size: 0.75em; opacity: 0.9;">' + (props.vehiculo || '') + '</div>' +
                              '</div>'
                    };
                },
                eventDidMount: function(info) {
                    // Tooltip con información adicional
                    const props = info.event.extendedProps;
                    const title = 'Cliente: ' + props.cliente + '\n' +
                                 'Vehículo: ' + props.vehiculo + '\n' +
                                 'Cantidad: ' + props.cantidad + '\n' +
                                 'Hora: ' + props.hora + '\n' +
                                 'Contacto: ' + props.contacto;
                    
                    info.el.setAttribute('title', title);
                    console.log('Evento renderizado:', info.event.title, 'Fecha:', info.event.start);
                },
                eventClick: function(info) {
                    if (info.event.url) {
                        window.location.href = info.event.url;
                        info.jsEvent.preventDefault();
                    }
                },
                eventMouseEnter: function(info) {
                    info.el.style.cursor = 'pointer';
                    info.el.style.transform = 'scale(1.02)';
                    info.el.style.transition = 'transform 0.2s';
                },
                eventMouseLeave: function(info) {
                    info.el.style.transform = 'scale(1)';
                },
                businessHours: {
                    daysOfWeek: [0, 1, 2, 3, 4, 5, 6],
                    startTime: '07:00',
                    endTime: '19:00',
                },
                slotMinTime: '07:00:00',
                slotMaxTime: '19:00:00',
                allDaySlot: false,
                height: 'auto',
                editable: false,
                selectable: false,
                dayMaxEvents: 5, // Mostrar hasta 5 eventos visibles por día
                moreLinkClick: 'popover', // Mostrar popover cuando hay más eventos
                // Para vista de mes, mostrar eventos aunque tengan hora específica
                displayEventTime: true,
                displayEventEnd: false,
                // Hacer que los eventos se muestren en la vista de mes
                eventStartEditable: false,
                eventDurationEditable: false,
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: 'short',
                    hour12: true
                },
                slotLabelFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: 'short',
                    hour12: true
                },
                dayMaxEvents: true,
                moreLinkClick: 'popover'
            });

            calendar.render();
            
            console.log('Calendario renderizado exitosamente');
            
            // Verificar eventos después de renderizar
            setTimeout(function() {
                const renderedEvents = calendar.getEvents();
                console.log('Eventos en el calendario:', renderedEvents.length);
                renderedEvents.forEach(function(event) {
                    console.log('Evento:', event.title, 'Fecha:', event.start);
                });
            }, 1000);
        });
    </script>
    @endpush
</x-app-layout>
