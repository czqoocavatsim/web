@extends('admin.training.layouts.main')
@section('training-content')
<h1 class="font-weight-bold blue-text pb-2">Students</h1>
<p class="my-2">Click on a student to see their training records, upcoming sessions, and their contact details.</p>
<p>
    <span class="p-3 rounded yellow lighten-5 d-block" style="width: 150px; text-align:center;">Long Term Student</span>
</p>
<table class="table dt table-hover table-bordered">
    <thead>
        <th>Name</th>
        <th>Student Since</th>
        <th>Action</th>
    </thead>
    <tbody>
        @foreach ($students as $s)
            <tr>
                <td>{{$s->user->fullName('FLC')}}</td>
                <td class="{{$s->created_at->diffInDays(Carbon\Carbon::now()) > 60 ? 'orange lighten-4' : ''}}">{{$s->created_at->toFormattedDateString()}} ({{$s->created_at->diffForHumans()}})</td>
                <td>
                    <a class="blue-text" href="{{route('training.admin.instructing.students.view', $s->user->id)}}">
                        <i class="fas fa-eye"></i>&nbsp;View Profile
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<ul class="list-unstyled mt-3">
    <li class="mb-2">
        <a href="#" data-toggle="modal" data-target="#addStudentModal" class="blue-text" style="font-size: 1.1em;"><i class="fas fa-plus"></i>&nbsp;&nbsp;Add a student</a>
    </li>
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
