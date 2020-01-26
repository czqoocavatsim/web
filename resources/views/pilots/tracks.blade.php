@extends('layouts.master')

@section('navbarprim')

    @parent

@stop
@section('title', 'NAT Tracks - ')
@section('description', 'View the current oceanic NAT tracks')
@section('content')
<div class="container" style="margin-top: 20px;">
    <div class="row">
        <div class="col-md-3">
            @include('layouts.toolSidebar')
        </div>
        <div class="col-md-9">
        <h1 class="font-weight-bold blue-text">NAT Tracks</h1>
        <hr>
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-rl-tab" data-toggle="tab" href="#nav-rl" role="tab" aria-controls="nav-rl" aria-selected="true">Real World Tracks</a>
                {{--<a class="nav-item nav-link" id="nav-ctp-tab" data-toggle="tab" href="#nav-ctp" role="tab" aria-controls="nav-ctp" aria-selected="false">Cross the Pond</a>--}}
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-rl" role="tabpanel" aria-labelledby="nav-rl-tab">
                <div id="map" style="height: 300px;">
                    Loading...
                </div>
                <b>Red = Westbound, Blue = Eastbound</b>
                <table class="table table-responsive table-striped">
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
            </div>
            <div class="tab-pane fade" id="nav-ctp" role="tabpanel" aria-labelledby="nav-ctp-tab">
                <div id="mapfit" style="height: 300px;">
                    Loading...
                </div>
                <b>Orange = Westbound, Blue = Eastbound</b>
                <table class="table table-responsive table-striped">
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
            </div>
        </div>
    </div>
</div>
</div>
<script src="http://cdn.mapfit.com/v2-4/assets/js/mapfit.js"></script>
    <script src="{{asset('js/natTracks.js')}}"></script>
@stop
