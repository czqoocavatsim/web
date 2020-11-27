@extends('admin.training.layouts.main')
@section('training-content')
<h1 class="blue-text pb-2">Calendar</h1>
<p class="lead">Upcoming training and OTS sessions</p>
<div id="instructing-sessions-calendar">
</div>
<div class="mt-2">
    <a href="#" class="blue-text" style="font-size: 1.2em;"><i class="fas fa-plus"></i>&nbsp;&nbsp;Create a session</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.3.2/main.min.js" integrity="sha256-mMw9aRRFx9TK/L0dn25GKxH/WH7rtFTp+P9Uma+2+zc=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.3.2/main.min.css" integrity="sha256-uq9PNlMzB+1h01Ij9cx7zeE2OR2pLAfRw3uUUOOPKdA=" crossorigin="anonymous">
<script>
    //Initialise
    let calendar = createInstructingSessionsCal()

    //Get events
    let trainingSessions = @json($trainingSessions);
    console.log(trainingSessions);
    let otsSessions = @json($otsSessions)

    //Go through training sessions
    trainingSessions.forEach(session => {
        //Create a title
        var title = null
        if (session.position_id) {
            title = `${session.student.user.display_fname} ${session.student.user.lname} ${session.student.user.id} (${session.position.identifier})`
        } else {
            title = `${session.student.user.display_fname} ${session.student.user.lname} ${session.student.user.id}`
        }
        calendar.addEvent({
            title: title,
            start: session.scheduled_time,
            end: new Date(session.scheduled_time).setHours(new Date(session.scheduled_time).getHours() + 2),
            backgroundColor: '#90caf9',
            borderColor: '#90caf9',
            textColor: '#000'
        })
    })


    //Go through OTS sessions
    otsSessions.forEach(session => {
        //Create a title
        var title = null
        if (session.position_id) {
            title = `OTS: ${session.student.user.display_fname} ${session.student.user.lname} ${session.student.user.id} (${session.position.identifier})`
        } else {
            title = `OTS: ${session.student.user.display_fname} ${session.student.user.lname} ${session.student.user.id}`
        }
        calendar.addEvent({
            title: title,
            start: session.scheduled_time,
            end: new Date(session.scheduled_time).setHours(new Date(session.scheduled_time).getHours() + 2),
            backgroundColor: '#f44336',
            borderColor: '#f44336'
        })
    })
</script>
@endsection
