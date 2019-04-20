@extends('layouts.email')

@section('title')
    <b>New Reply to ticket #{{$ticket->ticket_id}}</b>
@stop

@section('to')

    <strong>Hi there,</strong>
@stop

@section('content')
    <a href="https://czqo.vatcan.ca/dashboard/users/{{$ticketReply->user_id}}">
        {{\App\User::find($ticketReply->user_id)->fname}} {{\App\User::find($ticketReply->user_id)->lname}} {{\App\User::find($ticketReply->user_id)->id}}
    </a> has replied to your ticket. View it <a href="https://czqo.vatcan.ca/dashboard/tickets/{{$ticket->ticket_id}}">here.</a>
@stop

@section('end')
    <b>Gander Oceanic Core</b>
@stop