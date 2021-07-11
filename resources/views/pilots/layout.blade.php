@extends('layouts.primary')
@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-3">
            <ul class="list-unstyled w-100">
                <li class="w-100 my-3" style="border:none;">
                    <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                        <span style="font-size: 1em;" class="text-muted">GENERATORS</span>
                    </div>
                </li>
                <a class="myczqo-tab {{ request()->routeIs('pilots.oceanic-clearance') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('pilots.oceanic-clearance')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-receipt fa-fw"></i>
                            <span style="font-size: 1.1em;">Oceanic Clearance</span>
                        </div>
                    </li>
                </a>
                <a class="myczqo-tab {{ request()->routeIs('pilots.position-report') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('pilots.position-report')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-map-marker-alt fa-fw"></i>
                            <span style="font-size: 1.1em;">Position Reports</span>
                        </div>
                    </li>
                </a>
                <li class="w-100 my-3" style="border:none;">
                    <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                        <span style="font-size: 1em;" class="text-muted">RESOURCES</span>
                    </div>
                </li>
                <a class="myczqo-tab no-click" data-myczqo-tab="none" href="https://knowledgebase.ganderoceanic.ca">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-book fa-fw"></i>
                            <span style="font-size: 1.1em;">CZQO Knowledge Base</span>
                        </div>
                    </li>
                </a>
                <li class="w-100 my-3" style="border:none;">
                    <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                        <span style="font-size: 1em;" class="text-muted">NAT TRACKS</span>
                    </div>
                </li>
                <a class="myczqo-tab {{ request()->routeIs('pilots.tracks') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('pilots.tracks')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-map fa-fw"></i>
                            <span style="font-size: 1.1em;">Current Tracks</span>
                        </div>
                    </li>
                </a>
                <a class="myczqo-tab {{ request()->routeIs('pilots.event-tracks') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('pilots.event-tracks')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-star fa-fw"></i>
                            <span style="font-size: 1.1em;">Event Tracks</span>
                        </div>
                    </li>
                </a>
                <a class="myczqo-tab {{ request()->routeIs('pilots.concorde-tracks') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('pilots.concorde-tracks')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-fighter-jet fa-fw"></i>
                            <span style="font-size: 1.1em;">Concorde Tracks</span>
                        </div>
                    </li>
                </a>
                <li class="w-100 my-3" style="border:none;">
                    <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                        <span style="font-size: 1em;" class="text-muted">OTHER</span>
                    </div>
                </li>
                <a class="myczqo-tab {{ request()->routeIs('map') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('map')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-map fa-fw"></i>
                            <span style="font-size: 1.1em;">Oceanic Map</span>
                        </div>
                    </li>
                </a>
                <a class="myczqo-tab no-click" data-myczqo-tab="none" href="https://tracks.ganderoceanic.ca">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-code fa-fw"></i>
                            <span style="font-size: 1.1em;">NAT Track API</span>
                        </div>
                    </li>
                </a>
            </ul>
        </div>
        <div class="col-md-9">
            @yield('pilot-content')
        </div>
    </div>
@endsection
