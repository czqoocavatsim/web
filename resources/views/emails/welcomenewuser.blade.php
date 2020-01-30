@extends('layouts.email')
@section('to-line', 'Hi '. $user->fullName('FLC') . '!')
@section('message-content')
<p>Welcome to Gander Oceanic, we're very excited that you're here!</p>
<p>On our site you can find many resources pertaining to Oceanic operations in the North Atlantic for both pilots and controllers. Please don't hesitate to contact me should you have any questions about us!</p>
@endsection
@section('from-line')
Cheers,<br/>
<b>Andrew Ogden</b><br>
<b>FIR Chief</b>
@endsection
@section('footer-to-line', $user->fullName('FLC').' ('.$user->email.')')
@section('footer-reason-line', 'as they just logged into the CZQO website for the first time.')
