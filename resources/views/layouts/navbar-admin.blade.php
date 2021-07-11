<style>
@import url('https://fonts.googleapis.com/css2?family=Rubik&display=swap');
/* * {
    font-family: 'Rubik', 'Lato', 'Segoe UI', 'Arial', sans-serif;
} */
</style>
<div class="container pt-3 pb-1">
    <a class="navbar-brand" href="{{route('index')}}"><img style="height: 50px; width:auto;" id="czqoHeaderImg" src="https://cdn.ganderoceanic.ca/resources/media/img/brand/sqr/ZQO_SQ_TSPBLUE.png" alt=""></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto d-flex flex-row align-items-center">
            @hasanyrole('Senior Staff|Administrator')
            <li class="nav-item mr-2 {{ request()->routeIs('publications*') ? 'active' : ''}}">
                <a class="nav-link" href="{{route('publications.policies')}}">
                    Publications
                </a>
            </li>
            <li class="nav-item mr-2 {{ request()->routeIs('community*') ? 'active' : ''}}">
                <a class="nav-link" href="{{route('community.users.index')}}">
                    Community
                </a>
            </li>
            @endhasanyrole
            @hasanyrole('Senior Staff|Administrator|Instructor')
            <li class="nav-item mr-2 {{ request()->routeIs('training.admin*') ? 'active' : ''}}">
                <a class="nav-link" href="{{route('training.admin.dashboard')}}">
                    Training
                </a>
            </li>
            @endhasanyrole
            @hasanyrole('Senior Staff|Administrator|Marketing Team')
            <li class="nav-item mr-2 {{ request()->routeIs('news*') ? 'active' : ''}}">
                <a class="nav-link" href="{{route('news.index')}}">
                    News
                </a>
            </li>
            <li class="nav-item mr-2 {{ request()->routeIs('events.admin*') ? 'active' : ''}}">
                <a class="nav-link" href="{{route('events.admin.index')}}">
                    Events
                </a>
            </li>
            @endhasanyrole
            @hasanyrole('Administrator|Web Team')
            <li class="nav-item mr-2 {{ request()->routeIs('settings*') ? 'active' : ''}}">
                <a class="nav-link" href="{{route('settings.index')}}">
                    Settings
                </a>
            </li>
            @endhasanyrole
            <li class="nav-item mr-4">
                <a class="nav-link" href="{{route('my.index')}}">
                    myCZQO
                </a>
            </li>
            <li class="nav-item">
                <div class="d-flex flex-row align-items-center">
                    <img src="{{Auth::user()->avatar()}}" style="height: 27px; width:27px;margin-right: 13px; margin-bottom: 3px; border-radius: 50%;">
                    <div class="d-flex flex-column">
                        <span class="fw-600">{{Auth::user()->fullName('F')}}</span>
                        <span class="text-muted">
                            @if(Auth::user()->staffProfile)
                                {{Auth::user()->staffProfile->position}}
                            @elseif (Auth::user()->instructorProfile)
                                {{Auth::user()->instructorProfile->staffPageTagline()}}
                            @else
                                {{Auth::user()->highestRole()->name}}
                            @endif
                        </span>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
