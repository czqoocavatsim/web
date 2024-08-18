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
                    <span class="mr-2 student-label-span">
                        <a href="{{route('training.admin.instructing.students.drop.label', [$student->user_id, $label->id])}}" title="Remove label">
                        {{$label->label()->labelHtml()}}
                        </a>
                    </span>
                @endforeach
                <a data-toggle="modal" data-target="#assignLabelModal" title="Add label">
                    <i style="font-size: 0.7em;" class="fas fa-plus text-muted"></i>
                </a>
            </h5>
        </div>
    </div>

    @if(!$student->current)
        <h5 class="blue-text mt-3">Records</h5>
        <ul class="list-unstyled mt-2">
            <li class="mb-2">
                <a href="#" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Training/OTS Sessions</span></a>
            </li>
            <li class="mb-2">
                <a href="{{route('training.admin.instructing.students.records.training-notes', $student->user_id)}}#history" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Student History</span></a>
            </li>
            <li class="mb-2">
                <a href="{{route('training.admin.instructing.students.records.training-notes', $student->user_id)}}" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Training Notes</span></a>
            </li>
        </ul>
    @else

    <div class="row mt-3">
        <div class="col-md-6">
            <h5 class="blue-text">Information</h5>
            <ul class="list-unstyled">
                <li>Email: @if(Auth::user()->hasAnyRole('Senior Staff|Administrator') || ($student->instructor() && $student->instructor()->instructor == Auth::user()->instructorProfile))<a href="mailto:{{$student->user->email}}">{{$student->user->email}}</a>@else Private @endif</li>
                <li>Discord:
                    @if($student->user->hasDiscord())
                        @if(Auth::user()->hasAnyRole('Senior Staff|Administrator') || ($student->instructor() && $student->instructor()->instructor == Auth::user()->instructorProfile))
                            {{$student->user->discord_username}}
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
                    <div class="list-group z-depth-1">
                        <a data-target="#deleteStudentModal" data-toggle="modal" class="list-group-item list-group-item-action red-text"><i class="fas fa-dumpster-fire mr-3"></i>Terminate User as Student</a>
                    </div>
                @endcan
                @if($student->user->rosterProfile->certification == "training")
                    <div class="list-group z-depth-1">
                        <a data-target="#certifyStudentModal" data-toggle="modal" class="list-group-item list-group-item-action green-text"><i class="fas fa-check mr-3"></i>Certify Controller</a>
                    </div>
                @endif
            </ul>
            <h5 class="blue-text">Records</h5>
            <ul class="list-unstyled mt-2">
                <li class="mb-2">
                    <a href="{{route('training.admin.instructing.students.records.training-notes', $student->user_id)}}#history" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Student History</span></a>
                </li>
                <li class="mb-2">
                    <a href="{{route('training.admin.instructing.students.records.training-notes', $student->user_id)}}#instructorRecommendations" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Instructor Recommendations</span></a>
                </li>
                <li class="mb-2">
                    <a href="{{route('training.admin.instructing.students.records.training-notes', $student->user_id)}}" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Training Notes</span></a>
                </li>
            </ul>
            <h5 class="blue-text">Availability</h5>
            @if (count($student->availability) > 0)
                @foreach($student->availability as $a)
                <div class="border p-2">
                    <p>Submitted on {{$a->created_at->toFormattedDateString()}}</p>
                    {{ $a->submissionHtml() }}
                </div>
                @endforeach
            @else
                <p>Availability not yet submitted by student.</p>
            @endif
        </div>
        <div class="col-md-6">
            <h5 class="blue-text">Instructor</h5>
            @if ($student->instructor())
                <a href="{{route('training.admin.instructing.instructors.view', $student->instructor()->instructor->user->id)}}" class="list-group-item list-group-item-action z-depth-1 rounded waves-effect">
                    <div class="d-flex flex-row w-100 align-items-center h-100 justify-content-between">
                        <div class="d-flex flex-row align-items-center">
                            <img src="{{$student->instructor()->instructor->user->avatar()}}" style="height: 30px; width:30px;margin-right: 15px; border-radius: 50%;">
                            <div class="d-flex flex-column align-items-center h-100">
                                <h5 class="mb-0">{{$student->instructor()->instructor->user->fullName('FL')}}</h5>
                            </div>
                        </div>
                    </div>
                </a>
                <ul class="list-unstyled mt-3">
                    @can('assign instructor to student')
                    <li class="mb-2">
                        <a data-target="#assignInstructorModal" data-toggle="modal" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Reassign student</span></a>
                    </li>
                    @endcan
                    @if(Auth::user()->instructorProfile == $student->instructor()->instructor)
                    <li class="mb-2">
                        <a data-target="#dropStudentModal" data-toggle="modal" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Drop student</span></a>
                    </li>
                    @endif
                </ul>
            @else
                This student is not assigned to an instructor.
                <ul class="list-unstyled mt-2">
                    @can('assign instructor to student')
                    <li class="mb-2">
                        <a data-target="#assignInstructorModal" data-toggle="modal" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Assign</span></a>
                    </li>
                    <p class="text-muted">Use this function to assign yourself.</p>
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
            @if($student->soloCertification())
            <h5 class="blue-text mt-3">Solo Certification Active</h5>
            <div class="mt-3 card p-3 z-depth-1">
                Expires on {{$student->soloCertification()->expires->toFormattedDateString()}}
            </div>
            @endif
            {{-- @if(Auth::user()->hasAnyRole('Senior Staff|Administrator') || ($student->instructor() && $student->instructor()->instructor == Auth::user()->instructorProfile))
            <h5 class="mt-4 blue-text">Requests</h5>
                <div class="list-group z-depth-1">
                    @if(!$student->soloCertification() && !$student->setAsReadyForAssessment())
                        <a href="{{route('training.admin.instructing.students.request.recommend.solocert', $student->user_id)}}" data-toggle="tooltip" title="This will notify assessors that you recommend this student be placed on a solo certification. They will notify you of the action taken." class="list-group-item list-group-item-action purple-text"><i class="fas fa-user mr-3"></i>Recommend for Solo Certification</a>
                    @else
                        <div class="list-group-item text-muted"><i>Already recommended for solo certification/solo certification in progress</i></div>
                    @endif
                </div>
            @endif --}}
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

    <!--certify modal-->
    <div class="modal fade" id="certifyStudentModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Certify Controller: {{$student->user->FullName('FLC')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>This will mark the students training as 'completed', close out all of their training, and add them to the controller roster.</p>
                    <p>Please do not do this unless the student is actually completed. This is REALLY REALLY painful to reverse</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Dismiss</button>
                    <a disabled href="{{route('training.admin.instructing.students.certify', $student->user->id)}}" role="button" class="btn btn-success">Certify Controller</a>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!--End certify modal-->

    <!--Drop modal-->
    <div class="modal fade" id="dropStudentModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Are you sure?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Try to find another instructor to reassign this student to first before dropping them. Once they are unassigned, they will go back to Ready for Pick-Up Status.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Dismiss</button>
                    <a href="{{route('training.admin.instructing.students.drop.instructor', $student->user->id)}}" role="button" class="btn btn-danger">Drop Student</a>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!--End drop modal-->

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

    <!--Start assign label modal-->
    <div class="modal fade" id="assignLabelModal" role="dialog">
        <div class="modal-dialog modal-dialog-centered model-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign status label to {{$student->user->fullName('F')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('training.admin.instructing.student.assign.label', $student->user->id)}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        @if($errors->assignLabelErrors->any())
                            <div class="alert alert-danger">
                                <h4>There were errors</h4>
                                <ul class="pl-0 ml-0 list-unstyled">
                                    @foreach ($errors->assignLabelErrors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="">Select status label</label>
                            <select name="label_id" class="form-control">
                                <option hidden>Select one..</option>
                                @foreach ($labels as $l)
                                    <option value="{{$l->id}}">{{$l->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Dismiss</button>
                        <button class="btn btn-primary">Assign</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @endif

    <script>
        $.urlParam = function(name){
            var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
            return results[1] || 0;
        }

        if ($.urlParam('assignInstructorModal') == '1') {
            $("#assignInstructorModal").modal();
        }

        if ($.urlParam('assignLabelModal') == '1') {
            $("#assignLabelModal").modal();
        }
    </script>

    <style>
        .student-label-span:hover:after {
            cursor: pointer;
            color: red;
            content: '\00d7';
            font-size: 15px;
        }
    </style>
@endsection
