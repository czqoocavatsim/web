@extends('admin.settings.layouts.main')
@section('settings-content')
<h1 class="font-weight-bold blue-text pb-2">Staff</h1>
@if(count($staff) == 0) No staff members found @endif
@foreach($groups as $group)
    <a href="" data-target="#{{$group->slug}}Collapse" data-toggle="collapse">
        <h4 class="font-weight-bold blue-text">{{$group->name}}</h4>
    </a>
    <div id="{{$group->slug}}Collapse" class="list-group mb-3 show">
        @foreach($group->members as $member)
            <div class="list-group-item list-group-item-action">
                <div class="d-flex flex-row w-100 h-100">
                    @if(!$member->vacant())
                        <img src="{{$member->user->avatar()}}" style="height: 50px; width:50px;margin-right: 15px; margin-bottom: 3px; border-radius: 50%;">
                    @endif
                    <div class="h-100 w-100">
                        <div class="d-flex w-100 flex-row">
                            <h4 class="mr-auto">{{$member->position}}</h4>
                            <div>
                                <a href="{{route('community.users.view', $member->user->id)}}" class="blue-text"><i class="fas fa-user"></i>   View User</button></a>
                                &nbsp;
                                <a href="" data-target="#editStaffMember{{$member->shortform}}Modal" data-toggle="modal" class="blue-text"><i class="fas fa-pen"></i>   Edit</button></a>
                                &nbsp;
                                <a href="" data-target="#deleteStaffMember{{$member->shortform}}Modal" data-toggle="modal" class="red-text"><i class="fa fa-times"></i>   Delete</button></a>
                            </div>
                        </div>
                        <p class="mb-0">
                            @if (!$member->vacant())
                            {{$member->user->fullName('FLC')}}
                            @else
                                <i>Vacant</i>
                            @endif
                            <br>
                            {{$member->email}}
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endforeach
<ul class="list-unstyled mt-3">
    <li class="mb-2">
        <a href="#" class="blue-text" style="font-size: 1.1em;"><i class="fas fa-plus"></i>&nbsp;&nbsp;New staff member</a>
    </li>
    <li class="mb-2">
        <a href="#" class="blue-text" style="font-size: 1.1em;"><i class="fas fa-plus"></i>&nbsp;&nbsp;New group</a>
    </li>
</ul>



<!--Start edit staff member modal-->
@foreach($staff as $s)
<div class="modal fade" id="editStaffMember{{$s->shortform}}Modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit {{$s->position}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST">
                @csrf
                <div class="modal-body">
                    @if($errors->editRosterMemberErrors->any())
                    <div class="alert alert-danger">
                        <h4>There were errors</h4>
                        <ul class="pl-0 ml-0 list-unstyled">
                            @foreach ($errors->editStaffMemberErrors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="form-group">
                        <label for="">Position Name<sup class="red-text">*</sup></label>
                        <input type="text" value="{{old('position', $s->position)}}" name="position" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Description</label>
                        <input type="text" value="{{old('description', $s->description)}}" name="description" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Email Address<sup class="red-text">*</sup></label>
                        <input type="email" value="{{old('email', $s->email)}}" name="email" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Edit">
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
<!--End edit staff member modal-->

<!--Delete staff member modal-->
@foreach($staff as $s)
<div class="modal fade" id="deleteStaffMember{{$s->shortform}}Modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Are you sure?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>This will delete the '{{$member->position}}' staff member profile.<br>If this profile is in the Senior Staff grouping, please set it to Vacant rather than delete it, unless the position is being abolished.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Dismiss</button>
                <a href="#" role="button" class="btn btn-danger">Delete</a>
            </div>
            </form>
        </div>
    </div>
</div>
@endforeach
<!--End delete staff member modal-->
@endsection
