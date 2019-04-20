@extends('layouts.email')

@section('title')
    <b>Application {{$application->application_id}} Accepted!</b>
@stop

@section('to')

    <strong>Hi there,</strong>
@stop

@section('content')
    <p>{{$application->user->fullName('FLC')}}'s application for Gander Oceanic has been accepted by {{\App\User::find($application->processed_by)->fullName('FLC')}} at {{$application->processed_at}} (Zulu).</p>
    <b>Staff Comments:</b>
    <p>
        @if (!$application->staff_comment)
            None
        @else
        {!! html_entity_decode($application->staff_comment) !!}
        @endif
    </p>
    <p>Their email is {{$application->user->email}}</p>
    <hr>
    <br/>
    You can view the application <a href="{{route('training.viewapplication', $application->application_id)}}">here.</a>
@stop

@section('end')
    <b>Gander Oceanic Core</b>
@stop