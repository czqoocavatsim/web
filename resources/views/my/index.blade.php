@extends('layouts.master')
@section('title', 'myCZQO - ')
@section('content')
<div class="jarallax card card-image rounded-0"  data-jarallax data-speed="0.2">
    <img class="jarallax-img" src="{{$bannerImg->path}}" alt="">
    <div class="text-white text-left rgba-stylish-strong py-3 pt-5 px-4">
        <div class="container">
            <div class="py-5">
                <h1 class="h1 my-4 py-2 font-weight-bold" style="font-size: 3em;">
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
                        "guten_tag"=>"Guten Tag",
                        "grüß_dich"=>"Grüß Dich");

                    //echo greeting
                    echo (randomArrayVar($greeting));
                    ?>
                    {{Auth::user()->fullName('F')}}!
                </h1>
                @if(isset($quote))
                <p style="font-size: 1.2em;">{{$quote->contents->quotes[0]->quote}} ~ {{$quote->contents->quotes[0]->author}}</p>
                @endif
            </div>
        </div>
        @if(Auth::user()->created_at->diffInDays(Carbon\Carbon::now()) < 14) <!--14 days since user signed up-->
        <div class="container white-text">
            <p style="font-size: 1.4em;" class="font-weight-bold">
                <a href="https://knowledgebase.ganderoceanic.com/en/website/myczqo" class="white-text">
                    <i class="fas fa-question"></i>&nbsp;&nbsp;Need help with myCZQO?
                </a>
            </p>
        </div>
        @endif
    </div>
