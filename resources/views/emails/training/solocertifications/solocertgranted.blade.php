@extends('layouts.email')

@section('to-line', 'Hi '. $cert->rosterMember->user->fullName('F') . ',')

@section('message-content')
<p>You have been granted a solo certification.</p>
<p>
    <ul>
        <li>Expiry: {{$cert->expires->toDateString()}}</li>
        <li>Granted by: {{$cert->instructor->fullName('FLC')}}</li>
    </ul>
</p>
<p>
    If you believe this is a mistake or have any questions, please email the <a href="{{route('staff')}}">Events & Training Director.</a>
</p>
@endsection

@section('from-line')
Sent by Gander Oceanic OCA
@endsection

@section('footer-to-line', $cert->rosterMember->user->fullName('FLC').' ('.$cert->rosterMember->user->email.')')

@section('footer-reason-line')
your status with Gander Oceanic has changed.
@endsection
