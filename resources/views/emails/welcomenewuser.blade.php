@extends('layouts.email')
@section('to-line', 'Hi '. $user->fullName('FLC') . '!')
@section('message-content')
<p>Welcome to Gander Oceanic, we're very excited that you're here!</p>
<p>On our site you can find various resources relating to Oceanic operations in the North Atlantic for both pilots and controllers.  We also have a Discord server which you can access via the myCZQO page on the website.</p>
@endsection
@section('from-line')
Cheers,<br/>
<b>Gary Thomas</b><br>
<b>FIR Director</b>
@endsection
@section('footer-to-line', $user->fullName('FLC').' ('.$user->email.')')
@section('footer-reason-line', 'you just logged into the CZQO website for the first time.')
