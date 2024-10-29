@extends('admin.training.layouts.main')
@section('training-content')
<h1 class="blue-text pb-2">Students</h1>
<p class="my-2 mb-4">Click on a student to see their training records, upcoming sessions, and their contact details.</p>
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="currentStudents-tab" data-toggle="tab" href="#currentStudents" role="tab">Current Students</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="inactiveStudents-tab" data-toggle="tab" href="#inactiveStudents" role="tab">Past Students</a>
    </li>
</ul>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active pt-3" id="currentStudents" role="tabpanel">
        <div class="list-group z-depth-1 rounded">
            @foreach($students as $s)
                <a href="{{route('training.admin.instructing.students.view', $s->user_id)}}" class="list-group-item list-group-item-action waves-effect">
                    <div class="d-flex flex-row w-100 align-items-center h-100">
                        <img src="{{$s->user->avatar()}}" style="height: 50px; width:50px;margin-right: 15px; margin-bottom: 3px; border-radius: 50%;">
                        <div class="d-flex flex-column h-100">
                            <h5 class="mb-1">{{$s->created_at->format('d M')}} - {{$s->user->fullName('FLC')}}</h5>
                            <h5>
                                @foreach($s->labels as $label)
                                    <span class="mr-2">
                                        {{$label->label()->labelHtml()}}
                                    </span>
                                @endforeach
                            </h5>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    <div class="tab-pane fade pt-3" id="inactiveStudents" role="tabpanel" aria-labelledby="profile-tab">
        <table class="table dt table-hover table-bordered">
            <thead>
                <th>Name</th>
                <th>Action</th>
            </thead>
            <tbody>
                @foreach ($pastStudents as $s)
                    <tr>
                        <td>{{$s->user->fullName('FLC')}}</td>
                        <td>
                            <a class="blue-text" href="{{route('training.admin.instructing.students.view', $s->user->id)}}">
                                <i class="fas fa-eye"></i>&nbsp;View Profile
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<ul class="list-unstyled mt-5">
    @can('edit students')
    <li class="mb-2">
        <a href="#" data-toggle="modal" data-target="#addStudentModal" class="blue-text" style="font-size: 1.1em;"><i class="fas fa-plus"></i>&nbsp;&nbsp;Add a student</a>
    </li>
    @endcan
    @can('send announcements')
    <li>
        <a href="{{route('news.announcements.create')}}" class="blue-text" style="font-size: 1.1em;"><i class="fas fa-envelope"></i>&nbsp;&nbsp;Send message to all</a>
    </li>
    @endcan
</ul>

<!--Start add student modal-->
<div class="modal fade" id="addStudentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Student</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('training.admin.instructing.students.add')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>This method should be used for students seeking additional training or where an application is not required. It is preferred that if an application is required that student status is set through approving their application.</p>
                    @if($errors->addStudentErrors->any())
                    <div class="alert alert-danger">
                        <h4>There were errors</h4>
                        <ul class="pl-0 ml-0 list-unstyled">
                            @foreach ($errors->addStudentErrors->all() as $error)
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
                        <label for="">Reason for Manual Add</label>
                        <textarea required type="text" value="{{old('reason')}}" name="reason" maxlength="400" id="" class="form-control" placeholder="Please provide the reason for a manual add, as opposed for doing so via User Application."></textarea>
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
    $(document).ready(function () {
        $('.table.dt').DataTable();
    })

    $.urlParam = function(name){
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        return results[1] || 0;
    }

    if ($.urlParam('addStudentModal') == '1') {
        $("#addStudentModal").modal();
    }
</script>
@endsection
