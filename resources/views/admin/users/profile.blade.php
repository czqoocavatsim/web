@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    <div class="container py-4">
        <a href="{{route('users.viewall')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Users</a>
        <h1 class="blue-text font-weight-bold mt-2"><img src="{{$user->avatar()}}" style="height: 50px; width:50px;margin-right: 15px; margin-bottom: 3px; border-radius: 50%;">{{$user->fullName('FL')}}</h1>
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
                        @if (Auth::user()->permissions == 4)
                        <li>CERT First Name: {{$user->fname}}</li>
                        <li>CERT Last Name: {{$user->lname}}</li>
                        @endif
                        <li>Display Name: {{$user->fullName('FLC')}}</li>
                    </ul>
                    <h5>Rating & Division</h5>
                    <ul class="list-unstyled">
                        <li>Subdivision: {{$user->subdivision_code ? $user->subdivision_name.'('.$user->subdivision_code.')' : 'None'}}</li>
                        <li>Division: {{$user->division_name}} ({{$user->division_code}})</li>
                        <li>Region: {{$user->region_name}} ({{$user->region_code}})</li>
                        <li>Rating: {{$user->rating_GRP}} ({{$user->rating_short}})</li>
                    </ul>
                    <h5>Email</h5>
                    <a href="mailto:{{$user->email}}">{{$user->email}}</a>
                </div>
            </div>
            <div class="col-md-6">
                <h4>Avatar</h4>
                <div class="card p-3">
                    <div class="d-flex flex-row align-items-center">
                        <img src="{{$user->avatar()}}" style="height: 100px; width: 100px; border-radius: 50%;">
                        <div class="ml-4">
                            <a href="#" data-toggle="modal" data-target="#changeAvatar" class="btn btn-sm bg-czqo-blue-light">Change</a>
                            @if(!$user->isAvatarDefault())
                            <form action="{{route('users.resetusersavatar')}}" method="POST">
                                @csrf
                                <input type="hidden" name="user_id" value="{{$user->id}}">
                                <input type="submit" class="btn btn-sm bg-czqo-blue-light" value="Reset">
                            </form>
                            @endif
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
                    <hr>
                    <h5>
                        <div class="d-flex flex-row justify-content-between align-items-center">
                            Bans
                            <a href="#" class="btn btn-sm bg-czqo-blue-light">Add Ban</a>
                        </div>
                    </h5>
                    @if (count($user->discordBans) < 1)
                    No bans found.
                    @else
                    <div class="list-group">
                        @foreach($user->discordBans as $ban)
                        <div class="list-group-item pr-0">
                            <div class="d-flex flex-row justify-content-between">
                                <b>From {{$ban->banStartPretty()}} to {{$ban->banEndPretty()}}</b>
                                <div class="justify-self-end">
                                    <a href="#" class="btn btn-sm bg-czqo-blue-light">View Reason</a>
                                    @if($ban->isCurrent())
                                    <a href="#" class="btn btn-sm btn-danger ">Remove Ban</a>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    @else
                    This user does not have a linked Discord account.
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addNoteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New note</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::open(['route' => ['users.createnote', $user->id]]) !!}
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Content</label>
                        {!! Form::textarea('content', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Confidential</label>
                        {!! Form::checkbox('confidential', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!--Change avatar modal-->
    <div class="modal fade" id="changeAvatar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
    </div>
    <!--End change avatar modal-->
    <script>
        function displayDeleteModal() {
            $('#deleteModal').modal('show')
        }
    </script>
@stop
