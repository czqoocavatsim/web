@extends('layouts.master', ['solidNavBar' => false])
@section('title', 'Home - ')
@section('description', 'Cool, calm and collected oceanic control services in the North Atlantic on VATSIM.')

@section('content')
    @if(!$ctpMode)
    <div data-jarallax data-speed="0.2" class="jarallax" style="height: calc(100vh)">
        <div class="mask flex-center flex-column" style="position:absolute; top:0; left:0; z-index: 1; height: 100%; width: 100%; background: linear-gradient(40deg,rgba(3, 149, 233, 0.7),rgba(48,63,159,.4))!important;">
            <div class="container">
                <div class="py-5">
                    <h1 class="h1 my-4 py-2 font-weight-bold" style="font-size: 3em; width: 75%; color: #fff;">Cool, calm and collected oceanic control services over the North Atlantic.</h1>
                    <h4>
                        <a href="#blueBannerMid" id="discoverMore" class="white-text" style="transition:fade 0.4s;">
                        @if(Auth::check())
                            Welcome back, {{Auth::user()->fullName('F')}}!
                        @else
                            Find out more&nbsp;&nbsp;<i class="fas fa-arrow-down"></i>
                        @endif
                        </a>
                    </h4>
                </div>
            </div>
            @if($nextEvent)
            <div class="container white-text">
                <p style="font-size: 1.4em;" class="font-weight-bold">
                    <a href="{{route('events.view', $nextEvent->slug)}}" class="white-text">
                        <i class="fa fa-calendar"></i>&nbsp;&nbsp;Upcoming: &nbsp;{{$nextEvent->name}}
                    </a>
                </p>
                <p style="font-size: 1.2em;">{{$nextEvent->start_timestamp_pretty()}}</p>
            </div>
            @endif
        </div>
    </div>
    @else
    <iframe style="height: 100vh; margin-bottom: 0px;"
    src="https://player.twitch.tv/?channel=czqo_vatsim&parent=ganderoceanic.com"
    height="100vh"
    width="100%"
    frameborder="0"
    scrolling="no"
    allowfullscreen="true">
    </iframe>
    @endif
    <div class="container-fluid blue" id="blueBannerMid">
            <div class="row">
                <div class="col-md-6 pl-0 pr-0">
                    @if(!$news)
                        <span class="white-text">No news found.</span>
                    @else
                    <div class="view" style="height: 330px !important; @if($news->image) background-image:url({{$news->image}}); background-size: cover; background-position-x: center; @else background: var(--czqo-blue); @endif">
                        <div class="mask rgba-stylish-light flex-left p-4 justify-content-end d-flex flex-column h-100">
                            <div class="container">
                                <h1 class="font-weight-bold white-text">
                                    <a href="{{route('news.articlepublic', $news->slug)}}" class="white-text">
                                        {{$news->title}}
                                    </a>
                                </h1>
                                <p class="white-text" style="font-size: 1.3em;">
                                    {{$news->summary}}
                                </p>
                                <a href="{{route('news')}}" class="white-text" style="font-size: 1.2em;">All Articles <i class="fas fa-arrow-right"></i> </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="col-md-6 d-none d-md-block">
                    <div class="container py-4">
                        <div class="d-flex flex-row-justify-content-between align-items-center">
                            <h2 class="white-text font-weight-bold">Online Oceanic Controllers</h2>
                            <a href="{{route('map')}}" class="float-right ml-auto mr-0 white-text" style="font-size: 1.2em;">View map&nbsp;&nbsp;<i class="fas fa-map"></i></a>
                        </div>
                        <ul class="list-unstyled ml-0 mt-3 p-0 onlineControllers">
                            @if(count($controllers) < 1)
                            <li class="mb-2">
                                <div class="d-flex flex-row justify-content-between align-items-center mb-1">
                                    <h4 class="m-0 white-text"><i class="fas fa-sad-tear" style="margin-right: 1rem;"></i>No controllers online</h4>
                                </div>
                            </li>
                            @endif
                            @foreach($controllers as $controller)
                            <li>
                                <div class="white-text d-flex flex-row justify-content-between align-items-center">
                                    <h4 class="font-weight-bold m-0">{{$controller['callsign']}}</h4>
                                    <div style="font-size: 1.1em;">
                                        @if ($rosterMember = App\Models\Roster\RosterMember::where('cid', $controller['cid'])->first())
                                            <img src="{{$rosterMember->user->avatar()}}" style="height: 35px; !important; width: 35px !important; margin-left: 10px; margin-right: 5px; margin-bottom: 3px; border-radius: 50%;">
                                            {{$rosterMember->user->fullName('FLC')}}
                                        @else
                                            <div class="my-1">
                                                {{$controller['realname']}} {{$controller['cid']}}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </li>
                            <hr class="my-2">
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 d-md-none">
                    <div class="container py-4">
                        <h2 class="white-text font-weight-bold">Online Oceanic Controllers</h2>
                        <ul class="list-unstyled ml-0 mt-3 p-0 onlineControllers">
                            @if(count($controllers) < 1)
                            <li class="mb-2">
                                <div class="d-flex flex-row justify-content-between align-items-center mb-1">
                                    <h4 class="m-0 white-text"><i class="fas fa-sad-tear" style="margin-right: 1rem;"></i>No controllers online</h4>
                                </div>
                            </li>
                            @endif
                            @foreach($controllers as $controller)
                            <li>
                                <div class="white-text d-flex flex-row justify-content-between align-items-center">
                                    <h4 class="font-weight-bold m-0">{{$controller['callsign']}}</h4>
                                    <div style="font-size: 1.1em;">
                                        @if ($rosterMember = App\Models\Roster\RosterMember::where('cid', $controller['cid'])->first())
                                            <img src="{{$rosterMember->user->avatar()}}" style="height: 35px; !important; width: 35px !important; margin-left: 10px; margin-right: 5px; margin-bottom: 3px; border-radius: 50%;">
                                            {{$rosterMember->user->fullName('FLC')}}
                                        @else
                                            <div class="my-1">
                                                {{$controller['realname']}} {{$controller['cid']}}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </li>
                            <hr class="my-2">
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
    </div>
    <div style="background-size: cover; background-repeat: no-repeat; background-blend-mode:lighten; background-image:url({{asset('img/home-screen-backgrounds/czqosquarelightblue.png')}}); background-position: right;">
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-5">
                    <h1 class="font-weight-bold blue-text">We control the skies over the North Atlantic on VATSIM.</h1>
                    <p style="font-size: 1.2em;" class="mt-3">
                        Gander Oceanic is VATSIM's coolest, calmest and most collected provider of Oceanic control. With our worldwide team of skilled Oceanic controllers, we pride ourselves on our expert, high-quality service to pilots flying across the North Atlantic. Our incredible community of pilots and controllers extend their warmest welcome and wish you all the best for your oceanic crossings!
                    </p>
                    <p style="font-size: 1.2em;" class="mt-3">
                        <a class="font-weight-bold text-body" href="{{route('about.who-we-are')}}">Who we are &nbsp;&nbsp;<i class="fas fa-arrow-right blue-text"></i></a>
                    </p>
                    <div class="d-flex flex-row">
                        @if(!Auth::check() || Auth::user()->can('start-application'))
                        <a href="{{route('training.applications.apply')}}" role="button" class="btn bg-czqo-blue-light">Apply Now</a>
                        @endif
                        <a href="/pilots" class="btn bg-czqo-blue-light" role="button">Pilot Resources</a>
                    </div>
                </div>
                <div class="col-lg-7 text-right d-none d-lg-block">
                    <h1 class="font-weight-bold blue-text mb-3">Top Controllers This Month</h1>
                    <ul class="list-unstyled">
                        @php $index = 1; @endphp
                        @foreach($topControllers as $c)
                        <li class="mb-1">
                            <div class="row">
                                <div class="col-5">
                                    <span class="font-weight-bold blue-text" style="font-size: 1.9em;">
                                        {{$index}}.
                                    </span>
                                </div>
                                <div class="col text-left">
                                    <p class="mb-0">
                                        <span style="font-size: 1.4em;">
                                            <img src="{{$c->user->avatar()}}" style="height: 35px; !important; width: 35px !important; margin-left: 10px; margin-right: 5px; margin-bottom: 3px; border-radius: 50%;">
                                            {{$c->user->fullName('FL')}} - {{$c->monthly_hours}} hours
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </li>
                        @php $index++; @endphp
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="d-lg-none mt-4">
                <h1 class="font-weight-bold blue-text mb-3">Top Controllers This Month</h1>
                <ul class="list-unstyled">
                    @php $index = 1; @endphp
                    @foreach($topControllers as $c)
                    <li class="mb-1">
                        <span class="font-weight-bold blue-text" style="font-size: 1.9em;">
                            {{$index}}.
                        </span>
                        <span style="font-size: 1.4em;">
                            <img src="{{$c->user->avatar()}}" style="height: 35px; !important; width: 35px !important; margin-left: 10px; margin-right: 5px; margin-bottom: 3px; border-radius: 50%;">
                            {{$c->user->fullName('FL')}} - {{$c->monthly_hours}} hours
                        </span>
                    </li>
                    @php $index++; @endphp
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="jumbtron">
        <div class="container pt-5">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="d-flex flex-row justify-content-left">
                        <img style="margin-top: -7px;height: 80px;" src="{{asset('img/Twitter_Logo_Blue.png')}}" alt="">
                        <div>
                            <h2 class="font-weight-bold blue-text">Latest Tweets</h2>
                            <a href="https://twitter.com/ganderocavatsim/" class="text-body">
                                <p class="mt-0" style="font-size: 1.2em;">@ganderocavatsim</p>
                            </a>
                        </div>
                    </div>
                    <div class="list-group">
                        @if($tweets)
                        @foreach($tweets as $t)
                            <a href="https://twitter.com/ganderocavatsim/status/{{$t['id']}}" target="_blank" class="list-group-item list-group-item-action">
                                <p>
                                    {{$t['text']}}
                                </p>
                                <p class="text-muted mb-0">
                                    {{Carbon\Carbon::create($t['created_at'])->diffForHumans()}}
                                    @if($t['retweeted'])
                                    &nbsp;&nbsp;•&nbsp;&nbsp;
                                    <i class="fas fa-retweet"></i>
                                    Retweet of {{$t['retweeted_status']['user']['name']}}
                                    @endif
                                    @if($t['in_reply_to_user_id'])
                                    &nbsp;&nbsp;•&nbsp;&nbsp;
                                    <i class="fas fa-reply"></i>
                                    In Reply To {{$t['in_reply_to_screen_name']}}
                                    @endif
                                </p>
                            </a>
                        @endforeach
                        @else
                        No tweets found
                        @endif
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h2 class="font-weight-bold blue-text mb-4">Our Newest Controllers</h2>
                    @foreach ($certifications as $cert)
                        <div class="d-flex flex-row mb-2">
                            <img src="{{$cert->controller->avatar()}}" style="height: 55px !important; width: 55px !important; margin-right: 10px; margin-bottom: 3px; border-radius: 50%;">
                            <div class="d-flex flex-column">
                                <h4>{{$cert->controller->fullName('FL')}}</h4>
                                <p title="{{$cert->timestamp->toDayDateTimeString()}}">{{$cert->timestamp->diffForHumans()}}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="col-md-4">
                    <h2 class="font-weight-bold blue-text">Quick Links</h2>
                    <ul class="list-unstyled mt-4" style="font-size: 1.3em;">
                        <li class="mb-3">
                            <a data-toggle="modal" data-target="#discordTopModal" href="" style="text-decoration:none;">
                                <span class="blue-text">
                                    <i class="fab fa-discord fa-2x" style="vertical-align:middle;"></i>
                                </span>
                                &nbsp;
                                <span class="black-text">Join Our Discord Community</span>
                            </a>
                        </li>
                        <li class="mb-3">
                            <a href="https://twitter.com/ganderocavatsim" style="text-decoration:none;">
                                <span class="blue-text">
                                    <i class="fab fa-twitter fa-2x" style="vertical-align:middle;"></i>
                                </span>
                                &nbsp;
                                <span class="black-text">Twitter</span>
                            </a>
                        </li>
                        <li class="mb-3">
                            <a href="https://www.facebook.com/ganderocavatsim" style="text-decoration:none;">
                                <span class="blue-text">
                                    <i class="fab fa-facebook fa-2x" style="vertical-align:middle;"></i>
                                </span>
                                &nbsp;
                                <span class="black-text">Facebook</span>
                            </a>
                        </li>
                        <li class="mb-3">
                            <a href="https://www.youtube.com/channel/UC3norFpW3Cw4ryGR7ourjcA" style="text-decoration:none;">
                                <span class="blue-text">
                                    <i class="fab fa-youtube fa-2x" style="vertical-align:middle;"></i>
                                </span>
                                &nbsp;
                                <span class="black-text">YouTube Channel</span>
                            </a>
                        </li>
                        <li class="mb-3">
                            <a href="https://knowledgebase.ganderoceanic.com" style="text-decoration:none;">
                                <span class="blue-text">
                                    <i class="fas fa-book fa-2x" style="vertical-align:middle;"></i>
                                </span>
                                &nbsp;
                                <span class="black-text">Knowledge Base</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script>
        jarallax(document.querySelectorAll('.jarallax'), {
            speed: 0.5,
            videoSrc: 'mp4:https://cdn.ganderoceanic.com/resources/media/video/ZQO_SITE_TIMELAPSE.mp4',
            videoLoop: true
        });

    </script>
@endsection

