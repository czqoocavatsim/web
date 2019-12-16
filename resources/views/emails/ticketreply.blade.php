@extends('layouts.email')

@section('to-line', 'Hi '. $user->fullName('FLC') . ',')

@section('message-content')
<p>{{$reply->user->fullName('FLC')}} has replied to ticket {{$ticket->title}} at {{$reply->submission_time_pretty()}}.</p>
<hr>
{{$reply->html()}}
<hr>
<a href="{{route('tickets.viewticket', $ticket->ticket_id)}}">View the ticket here.</a>
@endsection

@section('footer-to-line', $user->fullName('FLC').' ('.$user->email.')')

@section('footer-reason-line')
as they are engaged in an open ticket and a reply was received.
@endsection
