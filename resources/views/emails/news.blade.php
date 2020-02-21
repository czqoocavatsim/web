@extends('layouts.email')

@section('to-line', 'Hi '. $user->fullName('FLC') . ',')

@section('message-content')
<h2>{{$news->title}}</h2>
{{$news->html()}}
@endsection

@section('from-line')
@if ($news->show_author)
Sent by <b>{{$news->user->fullName('FLC')}} ({{$news->user->staffProfile->position}})</b>
@else
Sent by the Gander Oceanic Staff Team
@endif
@endsection

@section('footer-to-line', $user->fullName('FLC').' ('.$user->email.')')

@section('footer-reason-line')
@if ($news->email_level == 1)
as they are an oceanic controller on the CZQO controller roster
@elseif ($news->email_level == 2)
as they hold an account on the CZQO website and have subscribed to emails
@elseif ($news->email_level == 3)
as the hold an account on the CZQO website.
@endif
@endsection
