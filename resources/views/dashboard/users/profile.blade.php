@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    <div class="container" style="margin-top: 20px;">
        <a href="{{url('dashboard/users/')}}"><i class="fa fa-left-arrow"></i>Back To All Users</a>
        <h2>View User {{ $user->id }}</h2>
        <h5>{{ $user->fname }}&nbsp;{{ $user->lname }}</h5>
        @if ($user->id == 1)
            <div class="alert alert-info">
                <h4 class="alert-heading">System User</h4>
                <p>
                    This is the System User account which is used for automatic actions that require a user account recorded, and as the target account on all actions that do not involve another user.
                </p>
            </div>
        @endif
        <br/>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Attribute</th>
                <th scope="col">Value</th>
            </tr>
            </thead>
            <tbody>
            @if (Auth::user()->permissions > 3)
                <tr>
                    <th scope="row">Email</th>
                    <td>
                        {{ $user->email }}
                    </td>
                </tr>
            @endif
            <tr>
                <th scope="row">Rating</th>
                <td>
                    @switch (Auth::user()->rating)
                        @case('INA')
                        Inactive (INA)
                        @break
                        @case('OBS')
                        Pilot/Observer (OBS)
                        @break
                        @case('S1')
                        Ground Controller (S1)
                        @break
                        @case('S2')
                        Tower Controller (S2)
                        @break
                        @case('S3')
                        TMA Controller (S3)
                        @break
                        @case('C1')
                        Enroute Controller (C1)
                        @break
                        @case('C3')
                        Senior Controller (C3)
                        @break
                        @case('I1')
                        Instructor (I1)
                        @break
                        @case('I3')
                        Senior Instructor (I3)
                        @break
                        @case('SUP')
                        Supervisor (SUP)
                        @break
                        @case('ADM')
                        Administrator (ADM)
                        @break
                    @endswitch
                </td>
            </tr>
            <tr>
                <th scope="row">Division</th>
                <td>{{ $user->division }}</td>
            </tr>
            <tr>
                <th scope="row">Permissions</th>
                <td>
                    @if ($user->permissions == 0)
                        Guest (0)
                    @elseif ($user->permissions == 1)
                        Controller (1)
                    @elseif ($user->permissions == 2)
                        Instructor/Mentor (2)
                    @elseif ($user->permissions == 3)
                        Director (Non-Executive) (3)
                    @elseif ($user->permissions == 4)
                        Director (Executive) (4)
                    @else
                        Not Found
                    @endif
                </td>
            </tr>
            <tr>
                <th scope="row">Staff Member</th>
                <td>
                    ???
                </td>
            </tr>
            <tr>
                <th scope="row">Instructor</th>
                <td>
                    @if ($user->instructorProfile)
                    <a href="#">
                        {{$user->instructorProfile->qualification}}
                    </a>
                    @else
                    No
                    @endif
                </td>
            </tr>
            <tr>
                <th scope="row">Student</th>
                <td>
                    @if ($user->studentProfile)
                    <a href="{{route('training.students.view', $user->studentProfile->id)}}">
                        @if ($user->studentProfile->status == 0)
                            Open
                        @elseif ($user->studentProfile->status == 3)
                            On Hold
                        @elseif ($user->studentProfile->status == 1)
                            Completed
                        @else 
                            Closed
                        @endif
                    </a>
                    @else
                    No
                    @endif
                </td>
            </tr>
            </tbody>
        </table>
        <br/>
        @if (Auth::user()->permissions >= 3)
            <h4>Applications</h4>
            @if (count($user->applications) < 1)
                <p>None found</p>
            @else
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Status</th>
                        <th scope="col">View</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($user->applications as $application)
                        <tr>
                            <th scope="row">#{{$application->application_id}}</th>
                            <td>
                                @if ($application->status == 0)
                                    <p class="mb-1 text-info">
                                        <i class="fa fa-clock"></i>&nbsp;
                                        Pending
                                    </p>
                                @elseif ($application->status == 2)
                                    <p class="mb-1 text-success">
                                        <i class="fa fa-check"></i>&nbsp;
                                        Accepted
                                    </p>
                                @elseif ($application->status == 1)
                                    <p class="mb-1 text-danger">
                                        <i class="fa fa-times"></i>&nbsp;
                                        Denied
                                    </p>
                                @else
                                    <p class="mb-1 text-dark">
                                        <i class="fa fa-times"></i>&nbsp;
                                        Withdrawn
                                    </p>
                                @endif
                            </td>
                            <td>
                                <a href="{{url('/dashboard/training/applications/'.$application->application_id)}}"><i class="fa fa-eye"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
            <br/>
            <h4>Staff Notes</h4>
            @if (count($user->notes) < 1)
                <p>No notes found</p>
            @else
                <div class="list-group">
                    @foreach ($user->notes as $note)
                        @if ($note->confidential == 1)
                            @if (Auth::user()->permissions == 4)
                                <div class="list-group-item">
                                    <h5>{{$note->timestamp}} by {{\App\User::find($note->author)->fname}} {{\App\User::find($note->author)->lname}} {{\App\User::find($note->author)->id}}</h5>
                                    <div class="badge badge-danger">Confidential</div>
                                    <p style="word-break: break-all;">
                                        {{$note->content}}
                                    </p>
                                    <a href="{{url('/dashboard/users/'.$user->id.'/note/'.$note->id.'/delete')}}">Delete note</a>
                                </div>
                            @endif
                        @else
                            <div class="list-group-item">
                                <h5>{{$note->timestamp}} by {{\App\User::find($note->author)->fname}} {{\App\User::find($note->author)->lname}} {{\App\User::find($note->author)->id}}</h5>
                                <p style="word-break: break-all;">
                                    {{$note->content}}
                                </p>
                                <a href="{{url('/dashboard/users/'.$user->id.'/note/'.$note->id.'/delete')}}">Delete note</a>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
            <br/>
            <a href="#" data-toggle="modal" data-target="#addNoteModal" role="button" class="btn btn-sm btn-outline-primary">Add Note</a>
            <br/>
        @endif
        <br/>
        @if (Auth::user()->permissions >= 4)
            <h4>Audit Log</h4>
            @if (count($auditLog) < 1)
                <p>No logs</p>
            @else
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">Time</th>
                        <th scope="col">User Responsible</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($auditLog as $entry)
                        <tr>
                            <th scope="row">{{$entry->time}}</th>
                            <td>
                                {{App\User::find($entry->user_id)->fname}} {{App\User::find($entry->user_id)->lname}} {{App\User::find($entry->user_id)->id}}
                            </td>
                            <td>
                                {{$entry->action}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        @endif
        <br/>
        @if (Auth::user()->permissions == 4 && $user->id != 1)
            <a href="javascript:displayDeleteModal()" role="button" class="btn btn-danger">Delete User</a>
            <a href="{{url('/dashboard/users/' . $user->id . '/edit')}}" role="button" class="btn btn-info">Edit User</a>
        @endif
    </div>
    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Delete User {{ $user->id }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <b>ARE YOU SURE YOU WISH TO DO THIS?</b>
                            <p>The following consequences will occur:
                            <ul>
                                <li>The user's data will be <i>removed permanently.</i></li>
                                <li>Training records could be corrupted.</li>
                                <li>Their roster status will be </i>removed.</i></li>
                            </ul>
                        </div>
                        <div class="col">
                            <img src="https://media1.tenor.com/images/9ed3b339bbe196589360e93c8ebf90f0/tenor.gif?itemid=9148667">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="window.location.href = '/dashboard/users/{{ $user->id }}/delete'" class="btn btn-outline-danger" data-dismiss="modal">Delete</button>
                    <button type="button" class="btn btn-success" >Exit</button>
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
    <script>
        function displayDeleteModal() {
            $('#deleteModal').modal('show')
        }
    </script>
@stop