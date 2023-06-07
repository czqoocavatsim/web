@extends('layouts.primary', ['solidNavBar' => false])
@section('title', 'Training Calendar - ')
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.5/index.global.min.js'></script>
@endpush




@section('content')
    <div class="card card-image blue rounded-0">
        <div class="text-white text-left pb-2 pt-5 px-4">
            <div class="container">
                <div class="py-5">
                    <h1 class="font-weight-bold" style="font-size: 3em;">Training Sessions Calendar</h1>
                </div>
            </div>
        </div>
    </div>
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
                url: "{{route('trainingcalendar.trainingsessions')}}",
                },
                {
                url: "{{route('trainingcalendar.otssessions')}}",
                }

            ]
          });
          calendar.render();
        });
  
      </script>
@endsection



