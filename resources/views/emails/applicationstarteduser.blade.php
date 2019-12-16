@extends('layouts.email')

@section('to-line', 'Hi '. $application->user->fullName('FLC') . ',')


@section('message-content')
    <strong style="font-family: 'Open Sans', 'Segoe UI', 'Roboto', 'Verdana', 'Arial', sans-serif;">Howdy,</strong>
    <p style="font-family: 'Open Sans', 'Segoe UI', 'Roboto', 'Verdana', 'Arial', sans-serif;">
        Your application for Gander certification has been submitted and is now processing. We will read it within 24 hours!<br/>
        You will be notified of any updates regarding your application, until then, sit tight!
    </p>
@stop

@section('footer-to-line', $application->user->fullName('FLC').' ('.$application->user->email.')')

@section('footer-reason-line')
as they hold an account an account on the CZQO website and submitted an application for ocenaic endorsement.
@endsection
