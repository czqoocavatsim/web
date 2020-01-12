@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    @include('includes.trainingMenu')
    <div class="container" style="margin-top: 20px;">
        <h1>Training</h1>
        <hr>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        Your Students
                    </div>
                    <div class="card-body">
                        @if ($yourStudents !== null)
                        <div class="list-group">
                            @foreach ($yourStudents as $student)
                            <a href="{{route('training.students.view', $student->id)}}" class="list-group-item d-flex justify-content-between align-items-center">
                                {{$student->user->fullName('FLC')}}
                                {{-- <i class="text-dark">Session planned at {date}</i> --}}
                                @if ($student->status == 0)
                                <span class="badge badge-success">
                                    <h6 class="p-0 m-0">
                                        Open
                                    </h6>
                                </span>
                                @elseif ($student->status == 4)
                                <span class="badge badge-success">
                                    <h6 class="p-0 m-0">
                                        On Hold
                                    </h6>
                                </span>
                                @endif
                            </a>
                            @endforeach
                        </div>
                        @else
                        No students are allocated to you.
                        @endif
                    </div>
                </div>
            </div>
            <div class="col">
            </div>
        </div>
        <br/>
        <h5>Training Calendar</h5>
    </div>
@stop
