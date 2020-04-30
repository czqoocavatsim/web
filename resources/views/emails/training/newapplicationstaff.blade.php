@extends('layouts.email')

@section('to-line', 'Hi,')

@section('message-content')
{{$application->user->fullName('FLC')}} has applied for Gander Oceanic.
@endsection

@section('from-line')
@endsection

@section('footer-to-line', '')

@section('footer-reason-line')
@endsection
