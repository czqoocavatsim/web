@extends('layouts.email')

@section('to-line', 'Hi,')

@section('message-content')
<p>{{$application->user->fullName('FLC')}}'s application for Gander Oceanic has been accepted.</p>
<ul>
    <li>Reference ID: {{$application->reference_id}}</li>
    <li>Name: {{$application->user->fullName('FLC')}}</li>
</ul>
<br/>
You can view their application <a href="{{route('training.admin.applications.view', $application->reference_id)}}">here.</a>
@endsection

@section('from-line')
@endsection

@section('footer-to-line', '')

@section('footer-reason-line')
you are the OCA Chief / Deputy Chief.
@endsection
