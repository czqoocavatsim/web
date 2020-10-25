var Calendar = require('tui-calendar'); /* CommonJS */
require("tui-calendar/dist/tui-calendar.css");
require("tui-date-picker/dist/tui-date-picker.css");
require("tui-time-picker/dist/tui-time-picker.css");

const monthNames = ["January", "February", "March", "April", "May", "June",
  "July", "August", "September", "October", "November", "December"
];


createCalendar = function () {
    var calendar = new Calendar('#instructing-sessions-calendar', {
        defaultView: 'month',
        taskView: false,
        isReadOnly: true,
        usageStatistics: false,
        timezones: [
            {
                timezoneOffset: 0,
                displayLabel: 'UTC',
                tooltip: 'Zulu'
            }
        ]
    });

    $("#instructing-sessions-calendar-range").text(monthNames[calendar.getDate().toDate().getMonth()] + " " + calendar.getDate().toDate().getFullYear())

    calendar.createSchedules([
        {
            id: '1',
            calendarId: '1',
            title: 'my schedule',
            category: 'time',
            dueDateClass: '',
            start: '2018-01-18T22:30:00+09:00',
            end: '2018-01-19T02:30:00+09:00'
        },
        {
            id: '2',
            calendarId: '1',
            title: 'second schedule',
            category: 'time',
            dueDateClass: '',
            start: '2018-01-18T17:30:00+09:00',
            end: '2018-01-19T17:31:00+09:00',
            isReadOnly: true    // schedule is read-only
        }
    ]);

    $("#instructing-sessions-calendar-prev-button").click(function() {
        calendar.prev();
        $("#instructing-sessions-calendar-range").text(monthNames[calendar.getDate().toDate().getMonth()] + " " + calendar.getDate().toDate().getFullYear())

    })


    $("#instructing-sessions-calendar-next-button").click(function() {
        calendar.next();
        $("#instructing-sessions-calendar-range").text(monthNames[calendar.getDate().toDate().getMonth()] + " " + calendar.getDate().toDate().getFullYear())
    })
}
