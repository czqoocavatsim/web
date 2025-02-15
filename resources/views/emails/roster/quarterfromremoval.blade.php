@extends('layouts.email')

@section('to-line', 'Dear '. $user->fullName('F') . ',')

@section('message-content')
<p>Our records indicate that you currently do not meet the currency requirements for your endorsement with Gander Oceanic.</p>

<p>This is a friendly reminder that you must control a minimum of six hours per calendar year. You currently have {{ floor($currency) }}h {{ str_pad(round(($currency - floor($currency)) * 60), 2, '0', STR_PAD_LEFT) }}m recorded on Gander, Shanwick and New York Positions.</p>

<p> We kindly request that over the remainder of the year, you take the time to connect as one of these positions to remain current within Gander Oceanic.</p>

<p>If you believe this is a mistake or have any questions, please submit a <a href="{{route('my.feedback.new')}}">Controller Certification Ticket</a>. We would be more than happy to answer any questions you may have!</p>
@endsection

@section('from-line')
Sent by Gander Oceanic OCA
@endsection

@section('footer-to-line', $user->fullName('FLC').' ('.$user->email.')')

@section('footer-reason-line')
of Certification Activity Status
@endsection
