<div class="container pt-3 pb-1">
    <a class="navbar-brand" href="{{route('index')}}"><img style="height: 50px; width:auto;" id="czqoHeaderImg" src="https://cdn.ganderoceanic.com/resources/media/img/brand/sqr/ZQO_SQ_TSPBLUE.png" alt=""></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto d-flex flex-row align-items-center">
            <li class="nav-item mr-2">
                <a class="nav-link" href="{{route('publications.policies')}}">
                    Publications
                </a>
            </li>
            <li class="nav-item mr-2">
                <a class="nav-link" href="{{route('community.users.index')}}">
                    Community
                </a>
            </li>
            <li class="nav-item mr-2">
                <a class="nav-link" href="{{route('training.admin.dashboard')}}">
                    Training
                </a>
            </li>
            <li class="nav-item mr-2">
                <a class="nav-link" href="{{route('news.index')}}">
                    News
                </a>
            </li>
            <li class="nav-item mr-2">
                <a class="nav-link" href="{{route('events.admin.index')}}">
                    Events
                </a>
            </li>
            <li class="nav-item mr-2">
                <a class="nav-link" href="{{route('settings.index')}}">
                    Settings
                </a>
            </li>
            <li class="nav-item mr-2">
                <a class="nav-link" href="{{route('my.index')}}">
                    myCZQO
                </a>
            </li>
            <li class="nav-item">
                <div class="d-flex flex-row align-items-center">
                    <img src="{{Auth::user()->avatar()}}" style="height: 27px; width:27px;margin-right: 13px; margin-bottom: 3px; border-radius: 50%;">
                    <div class="d-flex flex-column">
                        <span>{{Auth::user()->fullName('F')}}</span>
                        <span class="text-muted">
                            @if(!Auth::user()->staffProfile)
                                {{Auth::user()->staffProfile->position}}
                            @elseif (Auth::user()->instructorProfile)
                                {{Auth::user()->instructorProfile->staffPageTagline()}}
                            @endif
                        </span>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
