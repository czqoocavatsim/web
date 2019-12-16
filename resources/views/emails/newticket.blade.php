@extends('layouts.email')

@section('to-line', 'Hi '. $user->fullName('FLC') . ',')

@section('message-content')
<p>{{$ticket->user->fullName('FLC')}} has opened a ticket titled {{$ticket->title}} at {{$ticket->submission_time_pretty()}}.</p>
<hr>
{{$ticket->html()}}
<hr>
<a href="{{route('tickets.viewticket', $ticket->ticket_id)}}">View the ticket here.</a>
@endsection

@section('footer-to-line', $user->fullName('FLC').' ('.$user->email.')')

@section('footer-reason-line')
as they are a staff member of Gander Oceanic.
@endsection
