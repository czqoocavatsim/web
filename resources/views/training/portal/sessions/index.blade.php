@extends('training.portal.layouts.main')
@section('title', 'Sessions - Training Portal - ')
@section('page-header-title', 'Your sessions')

@section('portal-content')
<h4 class="blue-text mb-3 fw-500">OTS Sessions</h4>
@if(count($otsSessions) == 0)
    None yet!
@endif
<div class="list-group z-depth-1 mb-4 rounded">
    @foreach($otsSessions as $s)
        <a href="{{route('training.portal.sessions.view-ots-session', $s)}}" class="list-group-item list-group-item-action waves-effect">
            <div class="d-flex flex-row w-100 align-items-center h-100">
                <div class="d-flex flex-column h-100">
                    <h5 class="mb-1 mt-2 fw-600">{{$s->scheduled_time->toDayDateTimeString()}} UTC</h5>
                    <p class="mb-0">Assessor: {{$s->instructor->user->fullName('FL')}}<br>Result: {{ucfirst($s->result)}}</p>
                </div>
            </div>
        </a>
    @endforeach
</div>
<h4 class="blue-text mb-3 fw-500">Training Sessions</h4>
@if(count($trainingSessions) == 0)
    None yet!
@endif
<div class="list-group z-depth-1 mb-4 rounded">
    @foreach($trainingSessions as $s)
        <a href="{{route('training.portal.sessions.view-training-session', $s)}}" class="list-group-item list-group-item-action waves-effect">
            <div class="d-flex flex-row w-100 align-items-center h-100">
                <div class="d-flex flex-column h-100">
                    <h5 class="mb-1 mt-2 fw-600">{{$s->scheduled_time->toDayDateTimeString()}} UTC</h5>
                    <p class="mb-0">Instructor: {{$s->instructor->user->fullName('FL')}}</p>
                </div>
            </div>
        </a>
    @endforeach
</div>
@endsection
