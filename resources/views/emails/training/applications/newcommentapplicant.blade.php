@extends('layouts.email')

@section('to-line', 'Hi '. $application->user->fullName('F') . ',')

@section('message-content')
<p>{{$comment->user->fullName('FLC')}} has left a comment on your application for Gander Oceanic. They may be asking for more information about your application or notifying you of your application status.</p>
<hr>
<h5>Comment</h5>
<p>{{$comment->content}}</p>
<hr>
You can respond to their comment and view your application <a href="{{route('training.applications.show', $application->reference_id)}}">here.</a>
@endsection

@section('from-line')
Sent by Gander Oceanic OCA
@endsection

@section('footer-to-line', $application->user->fullName('FLC').' ('.$application->user->email.')')

@section('footer-reason-line')
there is an update on your application to become a Gander Oceanic Controller.
@endsection
