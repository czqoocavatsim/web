@extends('layouts.email')

@section('to-line', 'Hi '. $user->fullName('FLC') . ',')

@section('message-content')
<p>You have been banned from the Gander Oceanic Discord server.</p>
<p>Reason: {{$ban->reasonHtml()}}</p>
<p>The ban will expire on {{$ban->end_time->toDayDateTimeString()}}. To appeal, email the FIR Chief.</p>
@endsection

@section('footer-to-line', $user->fullName('FLC').' ('.$user->email.')')

@section('footer-reason-line')
your membership with Gander Oceanic has been affected.
@endsection
