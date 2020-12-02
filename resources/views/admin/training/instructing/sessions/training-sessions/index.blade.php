@extends('admin.training.layouts.main')
@section('training-content')
<h1 class="blue-text pb-2">Training Sessions</h1>

@if($profile = Auth::user()->instructorProfile)
    <h4 class="blue-text mb-3">Your Upcoming Sessions</h4>
    @if(count($profile->upcomingTrainingSessions()) == 0)
        None upcoming!
    @endif
    <div class="list-group z-depth-1 mb-4 rounded">
        @foreach($profile->upcomingTrainingSessions() as $s)
            <a href="{{route('training.admin.instructing.training-sessions.view', $s->id)}}" class="list-group-item list-group-item-action waves-effect">
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

<h4 class="blue-text mt-3">All Sessions</h4>
<table class="table dt table-hover table-bordered">
    <thead>
        <th>Student</th>
        <th>Instructor</th>
        <th>Scheduled Time</th>
        <th>Action</th>
    </thead>
    <tbody>
        @foreach ($sessions as $s)
            <tr>
                <td>{{$s->student->user->fullName('FLC')}}</td>
                <td>{{$s->instructor->user->fullName('FLC')}}</td>
                <td>{{$s->scheduled_time->toDayDateTimeString()}} UTC</td>
                <td>
                    <a class="blue-text" href="#">
                        <i class="fas fa-eye"></i>&nbsp;View
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    $(document).ready(function () {
        $('.table.dt').DataTable();
    })
</script>
@endsection
