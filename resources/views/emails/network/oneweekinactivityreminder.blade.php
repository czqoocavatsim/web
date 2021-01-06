@extends('layouts.email')

@section('to-line', 'Hi '. $rosterMember->user->fullName('FLC') . ',')

@section('message-content')
<h2>One Week Left - Activity Reminder</h2>
<p>According to our records, you have not yet fulfilled our currency requirement. You require <b>3 hours</b> online controlling on Gander or Shanwick by the end of the quarter, otherwise you will be marked inactive.</p>

<p>There is one week remaining in the current quarter, so this email serves only as a reminder in case you may have forgotten, or missed last week's email.<p>

<p>Once again, if you have received your certification during this current quarter, then you are exempt from this quarter's requirement.</p>

<p>Please don’t hesitate to contact us should you have any concerns, or if you need us to make an accommodation. We're here to help!</p>
@endsection

@section('from-line')
Sent automatically by ActivityBot
@endsection

@section('footer-to-line', $rosterMember->user->fullName('FLC').' ('.$rosterMember->user->email.')')

@section('footer-reason-line')
there is important information in regard to your status with Gander Oceanic.
@endsection
