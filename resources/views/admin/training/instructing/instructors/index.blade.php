@extends('admin.training.layouts.main')
@section('training-content')
<h1 class="font-weight-bold blue-text pb-2">Instructors</h1>
<p class="my-2">Click on an instructor to view their current students, upcoming sessions, and their contact details.</p>
<div class="list-group">
    @foreach($instructors as $i)
        <a href="{{route('training.admin.instructing.instructors.view', $i->user_id)}}" class="list-group-item list-group-item-action">
            <div class="d-flex flex-row w-100 align-items-center h-100">
                <img src="{{$i->user->avatar()}}" style="height: 50px; width:50px;margin-right: 15px; margin-bottom: 3px; border-radius: 50%;">
                <div class="d-flex flex-column h-100">
                    <h5 class="font-weight-bold mb-1">{{$i->user->fullName('FLC')}}</h5>
                    <div>
                        <p class="my-0">{{$i->staffPageTagline()}}&nbsp;&nbsp;•&nbsp;&nbsp;{{count($i->studentsAssigned)}} Students Assigned</p>
                    </div>
                </div>
            </div>
        </a>
    @endforeach
</div>
<ul class="list-unstyled mt-3">
    <li class="mb-2">
        <a href="#" data-toggle="modal" data-target="#addInstructorModal" class="blue-text" style="font-size: 1.1em;"><i class="fas fa-plus"></i>&nbsp;&nbsp;Add an instructor</a>
    </li>
    @can('send announcements')
    <li>
        <a href="{{route('news.announcements.create')}}" class="blue-text" style="font-size: 1.1em;"><i class="fas fa-envelope"></i>&nbsp;&nbsp;Send message to all</a>
    </li>
    @endcan
</ul>

<!--Start add instructor modal-->
<div class="modal fade" id="addInstructorModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Instructor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('training.admin.instructing.instructors.add')}}" method="POST">
                @csrf
                <div class="modal-body">
                    @if($errors->addInstructorErrors->any())
                    <div class="alert alert-danger">
                        <h4>There were errors</h4>
                        <ul class="pl-0 ml-0 list-unstyled">
                            @foreach ($errors->addInstructorErrors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="form-group">
                        <label for="">Their CID</label>
                        <input required type="text" value="{{old('cid')}}" name="cid" maxlength="9" id="" class="form-control" placeholder="1300001">
                    </div>
                    <div class="form-group">
                        <label for="">Their staff email addresss</label>
                        <input required type="email" value="{{old('staff_email')}}" name="staff_email" class="form-control" placeholder="j.doe@ganderoceanic.com">
                    </div>
                    <p>Adding this person as an instructor will give them automatic access to all resources and administrative tools. Are you sure the information entered is correct?</p>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" required name="agree" id="agree">
                        <label class="custom-control-label" for="agree">I'm sure (required)</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Add">
                </div>
            </form>
        </div>
    </div>
</div>
<!--End add instructor modal-->

<script>
    $.urlParam = function(name){
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        return results[1] || 0;
    }

    if ($.urlParam('addInstructorModal') == '1') {
        $("#addInstructorModal").modal();
    }
</script>
@endsection
