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

function trainingSessionRemarksInit() {
    var simplemde = new EasyMDE({ maxHeight: '200px', autofocus: true, autoRefresh: true, element: document.getElementById("contentMD")});
    simplemde.codemirror.setOption('readOnly', true);
    simplemde.codemirror.on("changes", function(){

    });

    $("#enableRemarkEditB").click(function (e) {
        simplemde.codemirror.setOption('readOnly', false)
        $("#enableRemarkEditB").text("Edit mode on.").addClass("text-muted").removeClass("green-text")
        e.preventDefault()
    })
}
