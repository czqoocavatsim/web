@extends('admin.training.layouts.main')
@section('training-content')
    <a href="{{route('training.admin.instructing.instructors')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Instructors</a>
    <div class="d-flex flex-row align-items-center">
        <img src="{{$instructor->user->avatar()}}" style="height: 50px; width:50px;margin-right: 15px; margin-bottom: 3px; border-radius: 50%;">
        <div>
            <h2 class="blue-text font-weight-bold mt-2 mb-1">{{$instructor->user->fullName('FLC')}}</h2>
            <h5>{{$instructor->staffPageTagline()}}</h5>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-6">
            <h5 class="font-weight-bold blue-text">Assigned Students</h5>
            <div class="list-group">
                @foreach($instructor->studentsAssigned as $student)
                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex flex-row w-100 align-items-center h-100">
                            <img src="{{$student->student->user->avatar()}}" style="height: 30px; width:30px;margin-right: 15px; border-radius: 50%;">
                            <div class="d-flex flex-column align-items-center h-100">
                                <h5>{{$student->student->user->fullName('FLC')}}</h5>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
