@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
<style>
    #topjumbo {
        /*background-image:url('https://cdn.discordapp.com/attachments/292398393375064066/538868929964277760/unknown.png');*/
        position: relative;
        color: black;
        background-position: center;
        height: 50px;
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
<div id="topjumbo" class="jumbotron jumbotron-fluid text-white mb-0" style="background-color: #1B7BC8 !important;">
    <div id=""></div>
    <div class="text-center">
        <h1 style="text-shadow: 0px 0px 0px;">
            <?php
            function randomArrayVar($array)
            {
                if (!is_array($array)){
                return $array;
                }
                return $array[array_rand($array)];
            }

            //list of grettings as arary

            $greeting= array(
                "aloha"=>"Aloha",
                "ahoy"=>"Ahoy",
                "bonjour"=>"Bonjour",
                "gday"=>"G'day",
                "hello"=>"Hello",
                "hey"=>"Hey",
                "hi"=>"Hi",
                "hola"=>"Hola",
                "howdy"=>"Howdy",
                "salutations"=>"Salutations",
                "sup"=>"Sup",
                "whatsup"=>"What's up",
                "yo"=>"Yo");

            //echo greeting
            echo (randomArrayVar($greeting));
            ?>
            {{Auth::user()->fullName('F')}}!
        </h1>
    </div>
</div>
@if (Auth::user()->userSinceInDays() < 7)
<div class="jumbotron jumbotron-fluid m-0 p-2" style="" >
    <h3 class="text-center m-0 p-0">
        <a data-step="10" data-intro="And there it is! Enjoy your time here. If you have any other enquires, please make a ticket to the Webmaster and she will be happy to answer any questions you have. :)" href="javascript:void(0);" onclick="javascript:introJs().setOption('showProgress', true).start();">I need help!</a>
    </h3>
</div>
@endif
<div class="container" style="margin-top: 20px;">
    <h2 data-step="1" data-intro="Welcome to the CZQO Dashboard! This is your central hub for all things Gander. Here you can interact with our FIR, and manage your account.">Dashboard</h2>
    @if (Auth::user()->rating_id >= 5)
    <blockquote class="blockquote bq-primary">
        <p class="bq-title">Cross the Pond Eastbound 2019</p>
        <p>Are you available to control for CTP Eastbound 2019? Fill out the CZQO sign-up form to control either Gander or Shanwick Oceanic for the event!
            <br/>
            <a href="#" role="button" class="btn btn-primary" data-toggle="modal" data-target="#ctpSignUpModal">Sign Up</a>
        </p>
    </blockquote>
    @endif
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
            <div data-step="2" data-intro="Here is where you manage and view the data we store on you and your CZQO profile." class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col" data-step="3" data-intro="Here is an overview of your profile, including your CZQO roles. You can change the way your name is displayed by clicking on your name, at the top of the panel. (CoC A4(b))">
                            <h5 class="card-title">
                                <a href="" data-toggle="modal" data-target="#changeDisplayNameModal" class="text-dark">
                                    {{ Auth::user()->fullName('FLC') }}
                                </a>
                            </h5>
                            <h6 class="card-subtitle mb-2 text-muted">
                                {{Auth::user()->rating_GRP}} ({{Auth::user()->rating_short}})
                            </h6>
                            <ul>
                                <li>Region: {{ Auth::user()->region_name }}</li>
                                <li>Division: {{ Auth::user()->division_name }}</li>
                                @if (Auth::user()->subdivision_name)
                                <li>vACC/ARTCC: {{ Auth::user()->subdivision_name }}</li>
                                @endif
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
                        <div data-step="4" data-intro="You can change your avatar here. Your avatar is available when people view your account. This will likely only be staff members, unless you sign up for an event or similar activity." class="col">
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
                <div class="list-group-flush" data-step="5" data-intro="This is where you can manage your biography, and manage your data preferences.">
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
                            <input id="searchUsersQuery" type="text" class="form-control" placeholder="Search for a user">
                        </div>
                        <p id="userSearchResultsStatus"></p>
                        <div class="list-group" id="userSearchResultsList">
                        </div>
                        <script type="text/javascript">
                            $('#searchUsersQuery').on('keydown', function(){
                               value = $(this).val();
                               if (value === '') {
                                   return;
                               }
                                $("#userSearchResultsList").html('');
                               $.ajax({
                                   type: 'post',
                                   headers: {
                                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                   },
                                   url: '{{route('users.search.ajax')}}',
                                   data: {'query': value},
                                   success: function(data) {
                                       console.log(data);
                                       if (data === 'n/a') {
                                           $('#userSearchResultsStatus').text('No results found! Try searching something else.');
                                           return;
                                       }
                                       $('#userSearchResultsStatus').text(data.length + ' results found.');
                                       for (i = 0; i < data.length; i++) {
                                           user = data[i];
                                           row = `<a class='list-group-item list-group-item-action' href='/dashboard/users/${user.id}'>${user.display_fname} ${user.lname} ${user.id}</a>`;
                                           $("#userSearchResultsList").append(row)
                                       }
                                   },
                                   error: function(error) {
                                       console.log(error);

                                   }
                               })
                            });
                        </script>
                    </div>
                    <div class="list-group-flush">
                        <a href="{{url('/dashboard/users')}}" class="list-group-item list-group-item-action"><i class="fa fa-users-cog"></i>&nbsp;View All Users</a>
                    </div>
                </div>
                <br/>
                <h4 class="display-6">Network</h4>
                <div class="card">
                    <div class="list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action"><i class="fa fa-chart-line"></i>&nbsp;Network Activity</a>
                        <a href="{{route('network.positions.index')}}" class="list-group-item list-group-item-action"><i class="fa fa-broadcast-tower"></i>&nbsp;Positions</a>
                        <a href="#" class="list-group-item list-group-item-action"><i class="fa fa-flag"></i>&nbsp;Network Log</a>

                    </div>
                </div>
            @endif
        </div>
        <div class="col">
            <h4>CZQO Certification & Training</h4>
            <div class="card" data-step="6" data-intro="Here you can view your certification status within CZQO.">
                <div class="card-body">
                    <h5 class="card-title">Certification status</h5>
                    <h5 class="card-text">
                        @if ($certification == "certified")
                            <h3>
                            <span class="badge  badge-success">
                                <i class="fa fa-check"></i>&nbsp;
                                CZQO Certified
                            </span>
                            </h3>
                        @elseif ($certification == "not_certified")
                            <h3>
                            <span class="badge badge-danger">
                                <i class="fa fa-times"></i>&nbsp;
                                Not Certified
                            </span>
                            </h3>
                        @elseif ($certification == "training")
                            <h3>
                            <span class="badge badge-warning">
                                <i class="fa fa-book-open"></i>&nbsp;
                                In Training
                            </span>
                            </h3>
                        @elseif ($certification == "instructor")
                            <h3>
                            <span class="badge badge-info">
                                <i class="fa fa-chalkboard-teacher"></i>&nbsp;
                                CZQO Instructor
                            </span>
                            </h3>
                        @else
                            <h3>
                            <span class="badge badge-dark">
                                <i class="fa fa-question"></i>&nbsp;
                                Unknown
                            </span>
                            </h3>
                        @endif
                        @if ($active == 0)
                            <h3>
                            <span class="badge badge-danger">
                                <i class="fa fa-times"></i>&nbsp;
                                Inactive
                            </span>
                            </h3>
                        @elseif ($active == 1)
                            <h3>
                            <span class="badge badge-success">
                                <i class="fa fa-check"></i>&nbsp;
                                Active
                            </span>
                            </h3>
                        @else

                        @endif
                    </h5>
                </div>
                <div class="list-group-flush" >
                    @if (Auth::user()->permissions >= 2)
                    <a data-step="7" data-intro="Access CZQO training resources here." href="{{url('/dashboard/training')}}" target="" class="list-group-item list-group-item-action">
                        <i class="fa fa-graduation-cap"></i>
                        Training and Resources
                    </a>
                    @endif
                    <a data-step="8" data-intro="View your CZQO controller applications here." target="" href="{{route('application.list')}}" class="list-group-item list-group-item-action">
                        <i class="fa fa-file-contract"></i>&nbsp;
                        Your Applications
                    </a>
                </div>
            </div>
            <br/>
            <h4 class="display-6">Tickets</h4>
            <div data-step="9" data-intro="If you have any enquires or issues for the staff, feel free to make a ticket via the ticketing system." class="card">
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
    <br/>
    <a href="javascript:void(0);" onclick="javascript:introJs().setOption('showProgress', true).start();">View the tutorial</a>
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

