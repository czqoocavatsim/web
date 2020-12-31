@extends('admin.training.layouts.main')
@section('training-content')
<h1 class="blue-text pb-2 font-weight-bold">Your Upcoming Sessions</h1>

@if($profile = Auth::user()->instructorProfile)
    @if($profile->assessor)
        <h4 class="blue-text mb-3 fw-500">OTS Sessions</h4>
        @if(count($profile->upcomingOTSSessions()) == 0)
            None upcoming!
        @endif
        <div class="list-group z-depth-1 mb-4 rounded">
            @foreach($profile->upcomingOTSSessions() as $s)
                <a href="#" class="list-group-item list-group-item-action waves-effect">
                    <div class="d-flex flex-row w-100 align-items-center h-100">
                        <img src="{{$s->student->user->avatar()}}" style="height: 50px; width:50px;margin-right: 15px; margin-bottom: 3px; border-radius: 50%;">
                        <div class="d-flex flex-column h-100">
                            <h5 class="mb-1">{{$s->student->user->fullName('FLC')}}</h5>
                            {{$s->scheduled_time->toDayDateTimeString()}} UTC
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
    <h4 class="blue-text mb-3 fw-500">Training Sessions</h4>
    @if(count($profile->upcomingTrainingSessions()) == 0)
        None upcoming!
    @endif
    <div class="list-group z-depth-1 mb-4 rounded">
        @foreach($profile->upcomingTrainingSessions() as $s)
            <a href="{{route('training.admin.instructing.training-sessions.view', $s)}}" class="list-group-item list-group-item-action waves-effect">
                <div class="d-flex flex-row w-100 align-items-center h-100">
                    <img src="{{$s->student->user->avatar()}}" style="height: 50px; width:50px;margin-right: 15px; margin-bottom: 3px; border-radius: 50%;">
                    <div class="d-flex flex-column h-100">
                        <h5 class="mb-1 fw-500">{{$s->student->user->fullName('FLC')}}</h5>
                        {{$s->scheduled_time->toDayDateTimeString()}} UTC
                    </div>
                </div>
            </a>
        @endforeach
    </div>
@endif

@endsection
