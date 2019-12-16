@extends('layouts.email')

@section('to-line')

    <strong>Hi there,</strong>
@stop

@section('message-content')
    <p>{{$application->user->fullName('FLC')}}'s application for Gander Oceanic has been accepted by {{\App\Models\Users\User::find($application->processed_by)->fullName('FLC')}} at {{$application->processed_at}} (Zulu).</p>
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
