function createInstructingSessionsCal() {
    var calendarEl = document.getElementById('instructing-sessions-calendar')

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        headerToolbar: {
            start: 'title', // will normally be on the left. if RTL, will be on the right
            center: '',
            end: 'today prev,next timeGridWeek,dayGridMonth,list' // will normally be on the right. if RTL, will be on the left
          },
        firstDay: 1,
        nowIndicator: true,
        timeZone: 'UTC'
    });

    calendar.render();

    return calendar;
}
