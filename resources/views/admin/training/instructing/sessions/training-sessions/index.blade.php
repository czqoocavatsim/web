@extends('admin.training.layouts.main')
@section('title', 'Training Sessions - Instructing - ')
@section('training-content')
<h1 class="blue-text pb-2 font-weight-bold">Training Sessions</h1>

@if($profile = Auth::user()->instructorProfile)
    <h4 class="blue-text mb-3 fw-500">Your Upcoming Sessions</h4>
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

<h4 class="blue-text mt-3 fw-500">All Sessions</h4>
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
<ul class="list-unstyled mt-5">
    @can('edit students')
    <li class="mb-2 fw-500">
        <a href="" data-toggle="modal" data-target="#createSessionModal" class="blue-text" style="font-size: 1.1em;"><i class="fas fa-plus"></i>&nbsp;&nbsp;Create a training session</a>
    </li>
    @endcan
</ul>

<!--Start create session modal-->
<div class="modal fade" id="createSessionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create training session</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('training.admin.instructing.training-sessions.create')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>You can also create sessions through your student's profile.</p>
                    @if($errors->createSessionErrors->any())
                    <div class="alert alert-danger">
                        <h4>There were errors</h4>
                        <ul class="pl-0 ml-0 list-unstyled">
                            @foreach ($errors->createSessionErrors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="form-group mt-4">
                        <label for="">Student (those assigned to you)</label>
                        <select required name="student_id" id="" class="form-control">
                            <option hidden>Please select one...</option>
                            @foreach(Auth::user()->instructorProfile->studentsAssigned as $student)
                            <option value="{{$student->student->id}}">{{$student->student->user->fullName('FLC')}} @foreach($student->student->labels as $label) - {{$label->label()->name}} @endforeach</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Scheduled time</label>
                        <input type="datetime" name="scheduled_time" class="form-control flatpickr" id="createSessionTimePicker">
                    </div>
                    <p class="mt-4 mb-0 rounded bg-light p-3">Creating this session will notify the student and all Instructors via Discord.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Create">
                </div>
            </form>
        </div>
    </div>
</div>
<!--End create session modal-->

<script>
    $(document).ready(function () {
        $('.table.dt').DataTable();

    })
    flatpickr('#createSessionTimePicker', {
        time_24hr: true,
        enableTime: true,
        dateFormat: "Y-m-d H:i",
    });

    $.urlParam = function(name){
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        return results[1] || 0;
    }

    if ($.urlParam('createSessionModal') && $.urlParam('createSessionModal') == '1') {
        $("#createSessionModal").modal();
    }
</script>
@endsection
