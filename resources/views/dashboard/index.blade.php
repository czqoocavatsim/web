@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
<style>
    #topjumbo {
        background-image:url('https://cdn.discordapp.com/attachments/292398393375064066/538868929964277760/unknown.png');
        position: relative;
        color: black;
        background-position: center;
    }

    #jumbopattern {
        background-image: url('{{ asset('img/worn-dots.png') }}');
        background-repeat: repeat;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
</style>
<div id="topjumbo" class="jumbotron jumbotron-fluid bg-primary text-white">
    <div id=""></div>
    <div class="text-center">
        <h1 style="text-shadow: 0px 0px 0px;">{{Auth::user()->fname}} {{Auth::user()->lname}} {{Auth::user()->id}}</h1>
    </div>
</div>
<div class="container" style="margin-top: 20px;">
    <h2>Dashboard</h2>
    <br class="my-2">
    <div class="row">
        <div class="col">
            @if (Auth::user()->permissions >= 1)
                <h4 class="display-6">Controller Tools</h4>
                <div class="list-group">
                    <a href="http://oca.vnas.net" target="_new" class="list-group-item list-group-item-action">
                        <i class="fa fa-compass"></i>&nbsp;
                        Virtual Norweigan Tools
                    </a>
                    <a target="_new" href="https://docs.google.com/spreadsheets/d/1N2vGBlljpltchJ-7tn_FFufLKdts42eXeYDww1y03Hc/edit?usp=sharing" class="list-group-item list-group-item-action">
                        <i class="fa fa-file"></i>&nbsp;
                        Google Sheets Spreadsheet
                    </a>
                    <a target="_blank" href="https://docs.google.com/document/d/1AYVSvTnP-q_cdRS7dwHfLUaXamBzm-Jv71XWWqngi1Q/edit?usp=sharing" class="list-group-item list-group-item-action">
                        <i class="fa fa-file"></i>
                        Phraseology Sheet
                    </a>
                </div><br/>
            @endif
            <h4 class="display-6">Your Data</h4>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title">{{ Auth::user()->fname }}&nbsp;{{ Auth::user()->lname }}&nbsp;({{ Auth::user()->id }})</h5>
                            <h6 class="card-subtitle mb-2 text-muted">
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
                            </h6>
                            <ul>
                                <li>Division: {{ Auth::user()->division }}</li>
                                @if (Auth::user()->permissions == 0)
                                    <li>Status: Not Certified/Guest</li>
                                @elseif (Auth::user()->permissions == 1)
                                    <li>Status: Controller</li>
                                @elseif (Auth::user()->permissions == 2)
                                    <li>Status: Instructor</li>
                                @elseif (Auth::user()->permissions == 3)
                                    <li>Status: Staff</li>
                                @elseif (Auth::user()->permissions == 4)
                                    <li>Status: Executive</li>
                                @endif
                                @if(Auth::user()->staffProfile)
                                <li>Staff Role: {{Auth::user()->staffProfile->position}}</li>
                                @endif
                            </ul>
                        </div>
                        <div class="col">
                            <h5 class="card-title">Avatar</h5>
                            <div class="text-center">
                                <img src="{{Auth::user()->avatar}}" style="width: 125px; height: 125px; margin-bottom: 10px; border-radius: 50%;">
                            </div>
                            <br/>
                            <a role="button" data-toggle="modal" data-target="#changeAvatar" class="btn btn-sm btn-block btn-outline-primary"  href="#">Change</a>
                            @if (!Auth::user()->isAvatarDefault())
                                <a role="button" class="btn btn-sm btn-block btn-outline-danger"  href="{{route('users.resetavatar')}}">Reset</a>
                            @endif
                        </div>
                    </div>
                    <br/>
                </div>
                <div class="list-group-flush">
                    <a href="#" class="list-group-item list-group-item-action" data-target="#viewBio" data-toggle="modal"><i class="fa fa-address-card"></i>&nbsp;View Biography</a>
                    <a href="{{url('dashboard/data/')}}" class="list-group-item list-group-item-action"><i class="fa fa-file-download"></i>&nbsp;&nbsp;Download All Data</a>
                    <a href="{{url('/dashboard/data/remove')}}" class="list-group-item list-group-item-action"><i class="fa fa-user-slash"></i>&nbsp;Request Removal</a>
                    <a href="{{url('/dashboard/emailpref')}}" class="list-group-item list-group-item-action"><i class="fa fa-envelope"></i> Manage Email Preferences</a>
                </div>
            </div>
            <br/>
            @if (Auth::user()->permissions >= 3)
                <h4 class="display-6">Users</h4>
                <div class="card">
                    <div class="card-body">
                        <div class="input-group mb-3">
                            <input id="searchUsersQuery" type="text" class="form-control" placeholder="Search with VATSIM CID">
                            <div class="input-group-append">
                                <button class="btn btn-success" onclick="search()">Go</button>
                            </div>
                            <script>
                                function search() {
                                    window.location.href = "/dashboard/users/search/" + document.getElementById('searchUsersQuery').value;
                                }
                            </script>
                        </div>
                    </div>
                    <div class="list-group-flush">
                        <a href="{{url('/dashboard/users')}}" class="list-group-item list-group-item-action"><i class="fa fa-users-cog"></i>&nbsp;View All Users</a>
                    </div>
                </div>
            @endif
        </div>
        <div class="col">
            <h4>CZQO Certification & Training</h4>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Certification status</h5>
                    <h3 class="card-text">
                        @if ($certification == "certified")
                            <span class="badge badge-success">
                                <i class="fa fa-check"></i>&nbsp;
                                CZQO Certified
                            </span>
                        @elseif ($certification == "not_certified")
                            <span class="badge badge-danger">
                                <i class="fa fa-times"></i>&nbsp;
                                Not Certified
                            </span>
                        @elseif ($certification == "training")
                            <span class="badge badge-warning">
                                <i class="fa fa-book-open"></i>&nbsp;
                                In Training
                            </span>
                        @elseif ($certification == "instructor")
                            <span class="badge badge-info">
                                <i class="fa fa-chalkboard-teacher"></i>&nbsp;
                                CZQO Instructor
                            </span>
                        @else
                            <span class="badge badge-dark">
                                <i class="fa fa-question"></i>&nbsp;
                                Unknown
                            </span>
                        @endif
                        @if ($active == 0)
                            <span class="badge badge-danger">
                                <i class="fa fa-times"></i>&nbsp;
                                Inactive
                            </span>
                        @elseif ($active == 1)
                            <span class="badge badge-success">
                                <i class="fa fa-check"></i>&nbsp;
                                Active
                            </span>
                        @else

                        @endif
                    </h3>
                </div>
                <div class="list-group-flush">
                    @if (Auth::user()->permissions >= 2)
                    <a href="{{url('/dashboard/training')}}" target="" class="list-group-item list-group-item-action">
                        <i class="fa fa-graduation-cap"></i>
                        Training and Resources
                    </a>
                    @endif
                    <a target="" href="{{route('application.list')}}" class="list-group-item list-group-item-action">
                        <i class="fa fa-file-contract"></i>&nbsp;
                        Your Applications
                    </a>
                </div>
            </div>
            <br/>
            <h4 class="display-6">Tickets</h4>
            <div class="card">
                <div class="card-body">
                    @if (count($openTickets) < 1)
                        No open tickets.
                    @else
                        <div class="alert alert-info">
                            <h5 class="alert-heading">
                                @if (count($openTickets) == 1)
                                    1 open ticket
                                @else
                                    {{count($openTickets)}} open tickets
                                @endif
                            </h5>
                            <div class="list-group bg-info">
                                @foreach ($openTickets as $ticket)
                                    <a href="{{url('/dashboard/tickets/'.$ticket->ticket_id)}}" class="list-group-item list-group-item-action bg-info">{{$ticket->title}}<br/>
                                        <small>Last updated {{$ticket->updated_at}}</small>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                <div class="list-group-flush">
                    <a href="{{url('/dashboard/tickets')}}" class="list-group-item list-group-item-action"><i class="fa fa-comments"></i>&nbsp;View Your Tickets</a>
                    @if (Auth::user()->permissions >= 3)
                    <div class="list-group-item"><small><b>DIRECTORS</b></small></div>
                    <a href="{{url('/dashboard/tickets/staff')}}" class="list-group-item list-group-item-action"><i class="fa fa-inbox"></i>&nbsp;Ticket Inbox</a>
                    @endif
                </div>
            </div>
            <br/>
            @if (Auth::user()->permissions >= 2)
            <h4 class="display-6">Administration</h4>
            <div class="list-group">
                <div class="list-group-item"><small><b>INSTRUCTORS</b></small></div>
                <a href="{{url('/dashboard/training')}}" class="list-group-item list-group-item-action"><i class="fa fa-graduation-cap"></i>&nbsp;Controller Training</a>
                @if (Auth::user()->permissions >= 3)
                    <a href="{{url('/dashboard/training/applications')}}" class="list-group-item list-group-item-action"><i class="fa fa-file-contract"></i>&nbsp;Controller Applications</a>
                @endif
                @if (Auth::user()->permissions >= 3)
                    <div class="list-group-item"><small><b>DIRECTORS</b></small></div>
                    <a href="{{url('/dashboard/roster')}}" class="list-group-item list-group-item-action"><i class="fa fa-users"></i>&nbsp;Controller Roster</a>
                    <a href="{{url('/dashboard/news')}}" class="list-group-item list-group-item-action"><i class="fa fa-newspaper"></i>&nbsp;News</a>
                @endif
                @if (Auth::user()->permissions >= 4)
                    <div class="list-group-item"><small><b>EXECUTIVE</b></small></div>
                    <a href="{{url('/dashboard/auditlog')}}" class="list-group-item list-group-item-action"><i class="fa fa-list-ul"></i>&nbsp;Audit Log</a>
                    <a href="{{route('staff.edit')}}" class="list-group-item list-group-item-action"><i class="fa fa-users"></i>&nbsp;Staff List</a>
                    <a href="{{url('/dashboard/coresettings')}}" class="list-group-item list-group-item-action"><i class="fa fa-cog"></i>&nbsp;Core Settings</a>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="betaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Welcome to the CZQO Dashboard</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <h4>Please note that this is in <b>beta stage</b>.</h4>
            <p>Please report all bugs either via the feedback form or via email to l.downes(at)vatpac.org</p>
            <p>Thanks!</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Okay!</button>
        </div>
        </div>
    </div>
</div>

<div class="modal fade" id="changeAvatar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Change avatar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Please ensure your avatar complies with the VATSIM Code of Conduct. This avatar will be visible to staff members, and if you're a staff member yourself, on the staff page.</p>
                <form method="post" action="{{route('users.changeavatar')}}" enctype="multipart/form-data" class="" id="">
                    @csrf
                    <input type="file" name="file" class="form-control-file">
                    <br/>
                    <input type="submit" class="btn btn-success" value="Upload">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Dismiss</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewBio" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">View your biography</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if (Auth::user()->bio)
                    {{Auth::user()->bio}}
                @else
                    You have no biography.
                @endif
            </div>
            <div class="modal-footer">
                <a href="{{route('me.editbioindex')}}" class="btn btn-primary" role="button">Edit Biography</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Dismiss</button>
            </div>
        </div>
    </div>
</div>

<script>
       //$('#changeAvatar').modal('show')
    </script>
@stop
