@extends('admin.training.layouts.main')
@section('title', "Student {$student->user->fullName('FLC')} - ")
@section('training-content')
    <a href="{{route('training.admin.instructing.students')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Students</a>
    <div class="d-flex flex-row align-items-center mt-3">
        <img src="{{$student->user->avatar()}}" style="height: 50px; width:50px;margin-right: 15px; margin-bottom: 3px; border-radius: 50%;">
        <div>
            <h2 class="blue-text mt-2 mb-1">{{$student->user->fullName('FLC')}}</h2>
            <h5>
                @foreach($student->labels as $label)
                    <span class="mr-2">
                        {{$label->label()->labelHtml()}}
                    </span>
                @endforeach
            </h5>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-6">
            <h5 class="blue-text">Information</h5>
            <ul class="list-unstyled">
                <li>Email: @if(Auth::user()->hasAnyRole('Senior Staff|Administrator') || ($student->instructor() && $student->instructor()->user == Auth::user()))<a href="mailto:{{$student->user->email}}">{{$student->user->email}}</a>@else Private @endif</li>
                <li>Discord:
                    @if($student->user->hasDiscord())
                        @if(Auth::user()->hasAnyRole('Senior Staff|Administrator') || ($student->instructor() && $student->instructor()->user == Auth::user()))
                            {{$student->user->getDiscordUser()->username}}<span style="color: #797979;">#{{$student->user->getDiscordUser()->discriminator}}
                        @else
                            Private
                        @endif
                    @else
                        N/A
                    @endif
                </li>
                <li>Student since: {{$student->created_at->toFormattedDateString()}}, {{$student->created_at->diffForHumans()}}</li>
            </ul>
            <h5 class="blue-text">Actions</h5>
            <ul class="list-unstyled mt-2">
                @can('edit students')
                <li class="mb-2">
                    <a data-target="#deleteStudentModal" data-toggle="modal" style="text-decoration:none;"><span class="red-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Remove as student</span></a>
                </li>
                @endcan
            </ul>
            <h5 class="blue-text">Records</h5>
            <ul class="list-unstyled mt-2">
                <li class="mb-2">
                    <a href="#" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Training/OTS Sessions</span></a>
                </li>
                <li class="mb-2">
                    <a href="#" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Student History</span></a>
                </li>
                <li class="mb-2">
                    <a href="{{route('training.admin.instructing.students.records.training-notes', $student->user_id)}}" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Training Notes</span></a>
                </li>
            </ul>
        </div>
        <div class="col-md-6">
            <h5 class="blue-text">Instructor</h5>
            @if ($student->instructor())
                <a href="{{route('training.admin.instructing.instructors.view', $student->instructor()->instructor->user->id)}}" class="list-group-item list-group-item-action z-depth-1 rounded waves-effect">
                    <div class="d-flex flex-row w-100 align-items-center h-100 justify-content-between">
                        <div class="d-flex flex-row align-items-center">
                            <img src="{{$student->instructor()->instructor->user->avatar()}}" style="height: 30px; width:30px;margin-right: 15px; border-radius: 50%;">
                            <div class="d-flex flex-column align-items-center h-100">
                                <h5 class="mb-0">{{$student->instructor()->instructor->user->fullName('FLC')}}</h5>
                            </div>
                        </div>
                    </div>
                </a>
            @else
                This student is not assigned to an instructor.
                <ul class="list-unstyled mt-2">
                    @can('assign instructor to student')
                    <li class="mb-2">
                        <a data-target="#assignInstructorModal" data-toggle="modal" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Assign</span></a>
                    </li>
                    @endcan
                </ul>
            @endif
            <h5 class="blue-text mt-3">Application</h5>
            <div class="mt-3 card p-3 z-depth-1 lighten-5">
                @if ($student->application())
                    <h5>#{{$student->application()->reference_id}}</h5>
                    <p>Submitted {{$student->application()->created_at->diffForHumans()}} ({{$student->application()->created_at->toFormattedDateString()}})</p>
                    <h5>Statement</h5>
                    {{$student->application()->applicantStatementHtml()}}
                    @can('view applications')
                        <a href="{{route('training.admin.applications.view', $student->application()->reference_id)}}" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">View application</span></a>
                    @endcan
                @else
                    No application found.
                @endif
            </div>
        </div>
    </div>

    <!--Delete modal-->
    <div class="modal fade" id="deleteStudentModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Are you sure?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>This will mark the student as 'not current', virtually deleting them. This will also notify the person of their removal via email.</p>
                    <img src="https://tenor.com/view/bartsimpson-boot-simpsons-thesimpsons-homer-gif-9148667.gif" alt="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Dismiss</button>
                    <a href="{{route('training.admin.instructing.students.remove', $student->user->id)}}" role="button" class="btn btn-danger">Remove</a>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!--End delete modal-->

    <!--Start assign instructor modal-->
    <div class="modal fade" id="assignInstructorModal" role="dialog">
        <div class="modal-dialog modal-dialog-centered model-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign instructor to {{$student->user->fullName('F')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('training.admin.instructing.students.assign.instructor', $student->user->id)}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        @if($errors->assignInstructorErrors->any())
                            <div class="alert alert-danger">
                                <h4>There were errors</h4>
                                <ul class="pl-0 ml-0 list-unstyled">
                                    @foreach ($errors->assignInstructorErrors->all() as $error)
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
                                    <option value="{{$i->id}}">{{$i->user->fullName('FLC')}} - {{$i->staffPageTagline()}} - {{count($i->studentsAssigned)}} Students</option>
                                @endforeach
                            </select>
                        </div>
                        <p>The assigned Instructor will be notified of the assignment and the student's details. It will be their responsibility to establish contact with the student.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Dismiss</button>
                        <button class="btn btn-primary">Assign</button>
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

        if ($.urlParam('assignInstructorModal') == '1') {
            $("#assignInstructorModal").modal();
        }
    </script>
@endsection
