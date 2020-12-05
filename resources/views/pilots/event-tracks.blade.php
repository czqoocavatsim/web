@extends('layouts.master')
@section('content')
<script src="{{asset('js/leaflet.latlng.js')}}"></script>
<div class="container py-4">
    <div class="row">
        <div class="col-md-3">
            @include('layouts.toolSidebar')
        </div>
        <div class="col-md-9">
            <h1 class="font-weight-bold blue-text mb-3">Event NAT Tracks</h1>
            <p>The tracks utlised for our latest event.</p>
            <div style="height: 400px;" id="map"></div>
            <p>Red = Westbound, Blue = Eastbound</p>
            <table id="natTrackTable" class="table table-responsive table-striped">
                <thead>
                <tr>
                    <th scope="col">Letter</th>
                    <th scope="col">Fixes</th>
                    <th scope="col">Direction</th>
                    <th scope="col">Levels</th>
                    <th scope="col">Validity</th>
                </tr>
                </thead>
                <tbody id="tableBody">
                </tbody>
            </table>
            <p class="mt-3 text-muted">Special thank you to Christian Kovanen 1379372 for the boundaries and fixes.</p>
        </div>
        <script>
            createEventTrackMap();
        </script>
    </div>
</div>
<style>
    .leaflet-tooltip {
    position: absolute;
    padding: 6px;
    background: none !important;
    border: none !important;
    border-radius: none !important;
    color: #222;
    white-space: nowrap;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    pointer-events: none;
    box-shadow: none !important;
    }
    .leaflet-tooltip-top:before, .leaflet-tooltip-bottom:before, .leaflet-tooltip-left:before, .leaflet-tooltip-right:before {
    position: absolute;
    pointer-events: none;
    border: none !important;
    background: transparent;
    content: "";
    }
</style>
@endsection
