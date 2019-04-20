@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    @include('includes.trainingMenu')
    <div class="container" style="margin-top: 20px;">
        <h1>Instructors</h1>
        <hr>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        Current Instructors
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @foreach($instructors as $instructor)
                            <a href="#" class="list-group-item d-flex justify-content-between align-items-center">
                                {{$instructor->user->fullName('FLC')}}
                                @if(count($instructor->students) < 1)
                                    <span class="badge badge-light badge-pill">
                                        <h6 class="p-0 m-0">
                                             No students
                                        </h6>
                                    </span>
                                @else
                                    <span class="badge badge-primary badge-pill">
                                        <h6 class="p-0 m-0">
                                            {{count($instructor->students)}}
                                        </h6>
                                    </span>
                                @endif
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-header">Actions</div>
                    <div class="card-body">
                        <a href="#" data-toggle="modal" data-target="#addInstructorModal" class="card-link">Add Instructor</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addInstructorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add an instructor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('training.instructors.add')}}" method="POST">
                    {{ csrf_field() }}
                    <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">CID</label>
                        <input required type="number" name="cid" id="searchBox" class="form-control" placeholder="Enter an exact CID" maxlength="7">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Qualification</label>
                        <input required type="text" name="qualification" id="searchBox" class="form-control" placeholder="e.g. Assessor">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input required type="email" name="email" class="form-control">
                        <small>This email will be publically available, so the instructor's CERT email remains hidden.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Add">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        //$('#addInstructorModal').modal('show')
    </script>
@stop
