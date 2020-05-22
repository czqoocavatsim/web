@extends('layouts.master')
@section('content')
<div class="container py-4">
    <a href="{{route('dashboard.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Dashboard</a>
    <h1 class="blue-text font-weight-bold mt-2">Network</h1>
    <hr>
    <div class="card-deck">
        <div class="card p-4 green white-text">
            <h3>Monitored Positions</h3>
            <p>Edit monitored positions and view position uptime</p>
            <a class="text-white font-weight-bold" href="{{route('network.monitoredpositions.index')}}">Go <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="card p-4 orange white-text">
            <h3>Controller Activity</h3>
            <p>View controller activity statistics against policy requirements</p>
            <a class="white-text font-weight-bold" href="#">Go <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="card p-4 purple white-text">
            <h3>Overall Statistics</h3>
            <p>View total statistics for Gander positions</p>
            <a class="white-text font-weight-bold" href="#">Go <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</div>
@endsection
