@extends('admin.training.layouts.main')
@section('title', 'Board - Instructing - ')
@section('training-content')
<h1 class="blue-text mb-4">Board</h1>
<h4 class="blue-text mb-4">Student Status</h4>
<div class="row">
    @foreach($lists as $list)
    <div class="col-lg-4">
        <div class="card p-3 z-depth-1 shadow-none mb-3" style="min-height: 160px;">
            <h5><i class="fa fa-circle fa-fw {{$list->colour}}-text"></i>&nbsp;{{$list->name}}</h5>
            @if (count($list->students) > 0)
                <div class="list-group mt-3">
                    @foreach($list->students as $student)
                        <a href="{{route('training.admin.instructing.students.view', $student->student->user->id)}}" class="list-group-item rounded list-group-item-action waves-effect">
                            <div class="d-flex flex-column">
                                <p class="mb-1">{{$student->student->created_at->format('d M')}} - {{$student->student->user->fullName('FL')}}</p>
                                <div class="d-flex flex-row">
                                    @foreach($student->student->labels as $label)
                                        <div class="mr-1 pb-0">
                                            <span class="badge shadow-none {{$label->label()->colour}}" style="height: 8px; width: 25px;">&nbsp;</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                No students with this label.
            @endif
        </div>
    </div>
    @endforeach
</div>

<div class="row">
    <h4 class="blue-text mb-4">Instructor View</h4>
    @foreach($instructors as $list)
    <div class="col-lg-4">
        <div class="card p-4 z-depth-1 shadow-none mb-3" style="min-height: 160px;">
            <div class="d-flex flex-row align-items-center">
                <a title="View Instructor Profile" href="{{route('training.admin.instructing.instructors.view', $list->user_id)}}"><img src="{{$list->user->avatar()}}" style="height: 30px; width:30px;margin-right: 10px; border-radius: 50%;"></a>
                <div class="d-flex flex-column align-items-center h-100">
                    <h5 class="mb-0">{{$list->user->fullName('F')}}'s Students</h5>
                </div>
            </div>
            @if (count($list->studentsAssigned) > 0)
                <div class="list-group mt-3">
                    @foreach($list->studentsAssigned as $student)
                        <a href="{{route('training.admin.instructing.students.view', $student->student->user->id)}}" class="list-group-item rounded list-group-item-action waves-effect">
                            <div class="d-flex flex-column">
                                <p class="mb-0">{{$student->student->created_at->format('d M')}} - {{$student->student->user->fullName('FL')}}</p>
                                <div class="d-flex flex-row">
                                    @foreach($student->student->labels as $label)
                                        <div class="mr-1 pb-0">
                                            <span class="badge shadow-none {{$label->label()->colour}}" style="height: 8px; width: 25px;">&nbsp;</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="mt-3">
                    No students assigned.
                </p>
            @endif
        </div>
    </div>
    </div>
    @endforeach
@endsection
