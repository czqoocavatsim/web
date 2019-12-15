@extends('layouts.master')
@section('title', 'CZQO Events - ')
@section('content')
    <div class="container py-4">
        <h1 class="blue-text font-weight-bold">Events</h1>
        <ul class="list-unstyled">
            @if (count($events) == 0)
            <li>No events.</li>
            @endif
            @foreach($events as $e)
            <div class="card my-2">
                <div class="d-flex flex-row justify-content-between">
                    <div class="p-3">
                        <a href="{{route('events.view', $e->slug)}}">
                            <h3>{{$e->name}}</h3>
                        </a>
                        <h5>{{$e->start_timestamp_pretty()}} to {{$e->end_timestamp_pretty()}}</h5>
                        @if ($e->departure_icao && $e->arrival_icao)
                        <h3>{{$e->departure_icao_data()->name}} // {{$e->departure_icao_data()->ICAO}}&nbsp;&nbsp;<i class="fas fa-plane"></i>&nbsp;&nbsp;{{$e->arrival_icao_data()->name}} // {{$e->arrival_icao_data()->ICAO}}</h3>
                        @endif
                        @if (!$e->event_in_past())
                        <h4>Starts {{$e->starts_in_pretty()}}</h4>
                        @endif
                    </div>
                    @if ($e->image_url)
                    <a href="{{route('events.view', $e->slug)}}" style="width: 35%; height: 150px;">
                        <div style="width: 100%; height: 150px; background-image:url({{$e->image_url}}); background-position: center;" class="waves-effect">
                        </div>
                    </a>
                    @else
                    <a href="{{route('events.view', $e->slug)}}" style="width: 35%; height 150px;">
                        <div style="width: 100%; height: 150px;" class="grey waves-effect">
                        </div>
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </ul>
    </div>
@endsection
