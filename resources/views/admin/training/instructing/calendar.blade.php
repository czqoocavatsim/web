@extends('admin.training.layouts.main')
@section('title', 'Board - Instructing - ')
@section('training-content')
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.5/index.global.min.js'></script>
@endpush

<div class="container py-4">
    <div id="calendar"></div>
</div>



<script>

    document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('calendar');
      var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar:{
            start:"dayGridMonth,timeGridWeek,timeGridDay",
            center:'title',
            right:'prev,next'
        },
        eventSources: [
            {
            url: "{{route('trainingcalendar.training.trainingsessions')}}",
            },
            {
            url: "{{route('trainingcalendar.training.otssessions')}}",
            }

        ]
      });
      calendar.render();
    });

  </script>

@endsection


