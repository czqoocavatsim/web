{{-- Single homepage menu (desktop and mobile). The burger opens the existing mobile menu modal. --}}
<nav class="zh-nav">
    <a class="zh-brand" href="{{ route('index') }}">
        <img src="{{ asset('assets/resources/media/img/brand/bnr/ZQO_BNR_TSPBLUE.png') }}" alt="Gander Oceanic">
    </a>
    <ul class="zh-links">
        <li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">About</a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('about.who-we-are') }}">Who We Are</a>
                <a class="dropdown-item" href="{{ url('/about/staff') }}">Staff</a>
                <a class="dropdown-item" href="{{ route('policies') }}">Policies</a>
            </div>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pilots</a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ url('/pilots/oceanic-clearance') }}">Oceanic Clearance Generator</a>
                <a class="dropdown-item" href="{{ url('/pilots/position-report') }}">Position Report Generator</a>
                <a class="dropdown-item" href="{{ url('/pilots/tracks') }}">Current NAT Tracks</a>
                <a class="dropdown-item" href="{{ url('/pilots/tracks/event') }}">Event NAT Tracks</a>
                <a class="dropdown-item" href="https://nattrak.vatsim.net" target="_blank">natTRAK</a>
                <a class="dropdown-item" href="{{ url('/map') }}">Map</a>
            </div>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">ATC</a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('atcresources.index') }}">ATC Resources</a>
                <a class="dropdown-item" href="{{ route('trainingcalendar.index') }}">Training Calendar</a>
                <a class="dropdown-item" href="https://knowledgebase.ganderoceanic.ca">Knowledge Base</a>
            </div>
        </li>
        <li><a href="{{ route('events.index') }}">Events</a></li>
        <li><a href="{{ route('news') }}">News</a></li>
        <li><a href="{{ route('roster.public') }}">Roster</a></li>
        @auth
            <li><a href="{{ route('my.index') }}">myCZQO</a></li>
        @endauth
    </ul>
    <div class="zh-account">
        @auth
            <span class="zh-user"><img src="{{ Auth::user()->avatar() }}" alt="">{{ Auth::user()->fullName('FL') }}</span>
            @hasanyrole('Administrator|Senior Staff|Marketing Team|Web Team|Instructor')
                <a href="{{ route('admin.index') }}" class="zh-admin" aria-label="Admin"><i class="fas fa-shield-alt"></i></a>
            @endhasanyrole
        @else
            <a href="{{ route('auth.connect.login') }}" class="zh-login"><i class="fas fa-sign-in-alt"></i>&nbsp;Log in</a>
        @endauth
    </div>
    <button class="zh-burger" type="button" data-toggle="modal" data-target="#mobileNavBarModal" aria-label="Menu">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
    </button>
</nav>
