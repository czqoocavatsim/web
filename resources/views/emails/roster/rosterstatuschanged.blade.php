@extends('layouts.email')

@section('to-line', 'Hi '. $rosterMember->user->fullName('F') . ',')

@section('message-content')
<p>Your roster status with Gander Oceanic has been changed.</p>
<p>
    <ul>
        <li>Certification: {{$rosterMember->certificationPretty()}}</li>
        <li>Active: {{$rosterMember->activePretty()}}</li>
    </ul>
</p>
<p>
    If you believe this is a mistake or have any questions, please contact us via a <a href="TODO: TicketURL">support ticket</a> or email the <a href="{{route('staff')}}">Chief Instructor.</a>
</p>
@endsection

@section('from-line')
Sent by Gander Oceanic OCA
@endsection

@section('footer-to-line', $rosterMember->user->fullName('FLC').' ('.$rosterMember->user->email.')')

@section('footer-reason-line')
of the following reason: your status with Gander Oceanic has changed
@endsection
