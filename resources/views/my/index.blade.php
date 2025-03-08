@extends('layouts.primary', ['solidNavBar' => false])
@section('title', 'myCZQO - ')
@section('content')
    @php
        $user = auth()->user();
    @endphp

    <div class="jarallax card card-image blue rounded-0"  data-jarallax data-speed="1">
    {{-- <img class="jarallax-img" src="{{asset('assets/resources/media/img/website/euroscope_client.png')}}" alt=""> --}}
        <div class="text-white text-left rgba-stylish-strong py-3 pt-5 px-4">
            <div class="container">
                <div class="py-5">
                    <h1 class="h1 my-4 py-2 font-weight-bold" style="font-size: 3em;">
                        <?php
                        function randomArrayVar($array)
                        {
                            if (!is_array($array)) {
                                return $array;
                            }
                            return $array[array_rand($array)];
                        }

                        //list of grettings as arary

                        $greeting = [
                            'aloha' => 'Aloha',
                            'ahoy' => 'Ahoy',
                            'bonjour' => 'Bonjour',
                            'gday' => "G'day",
                            'hello' => 'Hello',
                            'hey' => 'Hey',
                            'hi' => 'Hi',
                            'hola' => 'Hola',
                            'howdy' => 'Howdy',
                        ];

                        //echo greeting
                        echo randomArrayVar($greeting);
                        ?>
                        {{ $user->fullName('F') }}!
                    </h1>
                </div>
            </div>
            @if ($user->created_at->diffInDays(Carbon\Carbon::now()) < 14)
                <!--14 days since user signed up-->
                <div class="container white-text">
                    <p style="font-size: 1.4em;" class="font-weight-bold">
                        <a href="https://knowledgebase.ganderoceanic.ca/en/website/myczqo" class="white-text">
                            <i class="fas fa-question"></i>&nbsp;&nbsp;Need help with myCZQO?
                        </a>
                    </p>
                </div>
            @endif
        </div>
    </div>
    <div class="container py-4">
        <h1 data-step="1" data-intro="" class="blue-text fw-800">myCZQO</h1>
        @if ($user->rating_id >= 5)
        @endif
        <br class="my-2">
        @role('Restricted')
            <div class="alert bg-czqo-blue-light mb-4">
                Your account on Gander Oceanic is currently restricted. You cannot access pages that require an account, except
                for "Manage your data". Contact the FIR Director for more information.
            </div>
        @endrole
        <div class="row">
            <div class="col-md-3">
                <ul class="list-unstyled w-100">
                    <a class="myczqo-tab active" data-myczqo-tab="yourProfileTab" href="#yourProfile">
                        <li class="w-100">
                            <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                                <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-user-circle fa-fw"></i>
                                <span style="font-size: 1.1em;">{{ $user->fullName('F') }}</span>
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
                    <a class="myczqo-tab no-click" data-myczqo-tab="none" href="{{ route('training.portal.index') }}">
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
                    <a class="myczqo-tab no-click" data-myczqo-tab="none" href="https://knowledgebase.ganderoceanic.ca">
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
                        <a class="myczqo-tab no-click" data-myczqo-tab="none" href="{{ route('training.admin.dashboard') }}">
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
                    <a class="myczqo-tab no-click" data-myczqo-tab="none" href="{{ route('me.data') }}">
                        <li class="w-100">
                            <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                                <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-database fa-fw"></i>
                                <span style="font-size: 1.1em;">Manage your data</span>
                            </div>
                        </li>
                    </a>
                    <a class="myczqo-tab no-click" data-myczqo-tab="none" href="{{ route('my.preferences') }}">
                        <li class="w-100">
                            <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                                <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-cog fa-fw"></i>
                                <span style="font-size: 1.1em;">Manage preferences</span>
                            </div>
                        </li>
                    </a>
                    <a class="myczqo-tab no-click" data-myczqo-tab="none" href="{{ route('auth.logout') }}">
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
                        <div class="col-md" data-step="3"
                            data-intro="Here is an overview of your profile, including your CZQO roles. You can change the way your name is displayed by clicking on your name at the top of the panel. (CoC A4(b))">
                            <div class="d-flex flex-row">
                                <div class="myczqo_avatar_container" style=" margin-bottom: 10px; margin-right: 20px;">
                                    <a href="#" data-toggle="modal" data-target="#changeAvatar">
                                        <div class="myczqo_avatar_object">
                                            <img src="{{ $user->avatar() }}"
                                                style="width: 125px; height: 125px; border-radius: 50%;">
                                            <div class="img_overlay"></div>
                                        </div>
                                    </a>
                                </div>
                                <div>
                                    <h5 class="card-title">
                                        <a href="" data-toggle="modal" data-target="#changeDisplayNameModal"
                                            class="text-dark text-decoration-underline fw-500">
                                            {{ $user->fullName('FLC') }} <i style="font-size: 0.8em;"
                                                class="ml-1 far fa-edit text-muted"></i>
                                        </a>
                                    </h5>
                                    <h6 class="card-subtitle mb-2 text-muted fw-500">
                                        {{ $user->rating_GRP }} ({{ $user->rating_short }})
                                    </h6>
                                    Region: {{ $user->region_name }}<br />
                                    Division: {{ $user->division_name }}<br />
                                    @if ($user->subdivision_name)
                                        vACC/ARTCC: {{ $user->subdivision_name }}<br />
                                    @endif
                                    Role: {{ $user->highestRole()->name }}<br />
                                    @if ($user->staffProfile)
                                        Staff Role: {{ $user->staffProfile->position }}
                                    @endif
                                </div>
                            </div>
                            <br />
                            <div data-step="4"
                                data-intro="Here you can link your Discord account to receive training session reminders and to gain access to the CZQO Discord.">
                                <h3 class="mt-2 fw-600" style="font-size: 1.3em;">Discord</h3>
                                @if (!$user->hasDiscord())
                                    <p class="mt-1">You have not linked your Discord account.</p>
                                    <a href="#" data-toggle="modal" data-target="#discordTopModal"
                                        style="text-decoration:none;">
                                        <span class="blue-text">
                                            <i class="fas fa-chevron-right"></i>
                                        </span>
                                        &nbsp;
                                        <span class="black-text">
                                            Link your Discord
                                        </span>
                                    </a>
                                @else
                                    <p class="mt-1" style="font-size: 1.1em;"><img
                                            style="border-radius:50%; height: 30px;" class="img-fluid"
                                            src="{{ $user->getDiscordAvatar() }}"
                                            alt="">&nbsp;&nbsp;{{ $user->discord_username }}</p>
                                    @if (!$user->member_of_czqo)
                                        <a href="#" data-toggle="modal" data-target="#joinDiscordServerModal"
                                            style="text-decoration:none;">
                                            <span class="blue-text">
                                                <i class="fas fa-chevron-right"></i>
                                            </span>
                                            &nbsp;
                                            <span class="black-text">
                                                Join Our Discord
                                            </span>
                                        </a>&nbsp;
                                    @endif
                                    <a href="#" data-toggle="modal" data-target="#discordTopModal"
                                        style="text-decoration:none;">
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
                                    @if ($user->bio)
                                        {{ $user->bio }}
                                    @else
                                        You have no biography.
                                    @endif
                                </p>
                                <a href="#" data-toggle="modal" data-target="#bioModal"
                                    style="text-decoration:none;">
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
                            @if ($user->pendingApplication())
                                <a href="{{ route('training.applications.show',$user->pendingApplication()->reference_id) }}"
                                    class="list-group-item list-group-item-action p-4 z-depth-1 shadow-none mb-3">
                                    <h4 class="blue-text fw-600">You have a pending application for Gander Oceanic</h4>
                                    <p style="font-size:1.1em;" class="m-0">
                                        #{{ $user->pendingApplication()->reference_id }} - submitted
                                        {{ $user->pendingApplication()->created_at->diffForHumans() }}</p>
                                </a>
                            @endif
                            @if (
                                $studentProfile =
                                    $user->studentProfile &&
                                    ($cert = $user->studentProfile->soloCertification()))
                                <div class="list-group-item rounded p-4 mb-3 z-depth-1 shadow-none">
                                    <h4 class="fw-600 blue-text">
                                        {{ $cert->expires->diffInDays(Carbon\Carbon::now()) <= 2 ? 'Your solo certification is about to expire' : 'Your active solo certification' }}
                                    </h4>
                                    <h6 class="fw-500">Expires: {{ $cert->expires->toFormattedDateString() }} (in
                                        {{ $cert->expires->diffForHumans() }})</h6>
                                    <h6 class="fw-500">Granted by: {{ $cert->instructor->fullName('FL') }}</h6>
                                    <p class="mt-3 mb-0">
                                        {{ $cert->expires->diffInDays(Carbon\Carbon::now()) <= 2 ? 'Contact your instructor to request an extension or proceed to an OTS assessment.' : 'Your use of this solo certification is bound to our policies and VATSIM\'s GRP. Your instructor will give you more information.' }}
                                    </p>
                                </div>
                            @endif
                            @if (
                                $studentProfile =
                                    $user->studentProfile &&
                                    ($session = $user->studentProfile->upcomingTrainingSession()))
                                <div class="list-group-item rounded p-4 mb-3 z-depth-1 shadow-none">
                                    <h4 class="fw-600 blue-text">Your upcoming training session</h4>
                                    <h6 class="fw-500">Scheduled for
                                        {{ $session->scheduled_time->toFormattedDateString() }} (in
                                        {{ $session->scheduled_time->diffForHumans() }})</h6>
                                    <h6 class="fw-500 mb-0">With {{ $session->instructor->user->fullName('FL') }}</h6>
                                </div>
                            @endif
                            @if (
                                $studentProfile =
                                    $user->studentProfile &&
                                    ($session = $user->studentProfile->upcomingOtsSession()))
                                <div class="list-group-item rounded p-4 mb-3 z-depth-1 shadow-none">
                                    <h4 class="fw-600 blue-text">Your upcoming OTS session</h4>
                                    <h6 class="fw-500">Scheduled for
                                        {{ $session->scheduled_time->toFormattedDateString() }} (in
                                        {{ $session->scheduled_time->diffForHumans() }})</h6>
                                    <h6 class="fw-500 mb-0">With {{ $session->instructor->user->fullName('FL') }}</h6>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div id="supportTab" style="display:none;">
                    <h2 class="fw-700 blue-text pb-2">Support and Feedback</h2>
                    <h3 class="mt-4 mb-3 fw-600" style="font-size: 1.3em;">General Support</h3>
                    <p>We're always here to assist you. Feel free to contact the relevant staff member via email for
                        assistance with any enquries you may have.</p>
                    <p style="font-size: 1em;" class="mt-2">
                        <a class="font-weight-bold text-body" href="{{ route('staff') }}">Find their emails
                            &nbsp;&nbsp;<i class="fas fa-arrow-right blue-text"></i></a>
                    </p>
                    <h3 class="mt-4 mb-3 fw-600" style="font-size: 1.3em;">Feedback</h3>
                    <p>We love feedback! Submit feedback on controllers or our operations here.</p>
                    <p style="font-size: 1em;" class="mt-2">
                        <a class="font-weight-bold text-body" href="{{ route('my.feedback.new') }}">Submit feedback
                            &nbsp;&nbsp;<i class="fas fa-arrow-right blue-text"></i></a>
                    </p>
                    <p style="font-size: 1em;" class="mt-2">
                        <a class="font-weight-bold text-body" href="{{ route('my.feedback') }}">Your previous feedback
                            &nbsp;&nbsp;<i class="fas fa-arrow-right blue-text"></i></a>
                    </p>
                </div>
                <div id="certificationTrainingTab" style="display:none">
                    <h3 class="font-weight-bold blue-text pb-2">Certification</h3>
                    @if ($rosterProfile = $user->rosterProfile)
                        <div class="d-flex flex-row justify-content-left">
                            <h3 class="mr-3">
                                {{ $user->rosterProfile->certificationLabelHtml() }}
                            </h3>
                            <h3>
                                {{ $rosterProfile->activeLabelHtml() }}
                            </h3>
                        </div>

                        <h3 class="font-weight-bold blue-text mt-4 pb-2">Currency Hours</h3>

                        @php
                            $currency = auth()->user()->rosterProfile->currency;
                            $class = $currency < 0.5 ? 'red' : ($currency < 6.0 ? 'blue' : 'green');
                        @endphp

                        <h3>
                            <span style='font-weight: 400'
                                class='badge rounded {{ $class }} text-white p-2 shadow-none'>

                                @if($currency == 0)
                                    <td class="bg-success text-white">
                                        0m
                                    </td>
                                @elseif($currency < 1)
                                    <td class="bg-success text-white">
                                        {{ str_pad(round(($currency - floor($currency)) * 60), 2, '0', STR_PAD_LEFT) }}m
                                    </td>
                                @else
                                    <td class="bg-success text-white">
                                        {{ floor($currency) }}h {{ str_pad(round(($currency - floor($currency)) * 60), 2, '0', STR_PAD_LEFT) }}m
                                    </td>
                                @endif
                            </span>
                        </h3>   

                        {{-- Certified in Q3 - 3hrs Currency Requirements --}}
                        @if(auth()->user()->rosterProfile->certified_in_q3 == 1)
                        <div class="d-flex flex-row justify-content-left">
                            <h5>
                                <span style='font-weight: 400' class='badge rounded p-2 shadow-none blue text-white'><i class='fas fa-info mr-2'></i> As you were certified in Q3, you are only required to attain 3 hours currency for this year (6 hours next year).</span>
                            </h5>
                        </div>
                        @endif

                        {{-- Certified in Q4 - No Currency Requirements --}}
                        @if(auth()->user()->rosterProfile->certified_in_q4 == 1)
                            <div class="d-flex flex-row justify-content-left">
                                <h5>
                                    <span style='font-weight: 400' class='badge rounded p-2 shadow-none blue text-white'><i class='fas fa-info mr-2'></i> As you were certified in Q4, you do not have any currency requirements for this year (6 hours next year).</span>
                                </h5>
                            </div>
                        @endif

                        @if(auth()->user()->rosterProfile->certified_in_q4 !== 1 && auth()->user()->rosterProfile->certified_in_q3 !== 1)<p class="mt-2">In order to remain active, you require a minimum of six hours recorded during {{\Carbon\Carbon::now()->format('Y')}}.</p> @endif

                        <h3 class="font-weight-bold blue-text mt-4 pb-2">Your Connections</h3>

                        @if(!$sessions->isEmpty())
                            <p class="mt-2">List of all your Gander Oceanic connections to VATSIM during {{\Carbon\Carbon::now()->format('Y')}}.</p>
                            <table id="dataTable" class="table table-hover">
                                <thead>
                                    <th>Position</th>
                                    <th>Logon</th>
                                    <th>Logoff</th>
                                    <th>Time</th>
                                </thead>
                                <tbody>
                                    @foreach ($sessions as $s)
                                        <tr>
                                            <th>
                                                {{$s->callsign}}
                                                @if($s->is_instructing == 1)<span class="badge bg-danger">Instructing</span>@endif
                                                @if($s->is_student == 1)<span class="badge bg-warning">Training</span>@endif
                                                @if($s->is_ctp == 1)<span class="badge bg-primary">CTP</span>@endif
                                            </th>
                                            <th>{{\Carbon\Carbon::parse($s->session_start)->format('m/d/Y \a\t Hi\Z')}}</th>
                                            <th>
                                                @if($s->session_end === null)
                                                Currently Connected
                                                @else
                                                {{\Carbon\Carbon::parse($s->session_end)->format('m/d/Y \a\t Hi\Z')}}
                                                @endif
                                            </th>
                                            @if($s->duration < 0.5)
                                                <td>
                                                    {{ str_pad(round(($s->duration - floor($s->duration)) * 60), 2, '0', STR_PAD_LEFT) }}m <i style="color: red;" class="fas fa-times"></i>
                                                </td>
                                            @else
                                                @if($s->duration < 1)
                                                    <td>
                                                        {{ str_pad(round(($s->duration - floor($s->duration)) * 60), 2, '0', STR_PAD_LEFT) }}m
                                                    </td>
                                                @else
                                                    <td>
                                                        {{ floor($s->duration) }}h {{ str_pad(round(($s->duration - floor($s->duration)) * 60), 2, '0', STR_PAD_LEFT) }}m
                                                    </td>
                                                @endif
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <h5 class="font-weight-bold blue-text mt-4 pb-2">Notes for Table</h5>
                            <li>Connections of less than 30 minutes will show with a <i style="color: red;" class="fas fa-times"></i> within the time collum.</li>
                            <li>Connections conducted while a Student will appear with <span class="badge bg-warning">Training</span> and will not count towards your currency.
                        @else
                            <p class="mt-0">You have not recorded any hours so far this year. Connect to the network in order to record a session!</p>
                        @endif
                    @else
                    {{-- User is not in the RosterMember DB --}}
                        

                            {{-- Check if they are an ExternalController --}}
                            @if($externalController !== null)
                            <h3>
                                <span style='font-weight: 400' class='badge rounded p-2 green text-white shadow-none'>
                                    <i class="fas fa-check mr-2"></i>&nbsp;Oceanic Certified by
                                        @if($externalController->visiting_origin == "eggx")<span class="badge bg-danger">EGGX</span>
                                        @elseif($externalController->visiting_origin == "zny")<span class="badge bg-secondary">KZNY</span>@endif
                                </span>

                                <span style='font-weight: 400' class='badge rounded p-2 green text-white shadow-none'>
                                    <i class="fas fa-check mr-2"></i>&nbsp;Active
                                </span>
                            </h3><br>

                            <h3 class="font-weight-bold blue-text pb-2">Partnership Controllers, Please Note</h3>
                                <li>Your certification status on this page is managed by your endorsement operator listed above.</li>
                                <li>Your Activity Requirements within Gander are assumed correct. Should your Certification Status be removed by your home division due to their policy, your access on Gander Oceanic will be removed within 24 Hours.</li>
                                <li>You are authorised to open any EGGX_CTR, CZQO_CTR, NY_FSS or NAT_FSS Position while holding this endorsement.</li>
                                <li>Any questions regarding Activity Requirements should be directed towards the <a href="{{ route('my.feedback.new.write', ['operations']) }}">Gander Oceanic Operations Staff Team</a> who will assist you with your query.</li>

                            @else
                            <h3>
                            {{-- User is not either a Local, or External Certified Controller --}}
                                <span style='font-weight: 400' class='badge rounded p-2 red text-white shadow-none'>
                                    <i class="fas fa-times mr-2"></i>&nbsp;No Certification
                                </span>
                            </h3>
                            @endif
                        @if ($user->rating_id >= 5 &&
                                $user->can('start applications') && $externalController == null)
                            <div class="list-group-item rounded p-4 my-3 z-depth-1 shadow-none w-50 mt-4">
                                <h4 class="blue-text"><i style="margin-right: 10px;">👋</i>
                                    Apply for Gander Oceanic Certification</h4>
                                <p style="font-size: 1.1em;">Interested in joining our team of oceanic controllers?</p>
                                <p style="font-size: 1.2em;" class="mt-3 mb-0">
                                    <a class="font-weight-bold text-body"
                                        href="{{ route('training.applications.apply') }}">Start your application
                                        &nbsp;&nbsp;<i class="fas fa-arrow-right blue-text"></i></a>
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
                                            <a href="{{ route('training.admin.dashboard') }}"
                                                class="waves-effect list-group-item list-group-item-action">
                                                <i style="margin-right: 10px;" class="fas fa-tachometer-alt fa-fw"></i>Dashboard
                                            </a>
                                            @can('view roster admin')
                                                <a href="{{ route('training.admin.roster') }}"
                                                    class="waves-effect list-group-item list-group-item-action">
                                                    <i style="margin-right: 10px;" class="fas fa-users fa-fw"></i>Roster
                                                </a>
                                                {{-- <a href="{{ route('training.admin.solocertifications') }}"
                                                    class="waves-effect list-group-item list-group-item-action">
                                                    <i style="margin-right: 10px;" class="fas fa-certificate fa-fw"></i>Solo
                                                    Certifications
                                                </a> --}}
                                            @endcan
                                            @can('view applications')
                                                <a href="{{ route('training.admin.applications') }}"
                                                    class="waves-effect list-group-item list-group-item-action">
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
                                                <a href="{{ route('events.admin.index') }}"
                                                    class="waves-effect list-group-item list-group-item-action">
                                                    <i style="margin-right: 10px;" class="fas fa-calendar"></i>
                                                    Events
                                                </a>
                                            @endcan
                                            @can('view articles')
                                                <a href="{{ route('news.index') }}"
                                                    class="waves-effect list-group-item list-group-item-action">
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
                                                <a href="{{ route('publications.policies') }}"
                                                    class="waves-effect list-group-item list-group-item-action">
                                                    <i style="margin-right: 10px;" class="fas fa-file-alt fa-fw"></i>Policies
                                                </a>
                                            @endcan
                                            @can('edit atc resources')
                                                <a href="{{ route('publications.atc-resources') }}"
                                                    class="waves-effect list-group-item list-group-item-action">
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
                                                <a href="{{ route('community.users.index') }}"
                                                    class="waves-effect list-group-item list-group-item-action">
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
                                                <a href="{{ route('network.index') }}"
                                                    class="waves-effect list-group-item list-group-item-action">
                                                    <i style="margin-right: 10px;" class="fas fa-wifi fa-fw"></i>View Network Data
                                                </a>
                                            @endcan

                                            {{-- @can('view users')
                                            <a href="{{ route('network.index') }}"
                                                    class="waves-effect list-group-item list-group-item-action">
                                                    <i style="margin-right: 10px;" class="fas fa-notebook fa-fw"></i>See Connection Information
                                                </a>
                                            @endcan --}}
                                        </div>
                                    </div>
                                </div>
                            @endcan
                            @can('edit settings')
                                <div class="col-md-4">
                                    <div class="card mb-3 shadow-none">
                                        <h4 class="blue-text mb-3">Admin</h4>
                                        <div class="list-group z-depth-1">
                                            <a href="{{ route('settings.index') }}"
                                                class="waves-effect list-group-item list-group-item-action">
                                                <i style="margin-right: 10px;" class="fas fa-cog fa-fw"></i>Site Settings
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endcan
                        </div>
                    @endhasanyrole
                </div>
                <br />
            </div>
        </div>
        <small class="text-muted">Quote of the day provided by <a
                href="https://theysaidso.com">https://theysaidso.com</a></small>
    </div>

    <!-- Intro js -->
    <script>
        function startTutorial() {
            var intro = introJs();
            intro.setOptions({
                steps: [{
                        intro: "Hi {{ $user->fullName('F') }}! Welcome to the tutorial for the Gander Oceanic website. We're excited to have you join us. On the dashboard, you can get a glance at your status within our OCA and access various functions. To begin, click the 'Next' button below."
                    },
                    {
                        element: "#atcResources",
                        intro: "Here you can view resources for Gander controllers, including sector files, documents, and the spreadsheet. If you're not a rostered controller yet, you may not be able to see everything. For pilot resources, click the Pilots tab on the navbar above."
                    },
                    {
                        element: '#yourData',
                        intro: "Here you can get an overview of your Gander Oceanic profile. Change your display name by clicking '{{ $user->fullName('FLC') }}l and following the prompts. You can link your Discord account here and access to our Discord community, and you can even set an avatar for yourself. The buttons below allow you to change settings such as your biography, preferences, and manage your data.",
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
    <div class="modal fade" id="changeAvatar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="border-bottom: none">
                    <h5 class="modal-title" id="exampleModalLongTitle">Change your avatar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('users.changeavatar') }}" enctype="multipart/form-data"
                    class="" id="">
                    <div class="modal-body">
                        <p>Your avatar must comply with the VATSIM Code of Conduct.</p>
                        @csrf
                        <div class="input-group pb-3">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="file">
                                <label class="custom-file-label">Choose image file</label>
                            </div>
                        </div>
                        @if ($user->hasDiscord())
                            or use your Discord avatar (refreshes every 6 hours)<br />
                            <a href="{{ route('users.changeavatar.discord') }}" class="btn bg-czqo-blue-light mt-3">Use
                                Discord Avatar</a>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('users.resetavatar') }}" role="button" class="btn btn-light">Reset Avatar</a>
                        <input type="submit" class="btn btn-success" value="Upload">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--End change avatar modal-->

    <!--Biography modal-->
    <div class="modal fade" id="bioModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content p-2">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit your biography</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('me.editbio') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <p>Your biography must comply with the VATSIM Code of Conduct. Markdown styling is disabled.</p>
                        <textarea id="contentMD" name="bio" style="display:none;"></textarea>
                        <script>
                            var simplemde = new EasyMDE({
                                autofocus: true,
                                autoRefresh: true,
                                element: document.getElementById("contentMD"),
                                toolbar: false,
                                initialValue: '{{ $user->bio }}'
                            });
                            simplemde.value('{{ $user->bio }}')
                        </script>
                        <p>Wonder what the purpose of a biography is? <a
                                href="https://knowledgebase.ganderoceanic.ca/en/website/myczqo" target="_blank">Find out
                                here.</a></p>
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
    <div class="modal fade" id="changeDisplayNameModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Change your display name</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('users.changedisplayname') }}">
                    <div class="modal-body">
                        @csrf
                        <p>Your display name will display everywhere on Gander Oceanic, including the controller roster. It
                            is advised to use the same display name that you would use on the VATSIM network. All display
                            names must comply with section A4 of the VATSIM Code of Conduct.</p>
                        <div class="form-group">
                            <div class="md-form">
                                <input type="text" class="form-control" value="{{ $user->display_fname }}"
                                    name="display_fname" id="input_display_fname">
                                <label for="input_display_fname" class="active">Display first name</label>
                            </div>
                            <a class="btn btn-light btn-sm" role="button" onclick="resetToCertFirstName()"><span
                                    style="color: #000">Reset to your CERT first name</span></a>
                            <script>
                                function resetToCertFirstName() {
                                    $("#input_display_fname").val("{{ $user->fname }}")
                                }
                            </script>
                        </div>
                        <div class="form-group">
                            <label class="text-muted">Format</label>
                            <select name="format" class="custom-select">
                                <option value="showall">First name, last name, and CID
                                    ({{ $user->display_fname }} {{ $user->lname }} {{ Auth::id() }})
                                </option>
                                <option value="showfirstcid">First name and CID ({{ $user->display_fname }}
                                    {{ Auth::id() }})</option>
                                <option value="showcid">CID only ({{ Auth::id() }})</option>
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
    <div class="modal fade" id="discordTopModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            @if (!$user->hasDiscord())
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
                                            <img style="height: 40px; margin-right: 20px;"
                                                src="{{asset('assets/resources/media/img/brand/sqr/ZQO_SQ_TSPBLUE.png')}}"
                                                alt="">
                                            <p class="font-weight-bold"
                                                style="width: 75%; text-align:left; font-size: 1.1em;">Join the Gander
                                                Oceanic Discord community</p>
                                        </div>
                                    </div>
                                </li>
                                <li class="w-100">
                                    <div class="grey lighten-3 p-4" style="border-radius: 20px;">
                                        <div class="d-flex flex-row">
                                            <i style="font-size:35px; margin-right:20px;"
                                                class="fas fa-user-circle blue-text"></i>
                                            <p class="font-weight-bold"
                                                style="width: 75%; text-align:left; font-size: 1.1em;">Use your Discord
                                                avatar as your CZQO website avatar</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <a href="{{ route('me.discord.link') }}" class="class btn btn-primary mt-3">Link Your Discord
                                Account</a>
                            <p class="text-muted text-center mt-2">You will be redirected to Discord to link your account.
                                Information collected is shown on the Discord authorisation screen. Read our privacy policy
                                for details.</p>
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
                        <a href="{{ route('me.discord.unlink') }}" class="class btn btn-danger">Unlink</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!--End Discord modal-->

    @if (!$user->member_of_czqo)
        <!--Join guild modal-->
        <div class="modal fade" id="joinDiscordServerModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Join the Gander Oceanic Discord server</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Joining the Gander Oceanic Discord server allows you to join the Gander Oceanic controller and
                            pilot community.</p>
                        <h5>Rules</h5>
                        <ul>
                            <li>1. The VATSIM Code of Conduct applies.</li>
                            <li>2. Always show respect and common decency to fellow members.</li>
                            <li>3. Do not send server invites to servers unrelated to VATSIM without staff permission. Do
                                not send ANY invites via DMs unless asked to.
                            </li>
                            <li>4. Do not send spam in the server, including images, text, or emotes.</li>
                        </ul>
                        <p>Clicking the 'Join' button will redirect you to Discord. We require the Join Server permission to
                            add your Discord account to the server.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Dismiss</button>
                        <a role="button" type="submit" href="{{ route('me.discord.join') }}"
                            class="btn btn-primary">Join</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!--End join guild modal-->


    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.13.6/sorting/datetime-moment.js"></script>    
    <script>
        $(document).ready(function() {
            $.fn.dataTable.moment('MM/DD/YYYY [at] HHmm[Z]'); // Ensure correct date format parsing
    
            $('#dataTable').DataTable({
                "order": [[1, "desc"]] // Sort by the second column (Logon) in descending order
            });
        });
    </script>

@stop
