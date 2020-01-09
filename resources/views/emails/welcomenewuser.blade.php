@extends('layouts.email')
@section('to-line', 'Hi '. $user->fullName('FLC') . '!')
@section('message-content')
Welcome to Gander Oceanic, we're super excited to have you on board!
<br/>

We have tonnes of resources on our website for pilots and controllers alike, feel free to check them out! We look forward to seeing you in our skies very soon :)
@endsection
@section('from-line')
Kind regards,<br/>
<b>Andrew Ogden</b><br>
<b>FIR Chief</b>
@endsection
@section('footer-to-line', $user->fullName('FLC').' ('.$user->email.')')
@section('footer-reason-line', 'as they just logged into the CZQO website for the first time.')
