@extends('layouts.email')
@section('to-line', 'Hi '. $application->user->fullName('FLC') . ',')

@section('message-content')
    <p>Congratulations, your application for Gander Oceanic has been accepted by {{\App\Models\Users\User::find($application->processed_by)->fullName('FLC')}}  at {{$application->processed_at}} (Zulu)!</p>
    <b>Staff Comments:</b>
    <p>
        @if (!$application->staff_comment)
            None
        @else
            {!! html_entity_decode($application->staff_comment) !!}
        @endif
    </p>
    <p>The Chief Instructor will contact you as soon as possible to start the training process. If you have any questions, please do not hesitate to contact us.</p>
    <hr>
    <br/>
    You can view your application <a href="{{route('application.view', $application->application_id)}}">here.</a>
@stop


@section('footer-to-line', $application->user->fullName('FLC').' ('.$application->user->email.')')

@section('footer-reason-line')
as they hold an account an account on the CZQO website and submitted an application for ocenaic endorsement.
@endsection
