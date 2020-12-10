@php if (!isset($logo)) $logo = 'white' @endphp
<div class="container py-2">
    @if ($logo == 'white')
    <a class="navbar-brand" href="{{route('index')}}"><img style="height: 50px; width:auto;" id="czqoHeaderImg" src="https://ams3.digitaloceanspaces.com/ganderoceanicoca/resources/media/img/brand/bnr/ZQO_XMAS_BNR_TSPWHITE.png" alt=""></a>
    @else
    <a class="navbar-brand" href="{{route('index')}}"><img style="height: 45px; width:auto;" id="czqoHeaderImg" src="https://ams3.digitaloceanspaces.com/ganderoceanicoca/resources/media/img/brand/bnr/ZQO_BNR_TSPBLUE.png" alt=""></a>
    @endif
    <button class="navbar-toggler" type="button" data-toggle="modal" data-target="#mobileNavBarModal">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto mr-3 d-none d-md-flex">

            <li class="nav-item dropdown {{ Request::is('about/*') || Request::is('about') ? 'active' : ''}}">
                <a class="nav-link dropdown-toggle" style="cursor:pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">About</a>
                <div class="dropdown-menu" aria-labelledby="dropdown01">
                    <a href="{{route('about.who-we-are')}}" class="dropdown-item {{ Request::is('about/who-we-are') ? 'active white-text' : '' }}">Who We Are</a>
                    <a class="dropdown-item {{ Request::is('about/staff') ? 'active white-text' : '' }}" href="{{url ('/about/staff')}}" aria-expanded="false">Staff</a>
                    <a class="dropdown-item {{ Request::is('policies') ? 'active white-text' : '' }}" href="{{route('policies')}}">Policies</a>

                </div>
            </li>
            <li class="nav-item dropdown {{ Request::is('roster') || Request::is('roster/solo-certs') ? 'active' : ''}}">
                <a class="nav-link dropdown-toggle" style="cursor:pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Roster</a>
                <div class="dropdown-menu" aria-labelledby="dropdown01">
                    <a class="dropdown-item {{ Request::is('roster') ? 'active white-text' : '' }}" href="{{route('roster.public')}}" aria-expanded="false">Controller Roster</a>
                    <a class="dropdown-item {{ Request::is('roster/solo-certs') ? 'active white-text' : '' }}" href="{{route('solocertifications.public')}}" aria-expanded="false">Solo Certifications</a>
                </div>
            </li>
            <li class="nav-item {{ Request::is('news') ? 'active white-text' : '' }} {{ Request::is('news/*') ? 'active white-text' : '' }}">
                <a class="nav-link " href="{{route('news')}}">
                    News
                </a>
            </li>
            <li class="nav-item {{ Request::is('events/*') || Request::is('events') ? 'active' : '' }}">
                <a href="{{route('events.index')}}" class="nav-link ">Events</a>
            </li>
            <li class="nav-item dropdown {{ Request::is('dashboard/application') || Request::is('dashboard/application/*') || Request::is('atcresources') ? 'active' : '' }}">
                <a class="nav-link dropdown-toggle" style="cursor:pointer" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">ATC</a>
                <div class="dropdown-menu" aria-labelledby="dropdown01">
                    <a class="dropdown-item {{ Request::is('atcresources') ? 'active white-text' : '' }}" href="{{route('atcresources.index')}}">ATC Resources</a>
                    <a href="{{URL('/eurosounds')}}" class="dropdown-item {{ Request::is('eurosounds') ? 'active white-text' : '' }}">EuroSounds</a>
                    <a href="https://knowledgebase.ganderoceanic.com" class="dropdown-item">Knowledge Base</a>
                </div>
            </li>
            <li class="nav-item dropdown {{ Request::is('pilots/oceanic-clearance') || Request::is('pilots/position-report') || Request::is('pilots/vatsim-resources') || Request::is('pilots/tutorial') || Request::is('pilots/tracks') ? 'active' : '' }}">
                <a class="nav-link dropdown-toggle" style="cursor:pointer" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pilots</a>
                <div class="dropdown-menu" aria-labelledby="dropdown01">
                    <a class="dropdown-item {{ Request::is('pilots/oceanic-clearance') ? 'active white-text' : '' }}" href="{{url('/pilots/oceanic-clearance')}}">Oceanic Clearance Generator</a>
                    <a class="dropdown-item {{ Request::is('pilots/position-report') ? 'active white-text' : '' }}" href="{{url('/pilots/position-report')}}">Position Report Generator</a>
                    <a class="dropdown-item {{ Request::is('pilots/tracks') ? 'active white-text' : ''}}" href="{{url('/pilots/tracks')}}">Current NAT Tracks</a>
                    <a class="dropdown-item {{ Request::is('pilots/tracks/event') ? 'active white-text' : ''}}" href="{{url('/pilots/tracks/event')}}">Event NAT Tracks</a>
                    <a class="dropdown-item" href="https://www.vatsim.net/pilots/resources" target="_blank">VATSIM Resources</a>
                    <a class="dropdown-item" href="https://nattrak.vatsim.net" target="_blank">natTRAK</a>
                    <a class="dropdown-item" href="{{url('/map')}}">Map</a>
                </div>
            </li>
            @auth
            <li class="nav-item {{ Request::is('my') || Request::is('my/*') ? 'active' : ''}}">
                <a class="nav-link" href="{{route('my.index')}}">
                    myCZQO
                </a>
            </li>
            @endauth
        </ul>
        <ul class="navbar-nav nav-flex-icons">
            @unless (Auth::check())
            <li class="nav-item d-flex align-items-center">
                <a href="{{route('auth.connect.login')}}" class="nav-link waves-effect waves-light">
                    <i class="fas fa-sign-in-alt"></i>&nbsp;Log In
                </a>
            </li>
            @endunless
            @auth
            <li class="nav-item" id="accountDropdown">
                <a class="nav-link">
                    <img src="{{Auth::user()->avatar()}}" style="height: 32px; width: 32px; margin-right: 10px; margin-bottom: 3px; border-radius: 50%;">&nbsp;<span class="fw-800">{{Auth::user()->fullName("FL")}}</span>
                </a>
            </li>
            @endauth
            @hasanyrole('Administrator|Senior Staff|Marketing Team|Web Team|Instructor')

            <li class="nav-item d-flex align-items-center {{ Request::is('admin') || Request::is('admin/*') ? 'active' : ''}}">
                <a href="{{route('admin.index')}}" class="nav-link waves-effect waves-light">
                    <i class="fas fa-shield-alt"></i>
                </a>
            </li>
            @endhasanyrole
        </ul>
    </div>
</div>

<!-- Frame Modal Bottom -->
