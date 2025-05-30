@extends('layouts.email')

@section('to-line', 'Dear '. $u->fullName('F') . ',')

@section('message-content')
<p>Our records indicate that you recently attained a C1 Rating on the VATSIM Network. We wanted to reach out to you and say Congratulations!</p>

<p>Once you have attained 50 Hours on your C1 Rating, we invite you to come and join Gander Oceanic as a Certified Controller. This will give you access to control Gander (CZQO), Shanwick (EGGX), and New York (KZWY) Oceanic Control Areas during Cross the Pond, and during normal network operations.</p>

<p>If you are interested, we invite you to apply via <a href="https://ganderoceanic.ca">Gander Oceanic's Website</a> to begin your training</p>

<p>Enjoy your Enroute Rating!</p>
@endsection

@section('from-line')
Sent by Gander Oceanic OCA
@endsection

@section('footer-to-line', $u->fullName('FLC').' ('.$u->email.')')

@section('footer-reason-line')
of your recent C1 Rating Upgrade on VATSIM
@endsection