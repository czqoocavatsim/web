@extends('layouts.email')
@section('to-line', 'Hi'.$user->fullName('FL'))
@section('message-content')
Listed below is the data that you requested from Gander Oceanic. If you have any questions, please reply to this email.
<hr>
<p style="padding: 10px; border: 1px solid #000;">
{{$json}}
</p>
<p>To view this data easily, search for a JSON formatter online.</p>
@endsection
@section('from-line')
Kind regards,<br/>
<b>Gander Oceanic FIR</b><br>
@endsection
@section('footer-to-line', $user->fullName('FLC').' ('.$user->email.')')
@section('footer-reason-line', 'as they requested a data export')
