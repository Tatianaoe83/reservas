import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import listPlugin from '@fullcalendar/list';
import esLocale from '@fullcalendar/core/locales/es';

window.initCalendar = function(events, initialView = 'dayGridMonth') {
    const calendarEl = document.getElementById('calendar');
    
    if (!calendarEl) return;

    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin, listPlugin],
        initialView: initialView,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        locale: esLocale,
        firstDay: 0, // Domingo
        events: events.map(event => {
            // Formatear la fecha y hora correctamente
            const startDateTime = `${event.date}T${event.hora}`;
            
            return {
                id: event.id.toString(),
                title: `${event.title} - ${event.vehiculo} (${event.cantidad})`,
                start: startDateTime,
                url: event.url,
                backgroundColor: event.color,
                borderColor: event.color,
                textColor: '#ffffff',
                extendedProps: {
                    vehiculo: event.vehiculo,
                    cantidad: event.cantidad,
                    cliente: event.title,
                    hora: event.horaFormato
                }
            };
        }),
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
        contentHeight: 'auto',
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
        }
    });

    calendar.render();
    
    return calendar;
};

