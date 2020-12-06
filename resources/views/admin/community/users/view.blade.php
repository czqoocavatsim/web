@extends('admin.community.layouts.main')
@section('community-content')
    <div class="container py-4">
        <a href="{{route('community.users.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Users</a>
        <div class="d-flex flex-row align-items-center">
            <img src="{{$user->avatar()}}" style="height: 50px; width:50px;margin-right: 15px; margin-bottom: 3px; border-radius: 50%;">
            <div>
                <h1 class="blue-text font-weight-bold mt-2 mb-1">{{$user->fullName('FL')}}</h1>
                <h5>{{$user->highestRole()->name}}</h5>
            </div>
        </div>
        @if ($user->fname != $user->display_fname || !$user->display_last_name || $user->display_cid_only)
            <small>Note: this user's display name does not match their CERT name.</small>
        @endif
        @if($user->id == 1 || $user->id == 2)
        <div class="alert bg-czqo-blue-light mt-2">
            This account is a system account used to identify automatic actions, or to serve as a placeholder user.
        </div>
        @endif
        <div class="row">
            <div class="col-md-6">
                <h5 class="blue-text">Basic Data</h5>
                <div class="list-group-item z-depth-1 rounded p-3">
                    <h5>Identity</h5>
                    <ul class="list-unstyled">
                        <li>CID: {{$user->id}}</li>
                        @can('view user data')
                        <li>CERT First Name: {{$user->fname}}</li>
                        <li>CERT Last Name: {{$user->lname}}</li>
                        @endcan
                        <li>Display Name: {{$user->fullName('FLC')}}</li>
                    </ul>
                    <h5>Rating & Division</h5>
                    <ul class="list-unstyled">
                        <li>Subdivision: {{$user->subdivision_code ? $user->subdivision_name.'('.$user->subdivision_code.')' : 'None'}}</li>
                        <li>Division: {{$user->division_name ?? 'N/A'}} ({{$user->division_code ?? 'N/A'}})</li>
                        <li>Region: {{$user->region_name ?? 'N/A'}} ({{$user->region_code ?? 'N/A'}})</li>
                        <li>Rating: {{$user->rating_GRP ?? 'N/A'}} ({{$user->rating_short ?? 'N/A'}})</li>
                    </ul>
                    @can('view user data')
                    <h5>Email</h5>
                    <a href="mailto:{{$user->email}}">{{$user->email}}</a>
                    @endcan
                </div>
                <h5 class="mt-3 blue-text">Roles and Permissions</h5>
                <div class="list-group-item z-depth-1 rounded p-3">
                    <h5>Roles</h5>
                    <ul class="list-unstyled">
                        @foreach($user->roles as $role)
                        <li>
                            {{$role->name}}
                            @can('edit user data')
                                <form style="display: inline;" action="{{route('community.users.remove.role', $user->id)}}" method="POST">
                                    @csrf
                                    {{ method_field('DELETE')}}
                                    <input type="hidden" name="role_id" value="{{$role->id}}">
                                    &nbsp;<button class="red-text btn btn-link m-0 p-0"><i class="fa fa-times"></i>   Remove</button>
                                </form>
                            @endcan
                        </li>
                        @endforeach
                    </ul>
                    @can('edit user data')
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
                    @endcan
                    <hr>
                    <h5>Permissions</h5>
                    <ul class="list-unstyled">
                        @foreach($user->permissions as $perm)
                        <li>
                            {{ucfirst($perm->name)}}
                            @if($user->can('edit user data'))
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
                    @can('edit user data')
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
                    @endcan
                </div>
            </div>
            <div class="col-md-6">
                <h5 class="blue-text">Avatar</h4>
                <div class="list-group-item z-depth-1 rounded p-3">
                    <div class="d-flex flex-row align-items-center">
                        <img src="{{$user->avatar()}}" style="height: 100px; width: 100px; border-radius: 50%;">
                        <div class="ml-4">
                            @can('edit user details')
                            <a href="{{route('community.users.reset.avatar', $user->id)}}" class="btn btn-sm bg-light">Reset</a>
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
                <h5 class="mt-3 blue-text">Discord Link</h5>
                <div class="list-group-item z-depth-1 rounded p-3">
                    @if($user->hasDiscord())
                    <h5><img style="border-radius:50%; height: 30px;" class="img-fluid" src="{{$user->getDiscordAvatar()}}" alt="">&nbsp;&nbsp;{{$user->getDiscordUser()->username}}#{{$user->getDiscordUser()->discriminator}}</h5>
                    <ul class="list-unstyled">
                        <li class="d-flex align-items-center">Member of the CZQO Discord: <i style="margin-left: 5px;font-size: 20px;" class="{{$user->memberOfCzqoGuild() ? 'fas fa-check-circle green-text' : 'fas fa-times-circle red-text'}}"></i></li>
                    </ul>
                    @else
                    This user does not have a linked Discord account.
                    @endif
                </div>
            </div>
        </div>
    </div>

@stop
