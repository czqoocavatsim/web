@extends('admin.training.layouts.main')
@section('title', 'Dashboard - Training - ')
@section('training-content')
<h1 class="blue-text mb-4 font-weight-bold">Your Students</h1>
<div class="list-group z-depth-1 rounded">
    @foreach($students as $student)
        <a href="{{route('training.admin.instructing.students.view', $student->user_id)}}" class="list-group-item list-group-item-action waves-effect">
            <div class="d-flex flex-row w-100 align-items-center h-100">
                <img src="{{$student->user->avatar()}}" style="height: 50px; width:50px;margin-right: 15px; margin-bottom: 3px; border-radius: 50%;">
                <div class="d-flex flex-column h-100">
                    <h5 class="mb-1">{{$student->user->fullName('FLC')}}</h5>
                    <h5>
                        @foreach($student->labels as $label)
                            <span class="mr-2">
                                {{$label->label()->labelHtml()}}
                            </span>
                        @endforeach
                    </h5>
                </div>
            </div>
        </a>
    @endforeach
</div>
@endsection
