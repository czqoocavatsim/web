@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    @include('includes.trainingMenu')
    <div class="container" style="margin-top: 20px;">
        <h1>Student: {{$student->user->fullName('FLC')}}</h1>
        <hr>
        <div class="row">
            <div class="col">
                <h5>Personal Details</h5>
                <div class="card">                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title">{{$student->user->fullName('FLC')}}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">
                                    @switch ($student->user->rating)
                                    @case('INA')
                                    Inactive (INA)
                                    @break
                                    @case('OBS')
                                    Pilot/Observer (OBS)
                                    @break
                                    @case('S1')
                                    Ground Controller (S1)
                                    @break
                                    @case('S2')
                                    Tower Controller (S2)
                                    @break
                                    @case('S3')
                                    TMA Controller (S3)
                                    @break
                                    @case('C1')
                                    Enroute Controller (C1)
                                    @break
                                    @case('C3')
                                    Senior Controller (C3)
                                    @break
                                    @case('I1')
                                    Instructor (I1)
                                    @break
                                    @case('I3')
                                    Senior Instructor (I3)
                                    @break
                                    @case('SUP')
                                    Supervisor (SUP)
                                    @break
                                    @case('ADM')
                                    Administrator (ADM)
                                    @break
                                    @endswitch
                                </h6>
                                <ul>
                                    <li>Division: {{ $student->user->division }}</li>
                                    @if ($student->user->permissions == 0)
                                        <li>Status: Not Certified/Guest</li>
                                    @elseif ($student->user->permissions == 1)
                                        <li>Status: Controller</li>
                                    @elseif ($student->user->permissions == 2)
                                        <li>Status: Instructor</li>
                                    @elseif ($student->user->permissions == 3)
                                        <li>Status: Director</li>
                                    @elseif ($student->user->permissions == 4)
                                        <li>Status: Director (Executive ZQO1/2)</li>
                                    @endif
                                </ul>
                            </div>
                            <div class="col">
                                <h5 class="card-title">Avatar</h5>
                                <div class="text-center">
                                    <img src="{{$student->user->avatar}}" style="width: 125px; height: 125px; margin-bottom: 10px; border-radius: 50%;">
                                </div>
                                <!--TODO: add delete-->
                            </div>
                        </div>
                    </div>
                    <hr class="my-0">
                    <div class="card-footer">
                        <a href="{{route('users.viewprofile', $student->user->id)}}" class="card-link">View User Profile</a>
                    </div>
                </div>
            </div>
            <div class="col">
                <h5>Primary Info</h5>
                <div class="card">
                    <div class="card-body">
                        <h5>Training Status</h5>
                        @if ($student->status == 0)
                        <span class="badge badge-success">
                            <h3 class="p-0 m-0">
                                Open
                            </h3>
                        </span><br/>
                        The student's training is 'Open'. This means the student is actively engaging in training at CZQO.
                        @elseif ($student->status == 3)
                        <span class="badge badge-dark">
                            <h3 class="p-0 m-0">
                                On Hold
                            </h3>
                        </span><br/>
                        The student's training is on hold.
                        @elseif ($student->status == 1)
                        <span class="badge badge-primary">
                            <h3 class="p-0 m-0">
                                Completed
                            </h3>
                        </span><br/>
                        The student's training was completed successfully. 
                        @else 
                        <span class="badge badge-danger">
                            <h3 class="p-0 m-0">
                                Closed
                            </h3>
                        </span><br/>
                        The student's training was closed.
                        @endif
                        <h5 class="mt-3">Assigned Instructor</h5>
                        @if ($student->instructor)
                        <a href="#">
                            {{$student->instructor->user->fullName('FLC')}}
                        </a>
                        @else
                            No instructor assigned
                        @endif
                        <h5 class="mt-3">Application</h5>
                        Accepted at {{$student->application->processed_at}} by {{\App\User::find($student->application->processed_by)->fullName('FLC')}}
                        @if (Auth::user()->permissions >= 3)
                        <br/>
                        <a href="{{route('training.viewapplication', $student->application->application_id)}}">View application here</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <br/>
        <br/>
        <div class="row">
            <div class="col">
                <h5>Actions</h5>
                <div class="card">
                    <div class="card-body">
                        <h6>Change Status</h6>
                        <form action="{{route('training.students.setstatus', $student->id)}}" method="POST">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col">
                                    <select name="status" required class="custom-select">
                                        <option selected="" value="" hidden>Please choose one..</option>
                                        <option value="1">Open</option>
                                        <option value="2">Completed</option>
                                        <option value="3">Terminated (Closed)</option>
                                        <option value="4">On Hold</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <input type="submit" value="Save" class="btn btn-link"></input>
                                </div>
                            </div>
                        </form>
                        <br/>
                        <h6>Instructor</h6>
                        <form action="{{route('training.students.assigninstructor', $student->id)}}" method="POST">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col">
                                    <select name="instructor" required class="custom-select">
                                        <option value="" selected="" hidden>Please choose one..</option>
                                        @foreach ($instructors as $instructor)
                                        <option value="{{$instructor->id}}">{{$instructor->user->fullName('FLC')}}</option>
                                        @endforeach
                                        <option value="unassign">No instructor/unassign</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <input type="submit" value="Save" class="btn btn-link"></input>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col">
                <h5>Instructing Sessions</h5>
                <div class="card">
                    <div class="card-body">
                        @if (count($student->instructingSessions) >= 1)
                        @else
                        None found!
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop