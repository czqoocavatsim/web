@extends('layouts.email')

@section('title')
    <b>Application {{$application->application_id}} Denied</b>
@stop

@section('to')

    <strong>Hi there,</strong>
@stop

@section('content')
    <p>Your application for Gander Oceanic has been denied by {{$application->processed_by->fname}} {{$application->processed_by->lname}} {{$application->processed_by->id}} at {{$application->processed_at}} (Zulu).</p>
    <b>Staff Comments:</b>
    <p>
        @if (!$application->staff_comment)
            None
        @else
        {!! html_entity_decode($application->staff_comment) !!}
        @endif
    </p>
    <p>Please ensure you meet the requirements for next time. If you believe there is an error, please contact the FIR Chief.</p>
    <hr>
    <br/>
    You can view your application <a href="{{route('application.view', $application->application_id)}}">here.</a>
@stop

@section('end')
    <b>Gander Oceanic Core</b>
@stop