</div>
<div class="container py-4">
    <h1 data-step="1" data-intro="" class="blue-text font-weight-bold">myCZQO</h1>
    @if (Auth::user()->rating_id >= 5)
    @endif
    <br class="my-2">
    @role('Restricted')
    <div class="alert bg-czqo-blue-light mb-4">
        Your account on Gander Oceanic is currently restricted. You cannot access pages that require an account, except for "Manage your data". Contact the OCA Chief for more information.
    </div>
    @endrole
    <div class="row">
        <div class="col-md-3">
            <ul class="list-unstyled w-100">
                <a class="myczqo-tab active" data-myczqo-tab="yourProfileTab" href="#yourProfile">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-user-circle fa-fw"></i>
                            <span style="font-size: 1.1em;">Your Profile</span>
                        </div>
                    </li>
                </a>
                <a class="myczqo-tab" data-myczqo-tab="certificationTrainingTab" href="#certificationTraining">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-graduation-cap fa-fw"></i>
                            <span style="font-size: 1.1em;">Certfication and Training</span>
                        </div>
                    </li>
                </a>
                <a class="myczqo-tab" data-myczqo-tab="supportTab" href="#support">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-question-circle fa-fw"></i>
                            <span style="font-size: 1.1em;">Support</span>
                        </div>
                    </li>
                </a>{{--
                @hasanyrole('Administrator|Senior Staff|Training Team')
                <a class="myczqo-tab" data-myczqo-tab="instructingTab" href="#instructing">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-chalkboard-teacher"></i>
                            <span style="font-size: 1.1em;">Instructing</span>
                        </div>
                    </li>
                </a>
                @endhasanyrole --}}
                @hasanyrole('Administrator|Senior Staff|Training Team|Marketing Team|Web Team')
                <a class="myczqo-tab" data-myczqo-tab="staffTab" href="#staff">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-cog fa-fw"></i>
                            <span style="font-size: 1.1em;">Staff</span>
                        </div>
                    </li>
                </a>
                @endhasanyrole
                <a class="myczqo-tab no-click" data-myczqo-tab="none" href="https://knowledgebase.ganderoceanic.com">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-book fa-fw"></i>
                            <span style="font-size: 1.1em;">Knowledge Base</span>
                        </div>
                    </li>
                </a>
                <a class="myczqo-tab no-click" data-myczqo-tab="none" href="{{route('me.data')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-database fa-fw"></i>
                            <span style="font-size: 1.1em;">Manage your data</span>
                        </div>
                    </li>
                </a>
                <a class="myczqo-tab no-click" data-myczqo-tab="none" href="{{route('my.preferences')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-cog fa-fw"></i>
                            <span style="font-size: 1.1em;">Manage preferences</span>
                        </div>
                    </li>
                </a>
            </ul>
        </div>
        <div class="col-md-9">
            <div id="yourProfileTab">
                <h3 class="font-weight-bold blue-text pb-2">Your Profile</h3>
                <div class="row">
                    <div class="col-md" data-step="3" data-intro="Here is an overview of your profile, including your CZQO roles. You can change the way your name is displayed by clicking on your name at the top of the panel. (CoC A4(b))">
                        <div class="d-flex flex-row">
                            <div class="myczqo_avatar_container" style=" margin-bottom: 10px; margin-right: 20px;">
                                <a href="#" data-toggle="modal" data-target="#changeAvatar">
                                <div class="myczqo_avatar_object">
                                    <img src="{{Auth::user()->avatar()}}" style="width: 125px; height: 125px; border-radius: 50%;">
                                    <div class="img_overlay"></div>
                                </div>
                                </a>
                            </div>
                            <div>
                                <h5 class="card-title">
                                    <a href="" data-toggle="modal" data-target="#changeDisplayNameModal" class="text-dark text-decoration-underline">
                                        {{ Auth::user()->fullName('FLC') }}
                                    </a>
                                </h5>
                                <h6 class="card-subtitle mb-2 text-muted">
                                    {{Auth::user()->rating_GRP}} ({{Auth::user()->rating_short}})
                                </h6>
                                Region: {{ Auth::user()->region_name }}<br/>
                                Division: {{ Auth::user()->division_name }}<br/>
                                @if (Auth::user()->subdivision_name)
                                vACC/ARTCC: {{ Auth::user()->subdivision_name }}<br/>
                                @endif
                                Role: {{Auth::user()->highestRole()->name}}<br/>
                                @if(Auth::user()->staffProfile)
                                Staff Role: {{Auth::user()->staffProfile->position}}
                                @endif
                            </div>
                        </div>
                        <br/>
                        <div data-step="4" data-intro="Here you can link your Discord account to receive training session reminders and to gain access to the CZQO Discord.">
                        <h3 class="mt-2" style="font-size: 1.3em;">Discord</h3>
                        @if (!Auth::user()->hasDiscord())
                        <p class="mt-1">You have not linked your Discord account.</p>
                        <a href="#" data-toggle="modal" data-target="#discordModal" style="text-decoration:none;">
                            <span class="blue-text">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                            &nbsp;
                            <span class="black-text">
                                Link your Discord
                            </span>
                        </a>
                        @else
                        <p class="mt-1" style="font-size: 1.1em;"><img style="border-radius:50%; height: 30px;" class="img-fluid" src="{{Auth::user()->getDiscordAvatar()}}" alt="">&nbsp;&nbsp;{{Auth::user()->getDiscordUser()->username}}<span style="color: #d1d1d1;">#{{Auth::user()->getDiscordUser()->discriminator}}</span></p>
                        @if(!Auth::user()->memberOfCzqoGuild())
                        <a href="#" data-toggle="modal" data-target="#discordTopModal" style="text-decoration:none;">
                            <span class="blue-text">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                            &nbsp;
                            <span class="black-text">
                                Join Our Discord
                            </span>
                        </a>
                        @endif
                        <a href="#" data-toggle="modal" data-target="#discordModal"  style="text-decoration:none;">
                            <span class="blue-text">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                            &nbsp;
                            <span class="black-text">
                                Unlink
                            </span>
                        </a>
                        @endif
                        <h3 class="mt-4" style="font-size: 1.3em;">Biography</h3>
                        <p>
                        @if (Auth::user()->bio)
                        {{Auth::user()->bio}}
                        @else
                            You have no biography.
                        @endif
                        </p>
                        <a href="#" data-toggle="modal" data-target="#bioModal" style="text-decoration:none;">
                            <span class="blue-text">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                            &nbsp;
                            <span class="black-text">
                                Edit
                            </span>
                        </a>
                        </div>
                    </div>
                    <div class="col-md">
                        @if(Auth::user()->pendingApplication())
                            <div class="card grey lighten-4 p-4 mt-3 shadow-none mb-3">
                                <h5 class="font-weight-bold">You have a pending application for Gander Oceanic</h5>
                                <p style="font-size:1.1em;" class="m-0">#{{Auth::user()->pendingApplication()->reference_id}} - submitted {{Auth::user()->pendingApplication()->created_at->diffForHumans()}}</p>
                                <a href="{{route('training.applications.show', Auth::user()->pendingApplication()->reference_id)}}" class="btn bg-czqo-blue-light mt-4">View</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div id="supportTab" style="display:none;">
                <h3 class="font-weight-bold blue-text pb-2">Support</h3>
                {{-- @if (count($openTickets) < 1)
                    You have no open support tickets
                @else
                    <div class="alert bg-czqo-blue-light">
                        <h5 class="black-text">
                            @if (count($openTickets) == 1)
                                1 open ticket
                            @else
                                {{count($openTickets)}} open tickets
                            @endif
                        </h5>
                        <div class="list-group">
                            @foreach ($openTickets as $ticket)
                                <a href="{{url('/dashboard/tickets/'.$ticket->ticket_id)}}" class="list-group-item list-group-item-action bg-czqo-blue-light black-text rounded-0 ">{{$ticket->title}}<br/>
                                    <small title="{{$ticket->updated_at}} (GMT+0, Zulu)">Last updated {{$ticket->updated_at_pretty()}}</small>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif --}}
                <p>Support tickets are disabled to allow for a re-write of the system. For now, please contact us via email.</p>
                <ul class="list-unstyled mt-2 mb-0">
                    <li class="mb-2">
                        <a href="{{route('feedback.create')}}" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Send feedback to staff</span></a>
                    </li>
                    {{-- <li class="mb-2">
                        <a href="{{route('TODO: TicketURL', ['create' => 'yes'])}}" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Start a support ticket</span></a>
                    </li>
                    <li class="mb-2">
                        <a href="TODO: TicketURL" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">View previous support tickets</span></a>
                    </li>
                    @can('view tickets')
                    <li class="mb-2">
                        <a href="{{route('tickets.staff')}}" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">View staff ticket inbox</span></a>
                    </li>
                    @endcan --}}
                    <li class="mb-2">
                        <a href="https://knowledgebase.ganderoceanic.com/" target="_blank" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">CZQO Knowledge Base</span></a>
                    </li>
                </ul>
            </div>
            <div id="certificationTrainingTab" style="display:none">
                <h3 class="font-weight-bold blue-text pb-2">Certification and Training</h3>
                <h5 class="card-title">Status</h5>
                <div class="card-text">
                    <div class="d-flex flex-row justify-content-left">
                        @if (Auth::user()->rosterProfile)
                        <h3 class="mr-2">
                        @switch (Auth::user()->rosterProfile->certification)
                            @case("certified")
                            <span class="badge badge-success rounded shadow-none">
                                <i class="fa fa-check"></i>&nbsp;
                                Certified
                            </span>
                            @break
                            @case("not_certified")
                            <span class="badge badge-danger rounded shadow-none">
                                <i class="fa fa-times"></i>&nbsp;
                                Not Certified
                            </span>
                            @break
                            @case("training")
                            <span class="badge badge-warning rounded shadow-none">
                                <i class="fa fa-book-open"></i>&nbsp;
                                Training
                            </span>
                            @break
                            @default
                            <span class="badge badge-dark rounded shadow-none">
                                <i class="fa fa-question"></i>&nbsp;
                                Unknown
                            </span>
                        @endswitch
                        </h3>
                        <h3>
                        @switch (Auth::user()->rosterProfile->active)
                            @case(true)
                            <span class="badge badge-success rounded shadow-none">
                                <i class="fa fa-check"></i>&nbsp;
                                Active
                            </span>
                            @break
                            @case(false)
                            <span class="badge badge-danger rounded shadow-none">
                                <i class="fa fa-times"></i>&nbsp;
                                Inactive
                            </span>
                            @break
                        @endswitch
                        </h3>
                    @else
                    Not Gander Certified
                    @endif
                    </div>
                </div>
                @if (Auth::user()->rosterProfile)
                <h5 class="card-title mt-2">Activity</h5>
                @if (Auth::user()->rosterProfile->currency < 0.1)
                <h3><span class="badge rounded shadow-none red">
                    No hours recorded
                </span></h3>
                @elseif (Auth::user()->rosterProfile->currency < 3.0)
                <h3><span class="badge rounded shadow-none blue">
                    {{Auth::user()->rosterProfile->currency}} hours recorded
                </span></h3>
                @elseif (Auth::user()->rosterProfile->currency >= 3.0)
                <h3><span class="badge rounded shadow-none green">
                    {{Auth::user()->rosterProfile->currency}} hours recorded
                </span></h3>
                @endif
                <p>You require 3 hours of activity every 6 months, unless you were certified within the current activity cycle.</p>
                @endif
                <ul class="list-unstyled mt-4 mb-0">
                    <li class="mb-2">
                        <a href="{{route('training.applications.showall')}}" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">View Your Applications</span></a>
                    </li>
                </ul>
            </div>
            @hasanyrole('Administrator|Senior Staff|Training Team')
            <div id="instructingTab" style="display:none;">
                <h3 class="font-weight-bold blue-text pb-2">Instructing</h3>
            </div>
            @endhasanyrole
            <div id="staffTab" style="display:none">
                @hasanyrole('Administrator|Senior Staff|Training Team|Marketing Team|Web Team')
                    <h3 class="font-weight-bold blue-text pb-2">Staff</h3>
                    <ul class="list-unstyled mt-2 mb-0">
                        @can('view events')
                        <li class="mb-2">
                            <a href="{{route('events.admin.index')}}" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Events</span></a>
                        </li>
                        @endcan
                        @can('view articles')
                        <li class="mb-2">
                            <a href="{{route('news.index')}}" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">News</span></a>
                        </li>
                        @endcan
                    </ul>

                    @hasanyrole('Administrator|Senior Staff|Training Team')
                    <h5 class="font-weight-bold blue-text mt-3">Training</h5>
                    <ul class="list-unstyled mt-2 mb-0">
                        <li class="mb-2">
                            <a href="{{(route('training.admin.dashboard'))}}" style="text-decoration:none;">
                                <span class="blue-text">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                                &nbsp;
                                <span class="black-text">
                                    Dashboard
                                </span>
                            </a>
                        </li>
                        @can('view roster admin')
                        <li class="mb-2">
                            <a href="{{(route('training.admin.roster'))}}" style="text-decoration:none;">
                                <span class="blue-text">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                                &nbsp;
                                <span class="black-text">
                                    Roster
                                </span>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{(route('training.admin.solocertifications'))}}" style="text-decoration:none;">
                                <span class="blue-text">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                                &nbsp;
                                <span class="black-text">
                                    Solo Certifications
                                </span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                    @endhasanyrole

                    @canany('edit policies|edit atc resources')
                    <h5 class="font-weight-bold blue-text mt-3">Publications</h5>
                    <ul class="list-unstyled mt-2 mb-0">
                        <li class="mb-2">
                            <a href="{{(route('publications.policies'))}}" style="text-decoration:none;">
                                <span class="blue-text">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                                &nbsp;
                                <span class="black-text">
                                    Edit policies
                                </span>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{(route('training.admin.dashboard'))}}" style="text-decoration:none;">
                                <span class="blue-text">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                                &nbsp;
                                <span class="black-text">
                                    Edit ATC resources
                                </span>
                            </a>
                        </li>
                    </ul>
                    @endcanany

                    @can('view users')
                    <h5 class="font-weight-bold blue-text mt-3">Users</h5>
                    <ul class="list-unstyled mt-2 mb-0">
                        <li class="mb-2">
                            <a href="{{(route('community.users.index'))}}" style="text-decoration:none;">
                                <span class="blue-text">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                                &nbsp;
                                <span class="black-text">
                                    View users
                                </span>
                            </a>
                        </li>
                    </ul>
                    @endcan

                    @can('view network data')
                    <h5 class="font-weight-bold blue-text mt-3">Network</h5>
                    <ul class="list-unstyled mt-2 mb-0">
                        <li class="mb-2">
                            <a href="{{route('network.index')}}" style="text-decoration:none;">
                                <span class="blue-text">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                                &nbsp;
                                <span class="black-text">
                                    View network data
                                </span>
                            </a>
                        </li>
                    </ul>
                    @endcan

                    @can('edit settings')
                    <h5 class="font-weight-bold blue-text mt-3">Admin</h5>
                    <ul class="list-unstyled mt-0 mb-0">
                        <li class="mb-2">
                            <a href="{{route('settings.index')}}" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Settings</span></a>
                        </li>
                    </ul>
                    @endcan
                @endhasanyrole
            </div>
            <br/>
        </div>
    </div>
    <small class="text-muted">Quote of the day provided by <a href="https://theysaidso.com">https://theysaidso.com</a></small>
