@extends('layouts.email')

@section('to-line', 'Hi '. $user->fullName('FLC') . ',')

@section('message-content')
<h2>{{$announcement->title}}</h2>
{{$announcement->html()}}
@endsection

@section('from-line')
Sent by <b>{{$announcement->user->fullName('FLC')}} ({{$announcement->user->staffProfile->position ?? 'No staff position found'}})</b>
@endsection

@section('footer-to-line', $user->fullName('FLC').' ('.$user->email.')')

@section('footer-reason-line')
of the following reason: {{$announcement->reason_for_sending}}
@endsection
