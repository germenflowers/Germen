document.addEventListener('DOMContentLoaded', function () {
  let initialTimeZone = 'Europe/Moscow';
  let calendarEl = document.getElementById('germen-orders-calendar');
  let loadingEl = document.getElementById('germen-orders-calendar-loading');
  let errorEl = document.getElementById('germen-orders-calendar-warning');
  let isAdminSection = calendarEl.dataset.adminsection === 'Y';
  let url = '/local/modules/germen.orderscalendar/admin/ajax/handler.php';

  if (isAdminSection) {
    url += '?admin-section=Y';
  }

  let calendar = new FullCalendar.Calendar(calendarEl, {
    locale: 'ru',
    timeZone: initialTimeZone,
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,dayGridWeek,timeGridDay'
    },
    navLinks: true,
    events: {
      url: url,
      failure: function () {
        errorEl.style.display = 'block'
      }
    },
    eventDisplay: 'block',
    eventTimeFormat: { hour: '2-digit', minute: '2-digit' },
    eventClassNames: '',
    eventClick: function (event) {
      if (event.event.url) {
        event.jsEvent.preventDefault();
        window.open(event.event.url, "_blank");
      }
    },
    eventDidMount: function (event) {
      // console.log(event.event.extendedProps);

      let placement = 'left',
        element = event.el,
        content = '<div style="display: grid;">';

      if (event.view.type === 'timeGridDay') {
        placement = 'auto';
        element = event.el.parentElement;
      }

      content += '<div>Статус: ' + event.event.extendedProps.status + '</div>';
      content += '<div>Оплачен: ' + (event.event.extendedProps.paid ? 'Да' : 'Нет') + '</div>';
      content += '<div>Сумма: ' + event.event.extendedProps.sumFormat + '</div>';
      content += '</div>';

      tippy(element, {
        allowHTML: true,
        interactive: true,
        placement: placement,
        content: content,
      });
    },
    loading: function (bool) {
      if (bool) {
        loadingEl.style.display = 'flex';
      } else {
        loadingEl.style.display = 'none';
      }
    },
  });

  calendar.render();
});