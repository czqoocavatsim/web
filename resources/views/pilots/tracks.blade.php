@extends('layouts.master')
@section('content')
<script src="{{asset('js/leaflet.latlng.js')}}"></script>
<div class="container py-4">
    <div class="row">
        <div class="col-md-3">
            @include('layouts.toolSidebar')
        </div>
        <div class="col-md-9">
            <h1 class="font-weight-bold blue-text mb-3">Current NAT Tracks</h1>
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
            <a href="https://flightplandatabase.com"><img src="https://static.flightplandatabase.com/images/data-banner/light.min.png" alt="Data from the Flight Plan Database"></a>
            <p>Special thank you to Christian Kovanen 1379372 for providing the map theme, boundaries, and fixes.</p>
        </div>
        <script>
            createNatTrackMap();
        </script>
    </div>
</div>
@endsection
