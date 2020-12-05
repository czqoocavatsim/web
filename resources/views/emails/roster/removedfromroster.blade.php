@extends('layouts.email')

@section('to-line', 'Hi '. $user->fullName('F') . ',')

@section('message-content')
<p>You have been removed from the Gander Oceanic controller roster. This means you cannot control any Gander Oceanic position on the VATSIM network. You may apply to regain your certification at any time in the future.</p>
<p>
    If you believe this is a mistake or have any questions, please contact us via a <a href="TODO: TicketURL">support ticket</a> or email the <a href="{{route('staff')}}">OCA Chief.</a>
</p>
@endsection

@section('from-line')
Sent by Gander Oceanic OCA
@endsection

@section('footer-to-line', $user->fullName('FLC').' ('.$user->email.')')

@section('footer-reason-line')
of the following reason: your status with Gander Oceanic has changed
@endsection