</div>

<!-- Intro js -->
<script>
    function startTutorial()
    {
        var intro = introJs();
        intro.setOptions({
        steps: [
            {
            intro: "Hi {{Auth::user()->fullName('F')}}! Welcome to the tutorial for the Gander Oceanic website. We're excited to have you join us. On the dashboard, you can get a glance at your status within our OCA and access various functions. To begin, click the 'Next' button below."
            },
            {
            element: "#atcResources",
            intro: "Here you can view resources for Gander controllers, including sector files, documents, and the spreadsheet. If you're not a rostered controller yet, you may not be able to see everything. For pilot resources, click the Pilots tab on the navbar above."
            },
            {
            element: '#yourData',
            intro: "Here you can get an overview of your Gander Oceanic profile. Change your display name by clicking '{{Auth::user()->fullName('FLC')}}l and following the prompts. You can link your Discord account here and access to our Discord community, and you can even set an avatar for yourself. The buttons below allow you to change settings such as your biography, preferences, and manage your data.",
            position: 'right'
            },
            {
            element: '#certification',
            intro: 'Here you can view your certification status with us, and if you\'re a rostered controller, your activity hours. You can also view your previous applications.',
            position: 'left'
            },
            {
            element: '#support',
            intro: "If you ever need support from our staff or wish to send feedback, this is the place to do it. You can create a support ticket to a specific staff member, or send feedback on a controller or our operations.",
            position: 'left'
            },
            {
            intro: 'That\'s all for now! If you have any questions, please do not hesitate to contact us. The tutorial button on the top of the page will disappear when your account is older than 2 weeks. Enjoy!'
            }
        ]
        });

        intro.start();
    }
