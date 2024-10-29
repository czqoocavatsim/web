@extends('layouts.email')

@section('to-line', 'Dear '. $user->fullName('F') . ',')

@section('message-content')
<p>Our records indicate that you currently do not meet the currency requirements for your endorsement with Gander Oceanic</p>

<p>This is a friendly reminder that you must control a minimum of 1 hour per calendar year. You currently have {{round($currency * 60)}} minutes recorded on Gander & Shanwick Positions.</p>

<p>If you believe this is a mistake or have any questions, please email the <a href="{{route('staff')}}">Chief Instructor</a> with your query.</p>
@endsection

@section('from-line')
Sent by Gander Oceanic OCA
@endsection

@section('footer-to-line', $user->fullName('FLC').' ('.$user->email.')')

@section('footer-reason-line')
of Currency Policy Requirements
@endsection