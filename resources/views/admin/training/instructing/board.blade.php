@extends('admin.training.layouts.main')
@section('training-content')
<h1 class="font-weight-bold blue-text pb-2">Board</h1>
<div class="card-columns">
    @foreach($lists as $list)
        <div class="card p-3 grey lighten-5 shadow-none">
            <h5 class="font-weight-bold">{{$list->name}}</h5>
        </div>
    @endforeach
    @foreach($instructors as $list)
        <div class="card p-3 grey lighten-5 shadow-none">
            <div class="d-flex flex-row align-items-center">
                <img src="{{$list->user->avatar()}}" style="height: 30px; width:30px;margin-right: 10px; border-radius: 50%;">
                <div class="d-flex flex-column align-items-center h-100">
                    <h5 class="mb-0 font-weight-bold">{{$list->user->fullName('F')}}'s Students</h5>
                </div>
            </div>
            @if (count($list->studentsAssigned) > 0)
                <div class="list-group mt-3">
                    @foreach($list->studentsAssigned as $student)
                        <a href="{{route('training.admin.instructing.students.view', $student->student->user->id)}}" class="list-group-item list-group-item-action grey lighten-5">
                            <div class="d-flex flex-column">
                                <p class="mb-0">{{$student->student->user->fullName('FLC')}}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                No students assigned.
            @endif
        </div>
    @endforeach
</div>
@endsection
