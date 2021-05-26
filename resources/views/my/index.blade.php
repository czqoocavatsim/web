@extends('layouts.primary', ['solidNavBar' => false])
@section('title', 'myCZQO - ')
@section('content')
<div class="jarallax card card-image rounded-0 blue"  data-jarallax data-speed="0.2">
    <img class="jarallax-img" src="{{$bannerImg->path ?? ''}}" alt="">
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
    <h1 data-step="1" data-intro="" class="blue-text fw-800">myCZQO</h1>
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
                            <span style="font-size: 1.1em;">{{Auth::user()->fullName('F')}}</span>
                        </div>
                    </li>
                </a>
                <a class="myczqo-tab" data-myczqo-tab="certificationTrainingTab" href="#certificationTraining">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-id-card-alt fa-fw"></i>
                            <span style="font-size: 1.1em;">Certfication and Activity</span>
                        </div>
                    </li>
                </a>
                <a class="myczqo-tab no-click" data-myczqo-tab="none" href="{{route('training.portal.index')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-graduation-cap"></i>
                            <span style="font-size: 1.1em;">Training Portal</span>
                        </div>
                    </li>
                </a>
                <a class="myczqo-tab" data-myczqo-tab="supportTab" href="#support">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-question-circle fa-fw"></i>
                            <span style="font-size: 1.1em;">Support and Feedback</span>
                        </div>
                    </li>
                </a>
                <a class="myczqo-tab no-click" data-myczqo-tab="none" href="https://knowledgebase.ganderoceanic.com">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-book fa-fw"></i>
                            <span style="font-size: 1.1em;">Knowledge Base</span>
                        </div>
                    </li>
                </a>
                @hasanyrole('Administrator|Senior Staff|Marketing Team|Web Team|Instructor')
                <li class="w-100 my-3" style="border:none;">
                    <div class="d-flex h-100 fw-400 flex-row justify-content-left align-items-center">
                        <span style="font-size: 1em;" class="text-muted">STAFF</span>
                    </div>
                </li>
                @endhasanyrole
                @hasanyrole('Administrator|Senior Staff|Instructor')
                <a class="myczqo-tab no-click" data-myczqo-tab="none" href="{{route('training.admin.dashboard')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-chalkboard-teacher"></i>
                            <span style="font-size: 1.1em;">Instructing</span>
                        </div>
                    </li>
                </a>
                @endhasanyrole
                @hasanyrole('Administrator|Senior Staff|Marketing Team|Web Team')
                <a class="myczqo-tab" data-myczqo-tab="staffTab" href="#staff">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-cog fa-fw"></i>
                            <span style="font-size: 1.1em;">Administration</span>
                        </div>
                    </li>
                </a>
                @endhasanyrole
                <li class="w-100 my-3" style="border:none;">
                    <div class="d-flex h-100 fw-400 flex-row justify-content-left align-items-center">
                        <span style="font-size: 1em;" class="text-muted">SETTINGS</span>
                    </div>
                </li>
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
                <a class="myczqo-tab no-click" data-myczqo-tab="none" href="{{route('auth.logout')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-sign-out-alt fa-fw"></i>
                            <span style="font-size: 1.1em;">Log out</span>
                        </div>
                    </li>
                </a>
            </ul>
        </div>
        <div class="col-md-9">
            <div id="yourProfileTab">
                <h2 class="fw-700 blue-text pb-2">Your Profile</h2>
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
                                    <a href="" data-toggle="modal" data-target="#changeDisplayNameModal" class="text-dark text-decoration-underline fw-500">
                                        {{ Auth::user()->fullName('FLC') }} <i style="font-size: 0.8em;" class="ml-1 far fa-edit text-muted"></i>
                                    </a>
                                </h5>
                                <h6 class="card-subtitle mb-2 text-muted fw-500">
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
                        <h3 class="mt-2 fw-600" style="font-size: 1.3em;">Discord</h3>
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
                        </a>&nbsp;
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
                        <h3 class="mt-4 fw-600" style="font-size: 1.3em;">Biography</h3>
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
                            <a href="{{route('training.applications.show', Auth::user()->pendingApplication()->reference_id)}}" class="list-group-item list-group-item-action p-4 z-depth-1 shadow-none mb-3">
                                <h4 class="blue-text fw-600">You have a pending application for Gander Oceanic</h4>
                                <p style="font-size:1.1em;" class="m-0">#{{Auth::user()->pendingApplication()->reference_id}} - submitted {{Auth::user()->pendingApplication()->created_at->diffForHumans()}}</p>
                            </a>
                        @endif
                        @if ($studentProfile = Auth::user()->studentProfile && $cert = Auth::user()->studentProfile->soloCertification())
                        <div class="list-group-item rounded p-4 mb-3 z-depth-1 shadow-none">
                            <h4 class="fw-600 blue-text">{{ $cert->expires->diffInDays(Carbon\Carbon::now()) <= 2 ? 'Your solo certification is about to expire' : 'Your active solo certification'}}</h4>
                            <h6 class="fw-500">Expires: {{$cert->expires->toFormattedDateString()}} (in {{$cert->expires->diffForHumans()}})</h6>
                            <h6 class="fw-500">Granted by: {{$cert->instructor->fullName('FL')}}</h6>
                            <p class="mt-3 mb-0">{{ $cert->expires->diffInDays(Carbon\Carbon::now()) <= 2 ? 'Contact your instructor to request an extension or proceed to an OTS assessment.' : 'Your use of this solo certification is bound to our policies and VATSIM\'s GRP. Your instructor will give you more information.'}}</p>
                        </div>
                        @endif
                        @if ($studentProfile = Auth::user()->studentProfile && $session = Auth::user()->studentProfile->upcomingTrainingSession())
                        <div class="list-group-item rounded p-4 mb-3 z-depth-1 shadow-none">
                            <h4 class="fw-600 blue-text">Your upcoming training session</h4>
                            <h6 class="fw-500">Scheduled for {{$session->scheduled_time->toFormattedDateString()}} (in {{$session->scheduled_time->diffForHumans()}})</h6>
                            <h6 class="fw-500 mb-0">With {{$session->instructor->user->fullName('FL')}}</h6>
                        </div>
                        @endif
                        @if ($studentProfile = Auth::user()->studentProfile && $session = Auth::user()->studentProfile->upcomingOtsSession())
                        <div class="list-group-item rounded p-4 mb-3 z-depth-1 shadow-none">
                            <h4 class="fw-600 blue-text">Your upcoming OTS session</h4>
                            <h6 class="fw-500">Scheduled for {{$session->scheduled_time->toFormattedDateString()}} (in {{$session->scheduled_time->diffForHumans()}})</h6>
                            <h6 class="fw-500 mb-0">With {{$session->instructor->user->fullName('FL')}}</h6>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div id="supportTab" style="display:none;">
                <h2 class="fw-700 blue-text pb-2">Support and Feedback</h2>
                <h3 class="mt-4 mb-3 fw-600" style="font-size: 1.3em;">General Support</h3>
                <p>We're always here to assist you. Feel free to contact the relevant staff member via email for assistance with any enquries you may have.</p>
                <p style="font-size: 1em;" class="mt-2">
                    <a class="font-weight-bold text-body" href="{{route('staff')}}">Find their emails &nbsp;&nbsp;<i class="fas fa-arrow-right blue-text"></i></a>
                </p>
                <h3 class="mt-4 mb-3 fw-600" style="font-size: 1.3em;">Feedback</h3>
                <p>We love feedback! Submit feedback on controllers or our operations here.</p>
                <p style="font-size: 1em;" class="mt-2">
                    <a class="font-weight-bold text-body" href="{{route('my.feedback.new')}}">Submit feedback &nbsp;&nbsp;<i class="fas fa-arrow-right blue-text"></i></a>
                </p>
                <p style="font-size: 1em;" class="mt-2">
                    <a class="font-weight-bold text-body" href="{{route('my.feedback')}}">Your previous feedback &nbsp;&nbsp;<i class="fas fa-arrow-right blue-text"></i></a>
                </p>
            </div>
            <div id="certificationTrainingTab" style="display:none">
                <h3 class="font-weight-bold blue-text pb-2">Certification</h3>
                @if($rosterProfile = Auth::user()->rosterProfile)
                    <div class="d-flex flex-row justify-content-left">
                        <h3 class="mr-3">
                            {{Auth::user()->rosterProfile->certificationLabelHtml()}}
                        </h3>
                        <h3>
                            {{$rosterProfile->activeLabelHtml()}}
                        </h3>
                    </div>
                    <h3 class="font-weight-bold blue-text mt-3 pb-2">Activity</h3>
                    @if (Auth::user()->rosterProfile->currency < 0.1)
                        <h3>
                            <span style='font-weight: 400' class='badge rounded p-2 red text-white shadow-none'>
                            No hours recorded
                            </span>
                        </h3>
                    @elseif (Auth::user()->rosterProfile->currency < 3.0)
                        <h3>
                            <span style='font-weight: 400' class='badge rounded blue text-white p-2 shadow-none'>
                                {{Auth::user()->rosterProfile->currency}} hours recorded
                            </span>
                        </h3>
                    @elseif (Auth::user()->rosterProfile->currency >= 3.0)
                        <h3>
                            <span style='font-weight: 400' class='badge rounded green text-white p-2 shadow-none'>
                                {{Auth::user()->rosterProfile->currency}} hours recorded
                            </span>
                        </h3>
                    @endif
                    <p class="mt-4">You require 3 hours of activity every quarter, unless you were certified within the current activity cycle.</p>
                @else
                    <h3>
                        <span style='font-weight: 400' class='badge rounded p-2 red text-white shadow-none'>
                            <i class="fas fa-times mr-2"></i>&nbsp;Not Gander Certified
                        </span>
                    </h3>
                    @if (Auth::user()->rating_id >= 5 && Auth::user()->can('start applications'))
                        <div class="list-group-item rounded p-4 my-3 z-depth-1 shadow-none w-50 mt-4">
                            <h4 class="blue-text"><i style="margin-right: 10px;" >👋</i>Apply for Gander Oceanic Certification</h4>
                            <p style="font-size: 1.1em;">Interested in joining our team of oceanic controllers?</p>
                            <p style="font-size: 1.2em;" class="mt-3 mb-0">
                                <a class="font-weight-bold text-body" href="{{route('training.applications.apply')}}">Start your application &nbsp;&nbsp;<i class="fas fa-arrow-right blue-text"></i></a>
                            </p>
                        </div>
                    @endif
                @endif
            </div>
            @hasanyrole('Administrator|Senior Staff|Instructor')
            <div id="instructingTab" style="display:none;">
                <h3 class="font-weight-bold blue-text pb-2">Instructing</h3>
            </div>
            @endhasanyrole
            <div id="staffTab" style="display:none">
                @hasanyrole('Administrator|Senior Staff|Instructor|Marketing Team|Web Team')
                    <h2 class="font-weight-bold blue-text pb-2">Administration</h2>
                    <div class="row">
                        @hasanyrole('Administrator|Senior Staff|Instructor')
                        <div class="col-md-4">
                            <div class="card mb-3 shadow-none">
                                <h4 class="blue-text mb-3">Training</h4>
                                <div class="list-group z-depth-1">
                                    <a href="{{route('training.admin.dashboard')}}" class="waves-effect list-group-item list-group-item-action">
                                        <i style="margin-right: 10px;" class="fas fa-tachometer-alt fa-fw"></i>Dashboard
                                    </a>
                                    @can('view roster admin')
                                    <a href="{{route('training.admin.roster')}}" class="waves-effect list-group-item list-group-item-action">
                                        <i style="margin-right: 10px;" class="fas fa-users fa-fw"></i>Roster
                                    </a>
                                    <a href="{{route('training.admin.solocertifications')}}" class="waves-effect list-group-item list-group-item-action">
                                        <i style="margin-right: 10px;" class="fas fa-certificate fa-fw"></i>Solo Certifications
                                    </a>
                                    @endcan
                                    @can('view applications')
                                    <a href="{{route('training.admin.applications')}}" class="waves-effect list-group-item list-group-item-action">
                                        <i style="margin-right: 10px;" class="fas fa-clock fa-fw"></i>Applications
                                    </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                        @endhasanyrole
                        @canany('view events|view articles')
                        <div class="col-md-4">
                            <div class="card mb-3 shadow-none">
                                <h4 class="blue-text mb-3">Events and News</h4>
                                <div class="list-group z-depth-1">
                                    @can('view events')
                                    <a href="{{route('events.admin.index')}}" class="waves-effect list-group-item list-group-item-action">
                                        <i style="margin-right: 10px;" class="fas fa-calendar"></i>
                                        Events
                                    </a>
                                    @endcan
                                    @can('view articles')
                                    <a href="{{route('news.index')}}" class="waves-effect list-group-item list-group-item-action">
                                        <i style="margin-right: 10px;" class="fas fa-newspaper"></i>
                                        News
                                    </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                        @endcanany
                        @canany('edit policies|edit atc resources')
                        <div class="col-md-4">
                            <div class="card mb-3 shadow-none">
                                <h4 class="blue-text mb-3">Publications</h4>
                                <div class="list-group z-depth-1">
                                    @can('edit policies')
                                    <a href="{{route('publications.policies')}}" class="waves-effect list-group-item list-group-item-action">
                                        <i style="margin-right: 10px;" class="fas fa-file-alt fa-fw"></i>Policies
                                    </a>
                                    @endcan
                                    @can('edit atc resources')
                                    <a href="{{route('publications.atc-resources')}}" class="waves-effect list-group-item list-group-item-action">
                                        <i style="margin-right: 10px;" class="fas fa-file-alt fa-fw"></i>ATC Resources
                                    </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                        @endcanany
                        @hasanyrole('Administrator|Senior Staff')
                        <div class="col-md-4">
                            <div class="card mb-3 shadow-none">
                                <h4 class="blue-text mb-3">Community</h4>
                                <div class="list-group z-depth-1">
                                    @can('view users')
                                    <a href="{{route('community.users.index')}}" class="waves-effect list-group-item list-group-item-action">
                                        <i style="margin-right: 10px;" class="fas fa-users fa-fw"></i>Users
                                    </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                        @endhasanyrole
                        @can('view network data')
                        <div class="col-md-4">
                            <div class="card mb-3 shadow-none">
                                <h4 class="blue-text mb-3">Network</h4>
                                <div class="list-group z-depth-1">
                                    @can('view users')
                                    <a href="{{route('network.index')}}" class="waves-effect list-group-item list-group-item-action">
                                        <i style="margin-right: 10px;" class="fas fa-wifi fa-fw"></i>View Network Data
                                    </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                        @endcan
                        @can('edit settings')
                        <div class="col-md-4">
                            <div class="card mb-3 shadow-none">
                                <h4 class="blue-text mb-3">Admin</h4>
                                <div class="list-group z-depth-1">
                                    <a href="{{route('settings.index')}}" class="waves-effect list-group-item list-group-item-action">
                                        <i style="margin-right: 10px;" class="fas fa-cog fa-fw"></i>Site Settings
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endcan
                    </div>
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
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content p-2">
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
                <textarea id="contentMD" name="bio" style="display:none;" ></textarea>
                <script>
                    var simplemde = new EasyMDE({ autofocus: true, autoRefresh: true, element: document.getElementById("contentMD"), toolbar: false, initialValue: '{{Auth::user()->bio}}' });
                    simplemde.value('{{Auth::user()->bio}}')
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
                    <p>Your display name will display everywhere on Gander Oceanic, including the controller roster. It is advised to use the same display name that you would use on the VATSIM network. All display names must comply with section A4 of the VATSIM Code of Conduct.</p>
                    <div class="form-group">
                        <div class="md-form">
                            <input type="text" class="form-control" value="{{Auth::user()->display_fname}}" name="display_fname" id="input_display_fname">
                            <label for="input_display_fname" class="active">Display first name</label>
                        </div>
                        <a class="btn btn-light btn-sm" role="button" onclick="resetToCertFirstName()"><span style="color: #000">Reset to your CERT first name</span></a>
                        <script>
                            function resetToCertFirstName() {
                                $("#input_display_fname").val("{{Auth::user()->fname}}")
                            }
                        </script>
                    </div>
                    <div class="form-group">
                        <label class="text-muted">Format</label>
                        <select name="format" class="custom-select">
                            <option value="showall">First name, last name, and CID ({{Auth::user()->display_fname}} {{Auth::user()->lname}} {{Auth::id()}})</option>
                            <option value="showfirstcid">First name and CID ({{Auth::user()->display_fname}} {{Auth::id()}})</option>
                            <option value="showcid">CID only ({{Auth::id()}})</option>
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
                                    <img style="height: 40px; margin-right: 20px;" src="https://cdn.ganderoceanic.com/resources/media/img/brand/sqr/ZQO_SQ_TSPBLUE.png" alt="">
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
            <div class="modal-header pb-2">
                <h5 class="modal-title" id="exampleModalLongTitle">Unlink your Discord account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Unlinking your account will:</p>
                <ul class="mt-2">
                    <li class="mb-2">Remove you from the CZQO Discord, if you're a member</li>
                    <li>Remove your Discord avatar if you have it selected</li>
                </ul>
            </div>
            <div class="modal-footer">
                <a href="{{route('me.discord.unlink')}}" class="class btn btn-danger">Unlink</a>
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
