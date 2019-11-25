@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    <div class="container" style="margin-top: 20px;">
        <h2>Controller Bookings</h2>
        <div class="row">
            <div class="col-md-8">
                <input type="date" class="flatpickr">
                <h4>Upcoming</h4>
                @foreach($upcomingBookings as $b)
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">{{$b->position->callsign}}</h5>
                                <p>
                                    @php
                                        $start = \Carbon\Carbon::parse($b->start_time);
                                        echo $start->diffForHumans();
                                    @endphp
                                </p>
                            </div>
                            @php
                                $start_time = \Carbon\Carbon::parse($b->start_time);
                                $end_time = \Carbon\Carbon::parse($b->end_time);
                            @endphp
                            <p class="mb-1">
                                Booked by {{$b->user->fullName('FLC')}}<br/>
                                From {{$start_time->toDayDateTimeString()}} to {{$end_time->toDayDateTimeString()}}
                            </p>
                        </a>
                    </div>
                @endforeach
                <small>All times listed are in Zulu time.</small>
            </div>
            <div class="col">
                @if (Auth::check() && Auth::user()->certified())
                <div class="card">
                    <div class="card-header">Bookings</div>
                    <ul class="list-group list-group-flush">
                        <a href="#" class="list-group-item">Create a booking</a>
                        <a href="#" class="list-group-item">View your bookings</a>
                    </ul>
                </div>
                @endif
                <div class="card mt-3">
                    <div class="card-header">Information</div>
                    <ul class="list-group list-group-flush">
                        <a href="#" class="list-group-item">What is a controller booking?</a>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@stop