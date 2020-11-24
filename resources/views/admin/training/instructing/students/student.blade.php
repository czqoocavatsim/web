@extends('admin.training.layouts.main')
@section('title', "Student {$student->user->fullName('FLC')} - ")
@section('training-content')
    <a href="{{route('training.admin.instructing.students')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Students</a>
    <div class="d-flex flex-row align-items-center">
        <img src="{{$student->user->avatar()}}" style="height: 50px; width:50px;margin-right: 15px; margin-bottom: 3px; border-radius: 50%;">
        <div>
            <h2 class="blue-text font-weight-bold mt-2 mb-1">{{$student->user->fullName('FLC')}}</h2>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-6">
            <h5 class="font-weight-bold blue-text">Information</h5>
            <ul class="list-unstyled">
                <li>Email: <a href="mailto:{{$student->user->email}}">{{$student->user->email}}</a></li>
                <li>Discord: @if($student->user->hasDiscord()){{$student->user->getDiscordUser()->username}}<span style="color: #797979;">#{{$student->user->getDiscordUser()->discriminator}} @else N/A @endif</li>
                <li>Student since: {{$student->created_at->toFormattedDateString()}}, {{$student->created_at->diffForHumans()}}</li>
            </ul>
            <h5 class="font-weight-bold blue-text">Actions</h5>
            <ul class="list-unstyled mt-2 mb-0">
                <li class="mb-2">
                    <a href="#" data-target="#deleteStudentModal" data-toggle="modal" style="text-decoration:none;"><span class="red-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Remove as student</span></a>
                </li>
            </ul>
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



    <script>
        $.urlParam = function(name){
            var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
            return results[1] || 0;
        }

        if ($.urlParam('editInstructorModal') == '1') {
            $("#editInstructorModal").modal();
        }
    </script>
@endsection
