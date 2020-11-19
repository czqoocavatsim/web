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
            <h5 class="font-weight-bold blue-text">Information</h5>
            <ul class="list-unstyled">
                <li>Email: <a href="mailto:{{$instructor->email()}}">{{$instructor->email()}}</a></li>
                <li>Discord: @if($instructor->user->hasDiscord()){{$instructor->user->getDiscordUser()->username}}<span style="color: #797979;">#{{$instructor->user->getDiscordUser()->discriminator}} @else N/A @endif</li>
                <li>Instructor since: {{$instructor->created_at->toFormattedDateString()}}, {{$instructor->created_at->diffForHumans()}}</li>
            </ul>
            <h5 class="font-weight-bold blue-text">Records</h5>
            <ul class="list-unstyled mt-2 mb-3">
                <li class="mb-2">
                    <a href="#" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">OTS sessions conducted</span></a>
                </li>
                <li class="mb-2">
                    <a href="#" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Training sessions conducted</span></a>
                </li>
            </ul>
            <h5 class="font-weight-bold blue-text">Actions</h5>
            <ul class="list-unstyled mt-2 mb-0">
                <li class="mb-2">
                    <a href="#" data-target="#editInstructorModal" data-toggle="modal" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Edit profile</span></a>
                </li>
                <li class="mb-2">
                    <a href="#" data-target="#deleteInstructorModal" data-toggle="modal" style="text-decoration:none;"><span class="red-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Remove as instructor</span></a>
                </li>
            </ul>
        </div>
        <div class="col-md-6">
            <h5 class="font-weight-bold blue-text">Assigned Students</h5>
            <div class="list-group">
                @if (count($instructor->studentsAssigned) < 1) None assigned. @endif
                @foreach($instructor->studentsAssigned as $student)
                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex flex-row w-100 align-items-center h-100 justify-content-between">
                            <div class="d-flex flex-row align-items-center">
                                <img src="{{$student->student->user->avatar()}}" style="height: 30px; width:30px;margin-right: 15px; border-radius: 50%;">
                                <div class="d-flex flex-column align-items-center h-100">
                                    <h5 class="mb-0">{{$student->student->user->fullName('FLC')}}</h5>
                                </div>
                            </div>
                            <i style="font-size: 1.6em;" class="blue-text fas fa-chevron-right fa-fw"></i>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!--Delete modal-->
    <div class="modal fade" id="deleteInstructorModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Are you sure?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>This will mark the Instructor as 'not current', virtually deleting them. This will also notify the person of their removal via email.</p>
                    <img src="https://tenor.com/view/bartsimpson-boot-simpsons-thesimpsons-homer-gif-9148667.gif" alt="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Dismiss</button>
                    <a href="#" role="button" class="btn btn-danger">Remove</a>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!--End delete modal-->
@endsection
