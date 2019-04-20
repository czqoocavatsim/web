@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    @include('includes.trainingMenu')
    <div class="container" style="margin-top: 20px;">
        <h1>Current Students</h1>
        <hr>
        <table id="dataTable" class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">CID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Assigned to</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($students as $student)
            <tr>
                <th scope="row">{{$student->user->id}}</th>
                <td>
                    <a href="{{route('training.students.view', $student->id)}}">
                        {{$student->user->fullName('FL')}}
                    </a>
                </td>
                <td>
                    @if ($student->instructor !== null)
                        <a href="#">
                            {{$student->instructor->user->fullName('FLC')}}
                        </a>
                    @else
                        No instructor assigned
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
    </div>
@stop