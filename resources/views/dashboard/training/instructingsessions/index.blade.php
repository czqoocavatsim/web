@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    @include('includes.trainingMenu')
    <div class="container" style="margin-top: 20px;">
        <h1>Instructing Sessions</h1>
        <hr>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        Upcoming Sessions
                    </div>
                    <div class="card-body">
                        @if ($sessions !== null)
                        <div class="list-group">
                            @foreach ($upcomingSessions as $session)
                            <a href="{{route('training.instructingsessions.viewsession', $session->id)}}" class="list-group-item d-flex justify-content-between align-items-center @if (Auth::user()->instructingProfile === $session->instructor) bg-primary @endif">
                                {{$session->student->user->fullName('FLC')}}<br/>
                                {{$session->type}} | {{$session->date}} {{$session->start_time}} to {{$session->end_time}}<br/>
                                Instructor: {{$session->instructor->user->fullName('FLC')}}
                            </a>
                            @endforeach
                        </div>
                        @else
                        No upcoming sessions.
                        @endif
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        Actions
                    </div>
                    <div class="card-body">
                        <a href="#" role="button" class="btn btn-primary">Create Session</a>
                    </div>
                </div>
            </div>
        </div>
        <br/>
        <h5>Training Calendar</h5>
        {{--<div id='calendar'></div>
        <script>

                document.addEventListener('DOMContentLoaded', function() {
                  var calendarEl = document.getElementById('calendar');

                  var calendar = new FullCalendar.Calendar(calendarEl, {
                    plugins: [ 'dayGrid' ],
                      events: [
                          { // this object will be "parsed" into an Event Object
                              title: 'The Title', // a property!
                              start: '2018-09-01', // a property!
                              end: '2018-09-02' // a property! ** see important note below about 'end' **
                          }
                      ]
                  });

                  calendar.render();
                });

              </script>--}}
        {!!$calendar->calendar()!!}
        {!!$calendar->script()!!}
    </div>
@stop