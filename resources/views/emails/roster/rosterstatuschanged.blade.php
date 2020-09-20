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
    If you believe this is a mistake or have any questions, please contact us via a <a href="{{route('tickets.index')}}">support ticket</a> or email the <a href="{{route('staff.index')}}">Chief Instructor.</a>
</p>
@endsection

@section('from-line')
Sent by <b>{{$announcement->user->fullName('FLC')}} ({{$announcement->user->staffProfile->position ?? 'No staff position found'}})</b>
@endsection

@section('footer-to-line', $user->fullName('FLC').' ('.$user->email.')')

@section('footer-reason-line')
of the following reason: {{$announcement->reason_for_sending}}
@endsection
