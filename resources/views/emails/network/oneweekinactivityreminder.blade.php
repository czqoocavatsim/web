@extends('layouts.email')

@section('to-line', 'Hi '. $rosterMember->user->fullName('FLC') . ',')

@section('message-content')
<h2>One Week Left - Activity Reminder</h2>
<p>According to our records, you have not yet fulfilled our currency requirement. You require <b>6 hours</b> online controlling on Gander or Shanwick by 1 July.</p>

<p>There is one week remaining in the current cycle, so this email serves only as a reminder in case you may have forgotten or missed our previous email at one month out and two weeks out.<p>

<p>If you have received your certification during the current currency period, your requirement is reduced by one hour per month into the activity requirement you were certified. For example, if you were certified in March, you are only required to do three hours, since there would be only three months left until the end of the currency period. Otherwise, you require six hours.</p>

<p>This is the final reminder we will send. If you do not reach 6 hours of activity controlling Gander or Shanwick by 1 July, you will be marked as inactive.</p>

<p>Please don’t hesitate to contact us should you have any concerns or require leniancy on the requirement. We're here to help!</p>
@endsection

@section('from-line')
Sent automatically by ActivityBot
@endsection

@section('footer-to-line', $rosterMember->user->fullName('FLC').' ('.$rosterMember->user->email.')')

@section('footer-reason-line')
as there is important information in regard to your status with Gander Oceanic
@endsection
