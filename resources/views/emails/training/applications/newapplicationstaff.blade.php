@extends('layouts.email')

@section('to-line', 'Hi,')

@section('message-content')
<p>{{$application->user->fullName('FLC')}} has applied for Gander Oceanic.</p>
<ul>
    <li>Reference ID: {{$application->reference_id}}</li>
    <li>Name: {{$application->user->fullName('FLC')}}</li>
    <li>Rating/Division: {{$application->user->rating_GRP}}/{{$application->user->division_name}}</li>
</ul>
<b>Applicant Statement</b>
<p>
    {!! html_entity_decode($application->applicant_statement) !!}
</p>
<hr>
<br/>
You can view their application <a href="{{route('training.admin.applications.view', $application->reference_id)}}">here.</a>
@endsection

@section('from-line')
@endsection

@section('footer-to-line', '')

@section('footer-reason-line')
@endsection
