@extends('admin.training.layouts.main')
@section('training-content')
<a href="{{route('training.admin.instructing.training-sessions')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Training Sessions</a>
<div class="d-flex flex-row align-items-center mt-3">
    <img src="{{$session->instructor->user->avatar()}}" class="z-depth-1" style="height: 50px; width:50px;margin-right: 15px; margin-bottom: 3px; border-radius: 50%;">
    <img src="{{$session->student->user->avatar()}}" class="z-depth-1" style="height: 50px; z-index: 50; margin-left: -30px; width:50px;margin-right: 15px; margin-bottom: 3px; border-radius: 50%;">
    <div>
        <h2 class="blue-text mt-2 mb-1">Training Session with {{$session->student->user->fullName('F')}}</h2>
        <h5>
            Scheduled for {{$session->scheduled_time->toDayDateTimeString()}}
        </h5>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <h5 class="blue-text">Scheduled start</h5>
        <h5 class="d-flex flex-row align-items-center">
            {{$session->scheduled_time->toDayDateTimeString()}}
            <a data-toggle="modal" data-target="#editTimeModal">
                <i class="fas fa-edit ml-2 text-muted"></i>
            </a>
        </h5>
        <h5 class="mt-4 blue-text">Position</h5>
        @if ($session->position)
            <div class="list-group-item z-depth-1 rounded">
                <div class="d-flex flex-row w-100 align-items-center h-100 justify-content-between">
                    <div class="d-flex flex-row align-items-center">
                        <i class="fas fa-wifi fa-fw mr-2"></i>
                        <div class="d-flex flex-column align-items-center h-100">
                            <h5 class="mb-0">{{$session->position->identifier}}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="list-unstyled mt-3">
                @can('edit training sessions')
                <li class="mb-2">
                    <a style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Remove position</span></a>
                </li>
                @endcan
            </ul>
        @else
            <div class="list-group-item z-depth-1 rounded">
                <p>Not assigned.</p>
                <ul class="list-unstyled mt-3 mb-0">
                    @can('edit training sessions')
                    <li>
                        <a style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Assign position</span></a>
                    </li>
                    @endcan
                </ul>
            </div>
        @endif
    </div>
    <div class="col-md-6">
        <h5 class="blue-text">Instructor</h5>
        <a href="{{route('training.admin.instructing.instructors.view', $session->instructor->user->id)}}" class="list-group-item list-group-item-action z-depth-1 rounded waves-effect">
            <div class="d-flex flex-row w-100 align-items-center h-100 justify-content-between">
                <div class="d-flex flex-row align-items-center">
                    <img src="{{$session->instructor->user->avatar()}}" style="height: 30px; width:30px;margin-right: 15px; border-radius: 50%;">
                    <div class="d-flex flex-column align-items-center h-100">
                        <h5 class="mb-0">{{$session->instructor->user->fullName('FL')}}</h5>
                    </div>
                </div>
            </div>
        </a>
        <ul class="list-unstyled mt-3">
            @can('edit training sessions')
            <li class="mb-2">
                <a data-target="#reassignInstructorModal" data-toggle="modal" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Assign to another instructor</span></a>
            </li>
            @endcan
        </ul>
        <h5 class="blue-text mt-4">Student</h5>
        <a href="{{route('training.admin.instructing.students.view', $session->student->user->id)}}" class="list-group-item list-group-item-action z-depth-1 rounded waves-effect">
            <div class="d-flex flex-row w-100 align-items-center h-100 justify-content-between">
                <div class="d-flex flex-row align-items-center">
                    <img src="{{$session->student->user->avatar()}}" style="height: 30px; width:30px;margin-right: 15px; border-radius: 50%;">
                    <div class="d-flex flex-column align-items-left h-100">
                        <h5 class="mb-0">{{$session->student->user->fullName('FLC')}}</h5>
                        <div class="d-flex flex-row">
                            @foreach($session->student->labels as $label)
                                <span class="mr-2">
                                    {{$label->label()->labelHtml()}}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>


<!--Edit time modal-->
<div class="modal fade" id="editTimeModal" role="dialog">
    <div class="modal-dialog modal-dialog-centered model-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change scheduled time</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('training.admin.instructing.training-sessions.edit.time', $session->id)}}" method="POST">
                @csrf
                <div class="modal-body">
                    @if($errors->editTimeErrors->any())
                        <div class="alert alert-danger">
                            <h4>There were errors</h4>
                            <ul class="pl-0 ml-0 list-unstyled">
                                @foreach ($errors->editTimeErrors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <label>New time</label>
                    <input type="datetime" name="new_time" class="form-control border pl-3 flatpickr" id="new_time">
                    <script>
                        flatpickr('#new_time', {
                            enableTime: true,
                            noCalendar: false,
                            dateFormat: "Y-m-d H:i",
                            time_24hr: true,
                            defaultTime: "{{$session->scheduled_time->format('Y-m-d H:i')}}"
                        });
                    </script>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Dismiss</button>
                    <button class="btn btn-success">Change</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--Start assign instructor modal-->
<div class="modal fade" id="reassignInstructorModal" role="dialog">
    <div class="modal-dialog modal-dialog-centered model-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reassign instructor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('training.admin.instructing.training-sessions.edit.instructor', $session->id)}}" method="POST">
                @csrf
                <div class="modal-body">
                    @if($errors->reassignInstructorErrors->any())
                        <div class="alert alert-danger">
                            <h4>There were errors</h4>
                            <ul class="pl-0 ml-0 list-unstyled">
                                @foreach ($errors->reassignInstructorErrors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="">Select instructor</label>
                        <select name="instructor_id" class="form-control">
                            <option hidden>Select one..</option>
                            @foreach ($instructors as $i)
                                <option value="{{$i->id}}">{{$i->user->fullName('FLC')}} - {{$i->staffPageTagline()}}</option>
                            @endforeach
                        </select>
                    </div>
                    <p>The assigned Instructor will be notified of the assignment and the session's details. It will be their responsibility to establish contact with the student.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Dismiss</button>
                    <button class="btn btn-success">Assign</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $.urlParam = function(name){
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        return results[1] || 0;
    }

    if ($.urlParam('editTimeModal') && $.urlParam('editTimeModal') == '1') {
        $("#editTimeModal").modal();
    }

    if ($.urlParam('reassignInstructorModal') == '1') {
        $("#reassignInstructorModal").modal();
    }

</script>

@endsection
