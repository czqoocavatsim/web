@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    <div class="container py-4">
        <a href="{{route('community.users.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Users</a>
        <div class="d-flex flex-row align-items-center">
            <img src="{{$user->avatar()}}" style="height: 50px; width:50px;margin-right: 15px; margin-bottom: 3px; border-radius: 50%;">
            <div>
                <h1 class="blue-text font-weight-bold mt-2 mb-1">{{$user->fullName('FL')}}</h1>
                <h5>{{$user->highestRole()->name}}</h5>
            </div>
        </div>
        <hr>
        @if ($user->fname != $user->display_fname || !$user->display_last_name || $user->display_cid_only)
            <small>Note: this user's display name does not match their CERT name.</small>
        @endif
        @if($user->id == 1 || $user->id == 2)
        <div class="alert bg-czqo-blue-light">
            This account is a system account used to identify automatic actions, or to serve as a placeholder user.
        </div>
        @endif
        <div class="row">
            <div class="col-md-6">
                <h4>Basic Data</h4>
                <div class="card p-3">
                    <h5>Identity</h5>
                    <ul class="list-unstyled">
                        <li>CID: {{$user->id}}</li>
                        @can('view user details')
                        <li>CERT First Name: {{$user->fname}}</li>
                        <li>CERT Last Name: {{$user->lname}}</li>
                        @endcan
                        <li>Display Name: {{$user->fullName('FLC')}}</li>
                    </ul>
                    <h5>Rating & Division</h5>
                    <ul class="list-unstyled">
                        <li>Subdivision: {{$user->subdivision_code ? $user->subdivision_name.'('.$user->subdivision_code.')' : 'None'}}</li>
                        <li>Division: {{$user->division_name}} ({{$user->division_code}})</li>
                        <li>Region: {{$user->region_name}} ({{$user->region_code}})</li>
                        <li>Rating: {{$user->rating_GRP}} ({{$user->rating_short}})</li>
                    </ul>
                    @can('view user deatils')
                    <h5>Email</h5>
                    <a href="mailto:{{$user->email}}">{{$user->email}}</a>
                    @endcan
                </div>
                <h4 class="mt-3">Roles and Permissions</h4>
                <div class="card p-3">
                    <h5>Roles</h5>
                    <ul class="list-unstyled">
                        @foreach($user->roles as $role)
                        <li>
                            {{$role->name}}
                            @if($user->can('edit user details') && $role != $user->highestRole())
                                <form style="display: inline;" action="{{route('community.users.remove.role', $user->id)}}" method="POST">
                                    @csrf
                                    {{ method_field('DELETE')}}
                                    <input type="hidden" name="role_id" value="{{$role->id}}">
                                    &nbsp;<button class="red-text btn btn-link m-0 p-0"><i class="fa fa-times"></i>   Remove</button>
                                </form>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                    <h5>Assign Role</h5>
                    <form action="{{route('community.users.assign.role', $user->id)}}" method="POST">
                        @csrf
                        <div class="input-group mb-3">
                            <select class="form-control" name="role_id" id="">
                                <option value="" hidden>Select role...</option>
                                @foreach($assignableRoles as $role)
                                @if ($user->hasRole($role)) @continue @endif
                                <option value="{{$role->id}}">
                                    {{$role->name}}
                                </option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-link m-0 px-3 py-2 z-depth-0 waves-effect">Assign</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <h5>Permissions</h5>
                    <ul class="list-unstyled">
                        @foreach($user->permissions as $perm)
                        <li>
                            {{ucfirst($perm->name)}}
                            @if($user->can('edit user details'))
                                <form style="display: inline;" action="{{route('community.users.remove.permission', $user->id)}}" method="POST">
                                    @csrf
                                    {{ method_field('DELETE')}}
                                    <input type="hidden" name="permission_id" value="{{$perm->id}}">
                                    &nbsp;<button class="red-text btn btn-link m-0 p-0"><i class="fa fa-times"></i>   Remove</button>
                                </form>
                            @endif
                        </li>
                        @endforeach
                        @if(count($user->permissions) == 0)
                            <li>None assigned.</li>
                        @endif
                    </ul>
                    <h5>Assign Permission</h5>
                    <p>This should be used to give someone temporary access to a function without giving them unneeded access to other functions.</p>
                    <form action="{{route('community.users.assign.permission', $user->id)}}" method="POST">
                        @csrf
                        <div class="input-group mb-3">
                            <select class="form-control" name="permission_id" id="">
                                <option value="" hidden>Select permission...</option>
                                @foreach($assignablePermissions as $perm)
                                <option value="{{$perm->id}}">
                                    {{$perm->name}}
                                </option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-link m-0 px-3 py-2 z-depth-0 waves-effect">Assign</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <h4>Avatar</h4>
                <div class="card p-3">
                    <div class="d-flex flex-row align-items-center">
                        <img src="{{$user->avatar()}}" style="height: 100px; width: 100px; border-radius: 50%;">
                        <div class="ml-4">
                            @can('edit user details')
                            <a href="#" data-toggle="modal" data-target="#changeAvatar" class="btn btn-sm bg-czqo-blue-light">Change</a>
                            @if(!$user->isAvatarDefault())
                            {{-- <form action="{{route('users.resetusersavatar')}}" method="POST">
                                @csrf
                                <input type="hidden" name="user_id" value="{{$user->id}}">
                                <input type="submit" class="btn btn-sm bg-czqo-blue-light" value="Reset">
                            </form> --}}
                            @endif
                            @endcan
                            <p class="mt-2 pl-1">Avatar Mode:
                                @switch($user->avatar_mode)
                                @case(0)Default
                                @break
                                @case(1)Custom Image
                                @break
                                @case(2)Discord Avatar
                                @endswitch
                            </p>
                        </div>
                    </div>
                </div>
                <h4 class="mt-3">Discord Link</h4>
                <div class="card p-3">
                    @if($user->hasDiscord())
                    <h5><img style="border-radius:50%; height: 30px;" class="img-fluid" src="{{$user->getDiscordAvatar()}}" alt="">&nbsp;&nbsp;{{$user->getDiscordUser()->username}}#{{$user->getDiscordUser()->discriminator}}</h5>
                    <ul class="list-unstyled">
                        <li class="d-flex align-items-center">Member of the CZQO Discord: <i style="margin-left: 5px;font-size: 20px;" class="{{$user->memberOfCzqoGuild() ? 'fas fa-check-circle green-text' : 'fas fa-times-circle red-text'}}"></i></li>
                    </ul>
                    @can('edit user details')
                    <hr>
                    <h5>
                        <div class="d-flex flex-row justify-content-between align-items-center">
                            Bans
                            <a href="#" data-target="#createDiscordBanModal" data-toggle="modal" class="btn btn-sm bg-czqo-blue-light">Add Ban</a>
                        </div>
                    </h5>
                    @if (count($user->discordBans) < 1)
                    No bans found.
                    @else
                    <div class="list-group">
                        @foreach($user->discordBans as $ban)
                        <div class="list-group-item pr-0">
                            <div class="d-flex flex-row justify-content-between">
                                {{$ban->end_time->toDayDateTimeString()}}
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    @endcan
                    @else
                    This user does not have a linked Discord account.
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!--Change avatar modal-->
    {{-- <div class="modal fade" id="changeAvatar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Change {{$user->fullName('F')}}'s avatar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{route('users.changeusersavatar')}}" enctype="multipart/form-data" class="" id="">
                <div class="modal-body">
                    <p>Abuse of this function will result in disciplinary action. This function should only be used for adjusting staff members' avatars for the staff page, or at a users request.</p>
                    @csrf
                    <div class="input-group pb-3">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="file">
                            <input type="hidden" name="user_id" value="{{$user->id}}">
                            <label class="custom-file-label">Choose file</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Dismiss</button>
                    <input type="submit" class="btn btn-success" value="Upload">
                </div>
                </form>
            </div>
        </div>
    </div> --}}
    <!--End change avatar modal-->
    <!--Start create discord ban modal-->
    <div class="modal fade" id="createDiscordBanModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Create Discord ban for {{$user->fullName('F')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{route('discord.createban')}}" class="" id="">
                <input type="hidden" name="user_id" value="{{$user->id}}">
                <div class="modal-body">
                    <p>This will ban the user from the Discord.</p>
                    @csrf
                    <div class="form-group">
                        <label for="">Ban reason (in markdown)</label>
                        <textarea id="contentMD" name="reason" class="w-75"></textarea>
                        <script>
                            var simplemde = new SimpleMDE({ element: document.getElementById("contentMD"), toolbar: false });
                        </script>
                    </div>
                    <div class="form-group">
                        <label for="">Ban start time and date</label>
                        <input type="datetime" name="start_time" class="form-control flatpickr" id="ban_start" placeholder="Enter date">
                        <script>
                            flatpickr('#ban_start', {
                                enableTime: true,
                                noCalendar: false,
                                dateFormat: "Y-m-d H:i",
                                time_24hr: true,
                            });
                        </script>
                    </div>
                    <div class="form-group">
                        <label for="">Ban end time and date. Leave this blank for a permanent ban.</label>
                        <input type="datetime" name="end_time" class="form-control flatpickr" id="ban_end" placeholder="Enter date">
                        <script>
                            flatpickr('#ban_end', {
                                enableTime: true,
                                noCalendar: false,
                                dateFormat: "Y-m-d H:i",
                                time_24hr: true,
                            });
                        </script>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Dismiss</button>
                    <input type="submit" class="btn btn-danger" value="Ban">
                </div>
                </form>
            </div>
        </div>
    </div>
    <!--End create discord ban modal
    <script>
        function displayDeleteModal() {
            $('#deleteModal').modal('show')
        }
    </script>
@stop
