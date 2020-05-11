@extends('layouts.master')
@section('title', 'Events - ')
@section('description', 'Check out events over the Northern Atlantic supported by CZQO')
@section('content')
    <div class="container py-4">
        <div class="d-flex flex-row justify-content-between align-items-center mb-1">
            <h1 class="blue-text font-weight-bold">Events</h1>
            <a href="#" class="btn btn-link float-right mx-0 px-0" data-toggle="modal" data-target="#requestModal">Request ATC Coverage</a>
        </div>
        <hr>
        <ul class="list-unstyled">
            @if (count($events) == 0)
            <li>No events.</li>
            @endif
            @foreach($events as $e)
            <div class="card my-2" style="height:150px;">
                <div class="d-flex flex-row justify-content-between">
                    <div class="p-3">
                        <a href="{{route('events.view', $e->slug)}}">
                            <h3>{{$e->name}}</h3>
                        </a>
                        <h5>{{$e->start_timestamp_pretty()}} to {{$e->end_timestamp_pretty()}}</h5>
                        @if ($e->departure_icao && $e->arrival_icao)
                        <h3>{{$e->departure_icao_data()->name}} ({{$e->departure_icao_data()->ICAO}})&nbsp;&nbsp;<i class="fas fa-plane"></i>&nbsp;&nbsp;{{$e->arrival_icao_data()->name}} ({{$e->arrival_icao_data()->ICAO}})</h3>
                        @endif
                        @if (!$e->event_in_past())
                        <p>Starts {{$e->starts_in_pretty()}}</p>
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
        <br>
        <h4><a data-toggle="collapse" data-target="#pastEvents">Show Past Events <i class="fas fa-caret-down"></i></a></h4>
        <div class="collapse" id="pastEvents">
            <ul class="list-unstyled">
                @if (count($pastEvents) == 0)
                <li>No past events.</li>
                @endif
                @foreach($pastEvents as $e)
                <div class="card my-2" style="height:150px;">
                    <div class="d-flex flex-row justify-content-between">
                        <div class="p-3">
                            <a href="{{route('events.view', $e->slug)}}">
                                <h3>{{$e->name}}</h3>
                            </a>
                            <h5>{{$e->start_timestamp_pretty()}} to {{$e->end_timestamp_pretty()}}</h5>
                            @if ($e->departure_icao && $e->arrival_icao)
                            <h3>{{$e->departure_icao_data()->name}} ({{$e->departure_icao_data()->ICAO}})&nbsp;&nbsp;<i class="fas fa-plane"></i>&nbsp;&nbsp;{{$e->arrival_icao_data()->name}} ({{$e->arrival_icao_data()->ICAO}})</h3>
                            @endif
                            @if (!$e->event_in_past())
                            <p>Starts {{$e->starts_in_pretty()}}</p>
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
    </div>
    <!-- ATC coverage request modal-->
    <div class="modal fade" id="requestModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Request ATC Coverage</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Gander Oceanic is happy to provide ATC coverage for your event crossing the North Atlantic.<br/>
                        To request ATC for your event, contact the Events and Marketing Director via a <a href="{{route('tickets.index')}}">ticket</a> or via <a href="{{route('staff')}}">email.</a> If the position is vacant, instead contact the FIR Chief.</p>
                    <br/>
                    <p>Section 2.5 of the General Policy applies.</p>
                    <blockquote style="font-size: 12px !important;">
                        <p  style="font-size: 12px !important;">2.5    Events<br/><br/>
2.5.1    Gander Oceanic warmly welcomes events (including those organised by Virtual Airlines, streamers, etc) that pass through the Gander and Shanwick airspace, and we are very happy to provide our excellent, professional service for both sectors.<br/><br/>
2.5.2    Gander Oceanic requires a notice of at least thirty (30) days from the event coordinator if Oceanic control is needed. This ensures that you can have the best oceanic experience throughout the duration of your event, as it takes time to compile a roster.<br/><br/>
2.5.3    If thirty (30) days is not provided, then Gander Oceanic cannot guarantee coverage for your event. A roster will not be compiled for any event made aware to us less than thirty days before the event date, nor will the event be published on our event page. Your cooperation is appreciated in this regard.<br/><br/>
2.5.4    Requests in accordance with 2.5.2 and 2.5.3 shall be made in writing via email to the Events and Marketing Director, copying in the FIR Chief. If the Events and Marketing Director position is vacant, than correspondence shall be directed straight to the FIR Chief. Email addresses can be found on the website, on the staff page.</p>
                    </blockquote>
                </div>
            </div>
        </div>
    </div>
@endsection
