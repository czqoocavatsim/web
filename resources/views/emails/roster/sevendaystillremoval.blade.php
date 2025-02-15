@extends('layouts.email')

@section('to-line', 'Dear '. $user->fullName('F') . ',')

@section('message-content')
<p>Our records indicate that you currently do not meet the currency requirements for your endorsement with Gander Oceanic</p>

<p>This is a friendly reminder that you must control a minimum of six hours per calendar year. You currently have {{ floor($currency) }}h {{ str_pad(round(($currency - floor($currency)) * 60), 2, '0', STR_PAD_LEFT) }}m recorded on Gander, Shanwick and New York Positions.</p>

<p><b>You have Seven Days to meet the currency requirement, or you will be removed from the Gander Oceanic Roster.</b></p>

<p>If you believe this is a mistake or have any questions, please submit a <a href="{{route('my.feedback.new')}}">Controller Certification Ticket</a>. We would be more than happy to answer any questions you may have!</p>
@endsection

@section('from-line')
Sent by Gander Oceanic OCA
@endsection

@section('footer-to-line', $user->fullName('FLC').' ('.$user->email.')')

@section('footer-reason-line')
of Currency Policy Requirements
@endsection