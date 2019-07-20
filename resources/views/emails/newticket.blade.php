@extends('layouts.email')

@section('title')
    <b>New Ticket Opened</b>
@stop

@section('to')

    <strong>Hi there,</strong>
@stop

@section('content')
    <a href="https://czqo.vatcan.ca/dashboard/users/{{$ticket->user_id}}">
        {{\App\User::find($ticket->user_id)->fullName('FLC')}}
    </a> has opened a new ticket with the subject '{{$ticket->title}}'. View it <a href="https://czqo.vatcan.ca/dashboard/tickets/{{$ticket->ticket_id}}">here.</a>
@stop

@section('end')
    <b>Gander Oceanic Core</b>
@stop