</script>
<!-- End intro js -->

<!--Change avatar modal-->
<div class="modal fade" id="changeAvatar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: none">
                <h5 class="modal-title" id="exampleModalLongTitle">Change your avatar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{route('users.changeavatar')}}" enctype="multipart/form-data" class="" id="">
            <div class="modal-body">
                <p>Your avatar must comply with the VATSIM Code of Conduct.</p>
                @csrf
                <div class="input-group pb-3">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="file">
                        <label class="custom-file-label">Choose image file</label>
                    </div>
                </div>
                @if(Auth::user()->hasDiscord())
                    or use your Discord avatar (refreshes every 6 hours)<br/>
                    <a href="{{route('users.changeavatar.discord')}}" class="btn bg-czqo-blue-light mt-3">Use Discord Avatar</a>
                @endif
            </div>
            <div class="modal-footer">
                <a href="{{route('users.resetavatar')}}" role="button" class="btn btn-light">Reset Avatar</a>
                <input type="submit" class="btn btn-success" value="Upload">
            </div>
            </form>
        </div>
    </div>
</div>
<!--End change avatar modal-->

<!--Biography modal-->
<div class="modal fade" id="bioModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit your biography</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('me.editbio')}}" method="POST">
            <div class="modal-body">
                @csrf
                <p>Your biography must comply with the VATSIM Code of Conduct. Markdown styling is disabled.</p>
                <textarea id="contentMD" name="bio" class="w-75">{{Auth::user()->bio}}</textarea>
                <script>
                    var simplemde = new SimpleMDE({ element: document.getElementById("contentMD"), toolbar: false });
                </script>
                <p>Wonder what the purpose of a biography is? <a href="https://knowledgebase.ganderoceanic.com/en/website/myczqo" target="_blank">Find out here.</a></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Dismiss</button>
                <button type="submit" class="btn btn-success">Save Biography</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!--End biography modal-->

