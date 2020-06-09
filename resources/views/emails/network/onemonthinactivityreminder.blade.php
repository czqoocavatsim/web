@extends('layouts.email')

@section('to-line', 'Hi '. $rosterMember->user->fullName('FLC') . ',')

@section('message-content')
<h2>Activity Reminder</h2>
<p>According to our records, you have not yet fulfilled our currency requirement. You require <b>6 hours</b> online controlling on Gander or Shanwick by 1 July.</p>

<p>There is one month remaining in the current cycle, so this email serves only as a reminder in case you may have forgotten.<p>

<p>If you have received your certification during the current currency period, your requirement is reduced by one hour per month into the activity requirement you were certified. For example, if you were certified in March, you are only required to do three hours, since there would be only three months left until the end of the currency period. Otherwise, you require six hours.</p>

<p>You will receive further reminders at two weeks and one week from 1 July if you have not hit the activity requirement by those times.</p>

<p>Please don’t hesitate to contact us should you have any concerns or require leniancy on the requirement. We're here to help!</p>
@endsection

@section('from-line')
Sent automatically by ActivityBot
@endsection

@section('footer-to-line', $rosterMember->user->fullName('FLC').' ('.$rosterMember->user->email.')')

@section('footer-reason-line')
as there is important information in regard to your status with Gander Oceanic
@endsection
