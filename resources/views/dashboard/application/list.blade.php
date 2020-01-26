@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    <div class="container" style="margin-top: 20px;">
        <div class="container" style="margin-top: 20px;">
            <a href="{{route('dashboard.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Dashboard</a>
        <h1 class="blue-text font-weight-bold mt-2">Your Applications</h1>
        <hr>
        @if (count($applications) > 0)
            <div class="list-group">
                @foreach ($applications as $application)
                    <a href="{{route('application.view', $application->application_id)}}" class="list-group-item list-group-item-action flex-column align-items-start">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">#{{ $application->application_id }}</h5>
                        </div>
                        @if ($application->status == 2)
                            <p class="mb-1 text-success">
                                <i class="fa fa-check"></i>&nbsp;
                                Accepted
                            </p>
                        @elseif ($application->status == 1)
                            <p class="mb-1 text-danger">
                                <i class="fa fa-times"></i>&nbsp;
                                Denied
                            </p>
                        @elseif  ($application->status == 0)
                            <p class="mb-1 text-info">
                                <i class="fa fa-clock"></i>&nbsp;
                                Pending
                            </p>
                        @elseif ($application->status == 3)
                            <p class="mb-1 text-dark">
                                <i class="fa fa-times"></i>&nbsp;
                                Withdrawn
                            </p>
                        @endif
                        <small>Submitted at {{ $application->submitted_at }} Zulu</small>
                    </a>
                @endforeach
            </div>
        @else
            <div class="alert bg-czqo-blue-light">
                You have no applications.
                @if (Auth::user()->permissions == 0)
                    <a href="{{route('application.start')}}" class="alert-link black-text">
                        Start one.
                    </a>
                @endif
            </div>
        @endif
        <br/>
        @if (Auth::user()->permissions == 0)
            <a href="{{route('application.start')}}" role="button" class="btn btn-primary">Start an Application</a>
        @endif
    </div>
@stop
