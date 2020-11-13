<div class="container py-1">
    <a class="navbar-brand" href="{{route('index')}}"><img style="height: 40px; width:auto;" id="czqoHeaderImg" src="https://cdn.ganderoceanic.com/resources/media/img/brand/bnr/ZQO_BNR_TSPWHITE.png" alt=""></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item dropdown {{ Request::is('about/*') || Request::is('about') ? 'active' : ''}}">
                <a class="nav-link dropdown-toggle" style="cursor:pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">About</a>
                <div class="dropdown-menu" aria-labelledby="dropdown01">
                    <a href="{{route('about.who-we-are')}}" class="dropdown-item {{ Request::is('about/who-we-are') ? 'active white-text' : '' }}">Who We Are</a>
                    <a class="dropdown-item {{ Request::is('about/staff') ? 'active white-text' : '' }}" href="{{url ('/about/staff')}}" aria-expanded="false">Staff</a>
                </div>
            </li>
            <li class="nav-item dropdown {{ Request::is('roster') || Request::is('roster/solo-certs') ? 'active' : ''}}">
                <a class="nav-link dropdown-toggle" style="cursor:pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Roster</a>
                <div class="dropdown-menu" aria-labelledby="dropdown01">
                    <a class="dropdown-item {{ Request::is('roster') ? 'active white-text' : '' }}" href="{{route('roster.public')}}" aria-expanded="false">Controller Roster</a>
                    <a class="dropdown-item {{ Request::is('roster/solo-certs') ? 'active white-text' : '' }}"" href="{{route('solocertifications.public')}}" aria-expanded="false">Solo Certifications</a>
                </div>
            </li>
            <li class="nav-item {{ Request::is('news') ? 'active white-text' : '' }} {{ Request::is('news/*') ? 'active white-text' : '' }}">
                <a class="nav-link" href="{{route('news')}}">
                    News
                </a>
            </li>
            <li class="nav-item {{ Request::is('events/*') || Request::is('events') ? 'active' : '' }}">
                <a href="{{route('events.index')}}" class="nav-link">Events</a>
            </li>
            <li class="nav-item dropdown {{ Request::is('dashboard/application') || Request::is('dashboard/application/*') || Request::is('atcresources') ? 'active' : '' }}">
                <a class="nav-link dropdown-toggle" style="cursor:pointer" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">ATC</a>
                <div class="dropdown-menu" aria-labelledby="dropdown01">
                    <a class="dropdown-item {{ Request::is('atcresources') ? 'active white-text' : '' }}" href="{{route('atcresources.index')}}">ATC Resources</a>
                    <a href="{{URL('/eurosounds')}}" class="dropdown-item {{ Request::is('eurosounds') ? 'active white-text' : '' }}">EuroSounds</a>
                </div>
            </li>
            <li class="nav-item dropdown {{ Request::is('pilots/oceanic-clearance') || Request::is('pilots/position-report') || Request::is('pilots/vatsim-resources') || Request::is('pilots/tutorial') || Request::is('pilots/tracks') ? 'active' : '' }}">
                <a class="nav-link dropdown-toggle" style="cursor:pointer" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pilots</a>
                <div class="dropdown-menu" aria-labelledby="dropdown01">
                    <a class="dropdown-item {{ Request::is('pilots/oceanic-clearance') ? 'active white-text' : '' }}" href="{{url('/pilots/oceanic-clearance')}}">Oceanic Clearance Generator</a>
                    <a class="dropdown-item {{ Request::is('pilots/position-report') ? 'active white-text' : '' }}" href="{{url('/pilots/position-report')}}">Position Report Generator</a>
                    <a class="dropdown-item {{ Request::is('pilots/tracks') ? 'active white-text' : ''}}" href="{{url('/pilots/tracks')}}">Current NAT Tracks</a>
                    <a class="dropdown-item" href="https://www.vatsim.net/pilots/resources" target="_blank">VATSIM Resources</a>
                    <a class="dropdown-item" href="https://nattrak.vatsim.net" target="_blank">natTRAK</a>
                    <a class="dropdown-item" href="{{url('/map')}}">Map</a>
                </div>
            </li>
            <li class="nav-item dropdown {{ Request::is('policies') || Request::is('meetingminutes') ? 'active' : ''}}">
                <a class="nav-link dropdown-toggle" style="cursor:pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Publications</a>
                <div class="dropdown-menu" aria-labelledby="dropdown01">
                    <a class="dropdown-item {{ Request::is('policies') ? 'active white-text' : '' }}" href="{{route('policies')}}">Policies</a>
                    {{-- <a class="dropdown-item {{ Request::is('meetingminutes') ? 'active white-text' : '' }}" href="{{route('meetingminutes')}}">Meeting Minutes</a> --}}
                    <a href="https://knowledgebase.ganderoceanic.com" class="dropdown-item">Knowledge Base</a>
                </div>
            </li>
            <li class="nav-item  {{ Request::is('feedback') ? 'active' : '' }}">
            <a class="nav-link" href="{{route('feedback.create')}}" aria-expanded="false">Submit Feedback</a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto nav-flex-icons">
            @unless (Auth::check())
            <li class="nav-item d-flex align-items-center">
                {{-- <a href="{{route('auth.sso.login')}}" class="nav-link waves-effect waves-light">
                    <i class="fas fa-key"></i>&nbsp;Login
                </a> --}}
                <a href="{{route('auth.connect.login')}}" class="nav-link waves-effect waves-light">
                    <i class="fas fa-key"></i>&nbsp;Login with VATSIM
                </a>
            </li>
            @endunless
            @auth
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-333" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="{{Auth::user()->avatar()}}" style="height: 27px; width: 27px; margin-right: 7px; margin-bottom: 3px; border-radius: 50%;">&nbsp;<span class="font-weight-bold">{{Auth::user()->fullName("F")}}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-default py-0" aria-labelledby="navbarDropdownMenuLink-333">
                    <a class="dropdown-item {{ Request::is('my') || Request::is('my/*') ? 'active white-text' : '' }}" href="{{route('my.index')}}">
                        <img style="height: 25px; margin-left:-3px;" src="{{ Request::is('my') || Request::is('my/*') ? 'https://cdn.ganderoceanic.com/resources/media/img/brand/sqr/ZQO_SQ_TSPWHITE.png' : 'https://cdn.ganderoceanic.com/resources/media/img/brand/sqr/ZQO_SQ_TSPBLUE.png' }}" alt=""><span class="float-right">myCZQO</span>
                    </a>
                    <a class="dropdown-item red-text" href="{{route('auth.logout')}}">
                        <i class="fa fa-key"></i><span class="float-right">Logout</span>
                    </a>
                </div>
            </li>
            @endauth
            <li class="nav-item d-flex align-items-center">
                <a href="https://twitter.com/ganderocavatsim" class="nav-link waves-effect waves-light">
                    <i style="font-size: 1.7em;" class="fab fa-twitter"></i>
                </a>
            </li>
            <li class="nav-item d-flex align-items-center">
                <a href="https://www.facebook.com/ganderocavatsim" class="nav-link waves-effect waves-light">
                    <i style="font-size: 1.7em;" class="fab fa-facebook"></i>
                </a>
            </li>
            <li class="nav-item d-flex align-items-center">
                <a class="nav-link waves-effect waves-light" data-toggle="modal" data-target="#discordTopModal">
                    <i style="height: 22px; font-size: 1.7em;width: 28px;padding-left: 5px;padding-top: 2px;" class="fab fa-discord"></i>
                </a>
            </li>
        </ul>
    </div>
</div>