<div class="modal fade" id="changeDisplayNameModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Change your display name</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{route('users.changedisplayname')}}">
                    @csrf
                    <div class="form-group">
                        <label>Display first name</label>
                        <input type="text" class="form-control" value="{{Auth::user()->display_fname}}" name="display_fname" id="input_display_fname">
                        <br/>
                        <a class="btn btn-outline-light btn-sm" role="button" onclick="resetToCertFirstName()"><span style="color: #000">Reset to CERT first name</span></a>
                        <script>
                            function resetToCertFirstName() {
                                $("#input_display_fname").val("{{Auth::user()->fname}}")
                            }
                        </script>
                    </div>
                    <div class="form-group">
                        <label>Format</label>
                        <select name="format" class="custom-select">
                            <option value="showall">Show first name, last name, and CID (e.g. {{Auth::user()->display_fname}} {{Auth::user()->lname}} {{Auth::id()}})</option>
                            <option value="showfirstcid">Show first name and CID (e.g. {{Auth::user()->display_fname}} {{Auth::id()}})</option>
                            <option value="showcid">Show CID only (e.g. {{Auth::id()}})</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-outline-success" value="Save Changes">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Dismiss</button>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="ctpSignUpModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Cross the Pond October 2019 Sign-up</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('ctp.signup.post')}}" method="POST">
                @csrf
            <div class="modal-body">
                <p>
                    If you wish to control Gander/Shanwick Oceanic for <a href="https://ctp.vatsim.net/">Cross the Pond Eastbound 2019</a>, you can sign up here!
                </p>
                <h5 class="font-weight-bold">Requirements</h5>
                <ul class="ml-3" style="list-style: disc">
                    <li>Be a C1 rated controller or above</li>
                    <li>A suitable amount of hours as a C1 (50+)</li>
                    <li>You <b>do not</b> have to be a Gander or Shanwick certified controller</li>
                </ul>
                <h5 class="font-weight-bold">Availability</h5>
                <p>Are you available to control CTP Eastbound on 26 October?</p>
                <select name="availability" id="" class="form-control">
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                    <option value="standby">As a standby controller</option>
                </select>
                <h5 class="mt-2 font-weight-bold">Times</h5>
                <p>What times are you available (in zulu)? If left blank, we will assume you are available for the entire event.</p>
                <input maxlength="191" name="times" class="form-control" type="text" placeholder="e.g. Between 1100z and 2000z">
                <p class="mt-2">By pressing the "Confirm" button below, you agree to be available to control for the periods you have typed above. If you are no longer available, please contact the FIR Chief ASAP.</p>
            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-primary" value="Confirm">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Dismiss</button>
            </div>
            </form>
        </div>
    </div>

</div>

<script>
    //$('#ctpSignUpModal').modal('show')
    </script>
@stop
