@extends('layouts.master')
@section('title', 'Events - ')
@section('description', 'Check out events over the Northern Atlantic supported by CZQO')
@section('content')
    <div class="container pt-4 pb-4">
        <div class="d-flex flex-row justify-content-between align-items-center mb-1">
            <h1 class="blue-text font-weight-bold">Events</h1>
            <a href="#" class="btn bg-czqo-blue-light float-right" data-toggle="modal" data-target="#requestModal">Request ATC Coverage</a>
        </div>
        <hr>
        <div class="row">
            @foreach($events as $event)
            <div class="col-md-6 mb-3">
                <div class="view" style="height: 330px !important; @if($event->image_url) background-image:url({{$event->image_url}}); background-size: cover; @else background: var(--czqo-blue); @endif">
                    <div class="mask rgba-blue-grey-strong flex-left p-4 justify-content-end d-flex   flex-column h-100">
                        <h2 class="font-weight-bold white-text">
                            <a href="{{route('events.view', $event->slug)}}" class="white-text">
                                {{$event->name}}
                            </a>
                        </h2>{{--
                        @if ($event->departure_icao && $event->arrival_icao)
                        <h4 class="white-text">{{$event->departure_icao_data()->name}} ({{$event->departure_icao_data()->ICAO}})&nbsp;&nbsp;<i class="fas fa-plane"></i>&nbsp;&nbsp;{{$event->arrival_icao_data()->name}} ({{$event->arrival_icao_data()->ICAO}})</h4>
                        @endif --}}
                        <h5 class="white-text">{{$event->start_timestamp_pretty()}} to {{$event->end_timestamp_pretty()}}</h5>
                    </div>
                </div>
            </div>
            @endforeach
            @if(count($events) < 1)
            <div class="col-md-6">
                No events scheduled. Check back soon!
            </div>
            @endif
        </div>
        <div class="d-none d-sm-block">
        <h5 class="mt-4"><a data-toggle="collapse" data-target="#pastEvents">View Past Events <i class="fas fa-caret-down"></i></a></h5>
        <div class="collapse" id="pastEvents">
            <ul class="list-unstyled">
                @if (count($pastEvents) == 0)
                <li>No past events.</li>
                @endif
                @foreach($pastEvents as $event)
                    <div class="view" style="height: 150px !important; background-image:url({{$event->image_url}}); background-size: cover; background-position: centre;">
                        <div class="mask rgba-blue-grey-strong flex-left p-4 justify-content-end d-flex   flex-column h-100">
                            <h2 class="font-weight-bold white-text">
                                <a href="{{route('events.view', $event->slug)}}" class="white-text">
                                    {{$event->name}}
                                </a>
                            </h2>{{--
                            @if ($event->departure_icao && $event->arrival_icao)
                            <h4 class="white-text">{{$event->departure_icao_data()->name}} ({{$event->departure_icao_data()->ICAO}})&nbsp;&nbsp;<i class="fas fa-plane"></i>&nbsp;&nbsp;{{$event->arrival_icao_data()->name}} ({{$event->arrival_icao_data()->ICAO}})</h4>
                            @endif --}}
                            <h5 class="white-text">{{$event->start_timestamp_pretty()}} to {{$event->end_timestamp_pretty()}}</h5>
                        </div>
                    </div>
                @endforeach
                </div>
            </ul>
        </div>
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
                        To request ATC for your event, contact the Events and Marketing Director via a <a href="TODO: TicketURL">ticket</a> or via <a href="{{route('staff')}}">email.</a> If the position is vacant, instead contact the OCA Chief.</p>
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
