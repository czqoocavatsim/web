@extends('layouts.email')

@section('title')
    <b>Application {{$application->application_id}} Withdrawn</b>
@stop

@section('to')

    <strong>Hi there,</strong>
@stop

@section('content')
    <p>A controller has withdrawn their application for Gander Oceanic.</p>
    <b>Details</b>
    <ul>
        <li>Application ID: {{$application->application_id}}</li>
        <li>Name: {{$application->user->fullName('FLC')}}</li>
        <li>Rating/Division: {{$application->user->rating}}/{{$application->user->division}}</li>
    </ul>
    <hr>
    <br/>
    You can view their application <a href="{{route('training.viewapplication', $application->application_id)}}">here.</a>
@stop

@section('end')
    <b>Gander Oceanic Core</b>
@stop