<!--Change display name modal-->
<div class="modal fade" id="changeDisplayNameModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Change your display name</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{route('users.changedisplayname')}}">
            <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label>Display first name</label>
                        <input type="text" class="form-control" value="{{Auth::user()->display_fname}}" name="display_fname" id="input_display_fname">
                        <br/>
                        <a class="btn bg-czqo-blue-light btn-sm" role="button" onclick="resetToCertFirstName()"><span style="color: #000">Reset to your CERT first name</span></a>
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Dismiss</button>
                <input type="submit" class="btn btn-success" value="Save Changes">
            </div>
            </form>
        </div>
    </div>
</div>
<!--End change display name modal-->

<!--Link/unlink Discord modal-->
<div class="modal fade" id="discordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        @if (!Auth::user()->hasDiscord())
        <div class="modal-content">
            <div class="modal-header pb-2" style="border:none; text-align:center;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-center flex-column">
                    <h3 class="font-weight-bold blue-text">Link your Discord Account to CZQO</h3>
                    <ul class="list-unstyled mt-4">
                        <li class="w-100">
                            <div class="grey lighten-3 p-4" style="border-radius: 20px;">
                                <div class="d-flex flex-row">
                                    <img style="height: 40px; margin-right: 20px;" src="https://resources.ganderoceanic.com/media/img/brand/sqr/ZQO_SQ_TSPBLUE.png" alt="">
                                    <p class="font-weight-bold" style="width: 75%; text-align:left; font-size: 1.1em;">Join the Gander Oceanic Discord community</p>
                                </div>
                            </div>
                        </li>
                        <li class="w-100">
                            <div class="grey lighten-3 p-4" style="border-radius: 20px;">
                                <div class="d-flex flex-row">
                                    <i style="font-size:35px; margin-right:20px;" class="fas fa-user-circle blue-text"></i>
                                    <p class="font-weight-bold" style="width: 75%; text-align:left; font-size: 1.1em;">Use your Discord avatar as your CZQO website avatar</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <a href="{{route('me.discord.link')}}" class="class btn btn-primary mt-3">Link Your Discord Account</a>
                    <p class="text-muted text-center mt-2">You will be redirected to Discord to link your account. Information collected is shown on the Discord authorisation screen. Read our privacy policy for details.</p>
                </div>
            </div>
        </div>
        @else
        <div class="modal-content">
            <div class="modal-header pb-2" style="border:none; text-align:center;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-center flex-column">
                    <h3 class="font-weight-bold blue-text">Unlink your Discord account</h3>
                    <p>Unlinking your account will:</p>
                    <ul class="mt-2 list-unstyled">
                        <li class="mb-2">Remove you from the CZQO Discord, if you're a member</li>
                        <li class="mb-2">Remove your Discord avatar if you have it selected</li>
                    </ul>
                    <a href="{{route('me.discord.unlink')}}" class="class btn btn-danger mt-3">Unlink</a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
<script>
    //$("#discordModal").modal();
</script>
<!--End Discord modal-->

@if(!Auth::user()->memberOfCzqoGuild())
<!--Join guild modal-->
<div class="modal fade" id="joinDiscordServerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Join the Gander Oceanic Discord server</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Joining the Gander Oceanic Discord server allows you to join the Gander Oceanic controller and pilot community.</p>
                <h5>Rules</h5>
                <ul>
                    <li>1. The VATSIM Code of Conduct applies.</li>
                    <li>2. Always show respect and common decency to fellow members.</li>
                    <li>3. Do not send server invites to servers unrelated to VATSIM without staff permission. Do not send ANY invites via DMs unless asked to.
                    </li>
                    <li>4. Do not send spam in the server, including images, text, or emotes.</li>
                </ul>
                <p>Clicking the 'Join' button will redirect you to Discord. We require the Join Server permission to add your Discord account to the server.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Dismiss</button>
                <a role="button" type="submit" href="{{route('me.discord.join')}}" class="btn btn-primary">Join</a>
            </div>
        </div>
    </div>
</div>
@endif
<!--End join guild modal-->

{{-- <div class="modal fade" id="ctpSignUpModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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

</div> --}}

@stop
