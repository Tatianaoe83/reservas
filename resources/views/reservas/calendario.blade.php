<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Calendario de Reservas') }}
            </h2>
            <a href="{{ route('reservas.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Volver a Reservas
            </a>
        </div>
    </x-slot>

    @php
        $eventos = $reservas->map(function ($reserva) {
            return [
                'id' => $reserva->id,
                'title' => $reserva->cliente->negocio . ' - ' . $reserva->vehiculo->nombre . ' (' . $reserva->cantidad . ')',
                'date' => $reserva->fecha->format('Y-m-d'),
                'hora' => \Carbon\Carbon::parse($reserva->hora)->format('h:i A'),
                'url' => url('/reservas/' . $reserva->id . '/edit'),
            ];
        })->values()->toArray();
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div id="calendar" class="w-full"></div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const reservas = @json($eventos);

            let currentMonth = {{ now()->month - 1 }};
            let currentYear = {{ now()->year }};

            function renderCalendar() {
                const firstDay = new Date(currentYear, currentMonth, 1);
                const lastDay = new Date(currentYear, currentMonth + 1, 0);
                const daysInMonth = lastDay.getDate();
                const startingDayOfWeek = firstDay.getDay();

                let calendarHTML = '<div class="mb-4 flex justify-between items-center">';
                calendarHTML += '<button onclick="previousMonth()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Anterior</button>';
                calendarHTML += '<h3 class="text-xl font-bold">' + getMonthName(currentMonth) + ' ' + currentYear + '</h3>';
                calendarHTML += '<button onclick="nextMonth()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Siguiente</button>';
                calendarHTML += '</div>';

                calendarHTML += '<table class="w-full border-collapse">';
                calendarHTML += '<thead><tr>';
                const days = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
                days.forEach(day => {
                    calendarHTML += '<th class="border p-2 bg-gray-100">' + day + '</th>';
                });
                calendarHTML += '</tr></thead><tbody>';

                let day = 1;
                for (let i = 0; i < 6; i++) {
                    calendarHTML += '<tr>';
                    for (let j = 0; j < 7; j++) {
                        if (i === 0 && j < startingDayOfWeek) {
                            calendarHTML += '<td class="border p-2"></td>';
                        } else if (day > daysInMonth) {
                            calendarHTML += '<td class="border p-2"></td>';
                        } else {
                            const dateStr = currentYear + '-' + String(currentMonth + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0');
                            const dayReservas = reservas.filter(r => r.date === dateStr);
                            
                            calendarHTML += '<td class="border p-2 h-32 align-top">';
                            calendarHTML += '<div class="font-bold mb-1">' + day + '</div>';
                            if (dayReservas.length > 0) {
                                dayReservas.forEach(reserva => {
                                    calendarHTML += '<div class="text-xs bg-blue-500 text-white p-1 mb-1 rounded cursor-pointer hover:bg-blue-700" onclick="window.location.href=\'' + reserva.url + '\'">';
                                    calendarHTML += '<div class="font-semibold">' + reserva.hora + '</div>';
                                    calendarHTML += '<div class="text-xs">' + reserva.title + '</div>';
                                    calendarHTML += '</div>';
                                });
                            }
                            calendarHTML += '</td>';
                            day++;
                        }
                    }
                    calendarHTML += '</tr>';
                    if (day > daysInMonth) break;
                }
                calendarHTML += '</tbody></table>';

                document.getElementById('calendar').innerHTML = calendarHTML;
            }

            window.previousMonth = function() {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                renderCalendar();
            };

            window.nextMonth = function() {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
                renderCalendar();
            };

            function getMonthName(month) {
                const months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                return months[month];
            }

            renderCalendar();
        });
    </script>
    @endpush
</x-app-layout>
