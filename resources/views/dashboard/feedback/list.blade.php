@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    <div class="container" style="margin-top: 20px;">
    <h2>View All Feedback</h2>
    <br/>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Pending</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Accepted</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Denied</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="list-group">
                    @if (count($) < 1)
                        <br/>
                        <p>No applications.</p>
                    @endif
                    @foreach ($applicationsPending as $application)
                        <a href="{{url('/dashboard/training/applications/' . $application->application_id)}}" class="list-group-item list-group-item-action flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">#{{ $application->application_id }} | {{App\User::find($application->user_id)->fname}} {{App\User::find($application->user_id)->lname}} {{App\User::find($application->user_id)->id}}</h5>
                                <small><samp>DB No. {{ $application->id }}</samp></small>
                            </div>
                            <p class="mb-1 text-info">
                                <i class="fa fa-clock"></i>&nbsp;
                                {{ $application->status }}
                            </p>
                            <small>Submitted at {{ $application->submission_time }} Zulu</small>
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="list-group">
                    @if (count($applicationsAccepted) < 1)
                        <br/>
                        <p>No applications.</p>
                    @endif
                    @foreach ($applicationsAccepted as $application)
                        <a href="{{url('/dashboard/training/applications/' . $application->application_id)}}" class="list-group-item list-group-item-action flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">#{{ $application->application_id }} | {{App\User::find($application->user_id)->fname}} {{App\User::find($application->user_id)->lname}} {{App\User::find($application->user_id)->id}}</h5>
                                <small><samp>DB No. {{ $application->id }}</samp></small>
                            </div>
                            <p class="mb-1 text-success">
                                <i class="fa fa-check"></i>&nbsp;
                                {{ $application->status }}
                            </p>
                            <small>Submitted at {{ $application->submission_time }} Zulu</small>
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <div class="list-group">
                    @if (count($applicationsDenied) < 1)
                        <br/>
                        <p>No applications.</p>
                    @endif
                    @foreach ($applicationsDenied as $application)
                        <a href="{{url('/dashboard/training/applications/' . $application->application_id)}}" class="list-group-item list-group-item-action flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">#{{ $application->application_id }} | {{App\User::find($application->user_id)->fname}} {{App\User::find($application->user_id)->lname}} {{App\User::find($application->user_id)->id}}</h5>
                                <small><samp>DB No. {{ $application->id }}</samp></small>
                            </div>
                            <p class="mb-1 text-danger">
                                <i class="fa fa-cross"></i>&nbsp;
                                {{ $application->status }}
                            </p>
                            <small>Submitted at {{ $application->submission_time }} Zulu</small>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@stop