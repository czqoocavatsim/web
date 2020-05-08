@extends('layouts.master')

@section('content')
    <div class="container py-4">
        <a href="{{route('dashboard.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Dashboard</a>
        <h1 class="blue-text font-weight-bold mt-2">Settings</h1>
        <hr>
        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="card h-100 p-4 bg-czqo-blue-light black-text shadow-none">
                    <h3>Site information</h3>
                    <p>Version, copyright, etc<br>&nbsp;</p>
                    <a class="black-text font-weight-bold" href="{{route('settings.siteinformation')}}">Go <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card h-100 p-4 bg-czqo-blue-light black-text shadow-none">
                    <h3>Emails</h3>
                    <p>Set emails where site events are sent to&nbsp;</p>
                    <a class="black-text font-weight-bold" href="{{route('network.monitoredpositions.index')}}">Go <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card h-100 p-4 bg-czqo-blue-light black-text shadow-none">
                    <h3>Rotation images</h3>
                    <p>Images used throughout the site<br>&nbsp;</p>
                    <a class="black-text font-weight-bold" href="{{route('network.monitoredpositions.index')}}">Go <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card h-100 p-4 bg-czqo-blue-light black-text shadow-none">
                    <h3>Audit Log</h3>
                    <p>System/user events<br>&nbsp;</p>
                    <a class="black-text font-weight-bold" href="{{route('network.monitoredpositions.index')}}">Go <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
@stop
