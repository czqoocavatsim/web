@extends('layouts.email')

@section('to-line', 'Hi '. $application->user->fullName('F') . ',')

@section('message-content')
<p>Congratulations! Your application for Gander Oceanic has been accepted. The Events & Training Director will contact you via email to start your training.</p>
<hr>
You can view your application <a href="{{route('training.applications.show', $application->reference_id)}}">here.</a>
@endsection

@section('from-line')
Sent by Gander Oceanic OCA
@endsection

@section('footer-to-line', $application->user->fullName('FLC').' ('.$application->user->email.')')

@section('footer-reason-line')
there is an update on your application to become a Gander Oceanic Controller.
@endsection
