@extends('layouts.email')
@section('to-line', 'Hi '. $user->fullName('FLC') . '!')
@section('message-content')
Welcome to Gander Oceanic!
@endsection
@section('from-line')
Kind regards,<br/>
<b>Andrew Ogden</b><br>
<b>FIR Chief</b>
@endsection
@section('footer-to-line', $user->fullName('FLC').' ('.$user->email.')')
@section('footer-reason-line', 'as they just logged into the CZQO website for the first time.')
