@extends('layouts.master')
@section('content')
<div class="container py-4">
    <h2>{{$event->name}}</h2>
    <div class="row">
        <div class="col-md-3">
            <h4 class="mt-2">Start Time</h4>
            <p>{{$event->start_timestamp_pretty()}}</p>
            <h4>End Time</h4>
            <p>{{$event->end_timestamp_pretty()}}</p>
            <h4>Departure Airport</h4>
            @if (!$event->departure_icao)
            No departure airport listed.
            @else
            <ul class="list-unstyled">
                <li>{{$event->departure_icao_data()->name}}</li>
                <li>{{$event->departure_icao_data()->ICAO}} ({{$event->departure_icao_data()->IATA}})</li>
                <li>{{$event->departure_icao_data()->regionName}}</li>
            </ul>
            @endif
            <h4>Arrival Airport</h4>
            @if (!$event->departure_icao)
            No arrival airport listed.
            @else
            <ul class="list-unstyled">
                <li>{{$event->arrival_icao_data()->name}}</li>
                <li>{{$event->arrival_icao_data()->ICAO}} ({{$event->departure_icao_data()->IATA}})</li>
                <li>{{$event->arrival_icao_data()->regionName}}</li>
            </ul>
            @endif
        </div>
        <div class="col-md-9">
                <h4>Description</h4>
                {{$event->html()}}
                <br>
                <h4>Updates</h4>
                @if (count($updates) == 0)
                No updates yet.
                @else
                @foreach($updates as $u)
                <div class="card p-3">
                    <a href="{{Request::url()}}#{{$u->slug}}" name={{$u->slug}}>
                    <h4>{{$u->title}}</h4>
                    </a>
                    <div class="d-flex flex-row align-items-center">
                        <i class="far fa-clock"></i>&nbsp;&nbsp;Created {{$u->created_pretty()}}</span>&nbsp;&nbsp;•&nbsp;&nbsp;<i class="far fa-user-circle"></i>&nbsp;&nbsp;{{$u->author_pretty()}}&nbsp;&nbsp;•&nbsp;&nbsp;<a href="#" class="red-text">Delete</a>
                    </div>
                    <hr>
                    {{$u->html()}}
                </div>
                @endforeach
                @endif
                <h4 class="mt-3">Controller Applications</h4>
                @if (count($applications) == 0)
                No applications yet.
                @else
                @foreach($applications as $a)
                <div class="card p-3">
                    <h5>{{$a->user->fullName('FLC')}} ({{$a->user->rating_GRP}}, {{$a->user->division_name}})</h5>
                    <p>{{$a->start_availability_timestamp}} to {{$a->end_availability_timestamp}}</p>
                    <h6>Comments</h6>
                    <p>{{$a->comments}}</p>
                    <h6>Email</h6>
                    <p>{{$a->user->email}}</p>
                </div>
                @endforeach
                @endif
            </div>
    </div>
</div>
@endsection
