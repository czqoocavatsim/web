@extends('layouts.email')

@section('to-line', 'Hi,')

@section('message-content')
<p>{{$application->user->fullName('FLC')}} has sent a comment on their application.</p>
<b>Comment</b>
<p>
    {{$comment->content}}
</p>
<hr>
<br/>
You can view their application <a href="{{route('training.admin.applications.view', $application->reference_id)}}">here.</a>
@endsection

@section('from-line')
@endsection

@section('footer-to-line', '')

@section('footer-reason-line')
you are the OCA Chief / Deputy Chief.
@endsection
