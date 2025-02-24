@extends('layouts.primary', ['solidNavBar' => true])
@section('title', 'Home - ')
@section('description', 'Cool, calm and collected oceanic control services in the North Atlantic on VATSIM.')

@section('content')
    <div style="height: calc(100vh - 74px); z-index: -1" class="z-depth-0 jarallax">
        <img src="{{asset('assets/resources/media/img/website/home_banner.png')}}" alt=""
            class="jarallax-img">
        <div class="flex-center mask rgba-black-light flex-column">
            <div class="container d-none d-sm-block">
                <h1 class="display-2 fw-700 white-text">Cool. Calm. Collected.</h1>
                <h2 style="font-size:3em;" class="fw-500 mt-4 white-text">Welcome to Gander Oceanic.</h2>
            </div>
            <div class="container d-sm-none">
                <h1 class="display-2 fw-700 white-text" style="font-size: 4.5em;">Cool. Calm. Collected.</h1>
                <h2 style="font-size:2em;" class="fw-500 mt-4 white-text">Welcome to Gander Oceanic.</h2>
            </div>
        </div>
    </div>
    <div class="jumbtron" style="margin-top: -100px; z-index: 999;">
        @if ($nextEvent)
            <div class="container px-5 py-3 mb-0 blue darken-2 white-text">
                <p style="font-size: 1.4em;" class="font-weight-bold mb-0">
                    <a href="{{ route('events.view', $nextEvent->slug) }}" class="white-text">
                        <i class="fa fa-calendar"></i>&nbsp;&nbsp;Upcoming: {{ $nextEvent->name }}
                    </a>
                    <span class="fw-400">
                        {{ $nextEvent->start_timestamp_pretty() }}
                    </span>
                </p>
            </div>
        @endif
        <div class="container blue z-depth-2 px-5 pt-5 pb-3 mb-5">
            <div class="row">
                <div class="col-md-6 mb-4">
                    @if ($news)
                        <div class="view"
                            style="height: 330px !important; @if ($news->image) background-image:url({{ $news->image }}); background-size: cover; background-position-x: center; @else background-image:url('https://ganderoceanic.ca/assets/staff_uploads/news_article/default.png'); background-size: cover; background-position-x: center; @endif">
                            <div class="mask rgba-stylish-light flex-left p-4 justify-content-end d-flex flex-column h-100">
                                <div class="container">
                                    <h1 class="font-weight-bold white-text">
                                        <a href="{{ route('news.articlepublic', $news->slug) }}" class="white-text">
                                            {{ $news->title }}
                                        </a>
                                    </h1>
                                    <p class="white-text" style="font-size: 1.3em;">
                                        {{ $news->summary }}
                                    </p>
                                    <a href="{{ route('news') }}" class="white-text" style="font-size: 1.2em;">All Articles
                                        <i class="fas fa-arrow-right"></i> </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-md-6 mb-4">
                    <div class="d-flex flex-row-justify-content-between align-items-center">
                        <h2 class="white-text font-weight-bold">Online Oceanic Controllers</h2>
                        <a href="{{ route('map') }}" class="float-right ml-auto mr-0 white-text"
                            style="font-size: 1.2em;">View map&nbsp;&nbsp;<i class="fas fa-map"></i></a>
                    </div>
                    <ul class="list-unstyled ml-0 mt-3 p-0 onlineControllers">
                        @if (count($controllers) < 1)
                            <li class="mb-2">
                                <div class="d-flex flex-row justify-content-between align-items-center mb-1">
                                    <h4 class="m-0 white-text"><i class="fas fa-sad-tear" style="margin-right: 1rem;"></i>No controllers online</h4>
                                </div>
                            </li>
                        @endif
                        <table class="table table-hover" style="color: white;">
                            <thead>
                            <tr>
                                <th scope="col">Callsign</th>
                                <th scope="col">Time Online</th>
                                <th scope="col">Controller</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($controllers as $controller)
                                <tr>
                                    <th scope="row"><b>{{$controller->callsign}}</b></th>
                                    <td>{{$controller->user->fullName('FLC')}}</td>
                                    <td>
                                        @if ($controller->session_start->diff(\Carbon\Carbon::now())->h > 0)
                                            {{ $controller->session_start->diff(\Carbon\Carbon::now())->h }}hr {{ $controller->session_start->diff(\Carbon\Carbon::now())->i }}min
                                        @else
                                            {{ $controller->session_start->diff(\Carbon\Carbon::now())->i }}m
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="container my-5">
        <div class="row">
            

            <div class="col-md-4 mb-4">
                <h2 class="font-weight-bold blue-text mb-4">{{\Carbon\Carbon::now()->format('F')}} Top Controllers</h2>
                @if (auth()->check())
                    <ul class="list-unstyled">
                        @php $index = 1; @endphp
                        @foreach ($topControllers as $c)
                            <li class="mb-1">
                                <div class="d-flex flex-row">
                                    <span class="font-weight-bold blue-text" style="font-size: 1.9em;">
                                        @if ($index == 1)
                                            <i class="fas fa-trophy amber-text fa-fw"></i>
                                        @elseif ($index == 2)
                                            <i class="fas fa-trophy blue-grey-text fa-fw"></i>
                                        @elseif ($index == 3)
                                            <i class="fas fa-trophy brown-text fa-fw"></i>
                                        @else
                                            {{ $index }}.
                                        @endif
                                    </span>
                                    <p class="mb-0 ml-1">
                                        <span style="font-size: 1.4em;">
                                            <img src="{{ $c->user->avatar() }}"
                                                style="height: 35px; !important; width: 35px !important; margin-left: 10px; margin-right: 5px; margin-bottom: 3px; border-radius: 50%;">
                                            <div class="d-flex flex-column ml-2">
                                                <h4 class="fw-400">{{ $c->user->fullName('FL') }}</h4>
                                                <p>
                                                    @if($c->monthly_hours < 1)
                                                        {{ str_pad(round(($c->monthly_hours - floor($c->monthly_hours)) * 60), 2, '0', STR_PAD_LEFT) }}m recorded this month
                                                    @else
                                                        {{ floor($c->monthly_hours) }}h {{ str_pad(round(($c->monthly_hours - floor($c->monthly_hours)) * 60), 2, '0', STR_PAD_LEFT) }}m recorded this month
                                                    @endif
                                                </p>
                                            </div>
                                        </span>
                                    </p>
                                </div>
                            </li>
                            @php $index++; @endphp
                        @endforeach
                        @if (count($topControllers) < 1)
                            No data available.
                        @endif
                    </ul>
                @else
                    Login with VATSIM to check our top controllers!
                @endif
            </div>

            {{-- new certifications --}}
            <div class="col-md-4 mb-4">
                <h2 class="font-weight-bold blue-text mb-4">Our Newest Controllers</h2>
                @if (count($certifications) > 0)
                    @foreach ($certifications as $cert)
                        <div class="d-flex flex-row mb-2">
                            <img src="{{ $cert->controller->avatar() }}"
                                style="height: 55px !important; width: 55px !important; margin-right: 10px; margin-bottom: 3px; border-radius: 50%;">
                            <div class="d-flex flex-column">
                                <h4 class="fw-400">{{ $cert->controller->fullName('FL') }}</h4>
                                <p title="{{ $cert->timestamp->toDayDateTimeString() }}">
                                    {{ $cert->timestamp->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                @elseif (!auth()->check())
                    Login with VATSIM to view our newest controllers!
                @else
                    No data available.
                @endif
            </div>

            {{-- Controller's of the Year --}}
            <div class="col-md-4 mb-4">
                <h2 class="font-weight-bold blue-text mb-4">{{\Carbon\Carbon::now()->format('Y')}} Top Controllers</h2>
                @if (auth()->check())
                    <ul class="list-unstyled">
                        @php $index = 1; @endphp
                        @foreach ($yearControllers as $c)
                            <li class="mb-1">
                                <div class="d-flex flex-row">
                                    <span class="font-weight-bold blue-text" style="font-size: 1.9em;">
                                        @if ($index == 1)
                                            <i class="fas fa-trophy amber-text fa-fw"></i>
                                        @elseif ($index == 2)
                                            <i class="fas fa-trophy blue-grey-text fa-fw"></i>
                                        @elseif ($index == 3)
                                            <i class="fas fa-trophy brown-text fa-fw"></i>
                                        @else
                                            {{ $index }}.
                                        @endif
                                    </span>
                                    <p class="mb-0 ml-1">
                                        <span style="font-size: 1.4em;">
                                            <img src="{{ $c->user->avatar() }}"
                                                style="height: 35px; !important; width: 35px !important; margin-left: 10px; margin-right: 5px; margin-bottom: 3px; border-radius: 50%;">
                                            <div class="d-flex flex-column ml-2">
                                                <h4 class="fw-400">{{ $c->user->fullName('FL') }}</h4>
                                                <p>
                                                    @if($c->currency < 1)
                                                        {{ str_pad(round(($c->currency - floor($c->currency)) * 60), 2, '0', STR_PAD_LEFT) }}m recorded in {{\Carbon\Carbon::now()->format('Y')}}
                                                    @else
                                                        {{ floor($c->currency) }}h {{ str_pad(round(($c->currency - floor($c->currency)) * 60), 2, '0', STR_PAD_LEFT) }}m recorded in {{\Carbon\Carbon::now()->format('Y')}}
                                                    @endif
                                                </p>
                                            </div>
                                        </span>
                                    </p>
                                </div>
                            </li>
                            @php $index++; @endphp
                        @endforeach
                        @if (count($yearControllers) < 1)
                            No data available.
                        @endif
                    </ul>
                @else
                    Login with VATSIM to check our top controllers!
                @endif
            </div>
        </div>
    </div>
    <div class="container pb-5">
        <div class="row">
            <div class="col-lg-7 mb-4">
                <h1 class="font-weight-bold blue-text">We control the skies over the North Atlantic on VATSIM.</h1>
                <p style="font-size: 1.2em;" class="mt-3">
                    Gander Oceanic is VATSIM's coolest, calmest and most collected provider of Oceanic control. With our
                    worldwide team of skilled Oceanic controllers, we pride ourselves on our expert, high-quality service to
                    pilots flying across the North Atlantic. Our incredible community of pilots and controllers extend their
                    warmest welcome and wish you all the best for your oceanic crossings!
                </p>
                <p style="font-size: 1.2em;" class="mt-3">
                    <a class="font-weight-bold text-body" href="{{ route('about.who-we-are') }}">Who we are
                        &nbsp;&nbsp;<i class="fas fa-arrow-right blue-text"></i></a>
                </p>
                <div class="d-flex flex-row">
                    @if (!Auth::check() || Auth::user()->can('start-application'))
                        <a href="{{ route('training.applications.apply') }}" role="button"
                            class="btn bg-czqo-blue-light">Apply Now</a>
                    @endif
                    <a href="/pilots" class="btn bg-czqo-blue-light" role="button">Pilot Resources</a>
                </div>
            </div>

            <div class="col-lg-5 text-right">
                <h2 class="font-weight-bold mb-3 blue-text">Quick Links</h2>
                <div class="list-group mt-4 rounded list-group-flush" style="font-size: 1.3em;">
                    <a class="border-0 list-group-item list-group-item-action waves-effect" data-toggle="modal"
                        data-target="#discordTopModal" href="" style="text-decoration:none;">
                        <span class="blue-text">Join Our Discord Community</span>
                        &nbsp;
                        <span class="blue-text">
                            <i class="fab fa-discord fa-2x" style="vertical-align:middle;"></i>
                        </span>
                    </a>
                    {{-- <a class="border-0 list-group-item list-group-item-action waves-effect"
                        href="https://twitter.com/ganderocavatsim" style="text-decoration:none;">
                        <span class="blue-text">Twitter</span>
                        &nbsp;
                        <span class="blue-text">
                            <i class="fab fa-twitter fa-2x" style="vertical-align:middle;"></i>
                        </span>
                    </a> --}}
                    {{-- <a class="border-0 list-group-item list-group-item-action waves-effect"
                        href="https://www.facebook.com/ganderocavatsim" style="text-decoration:none;">
                        <span class="blue-text">Facebook</span>
                        &nbsp;
                        <span class="blue-text">
                            <i class="fab fa-facebook fa-2x" style="vertical-align:middle;"></i>
                        </span>
                    </a> --}}
                    {{-- <a class="border-0 list-group-item list-group-item-action waves-effect"
                        href="https://www.youtube.com/channel/UC3norFpW3Cw4ryGR7ourjcA" style="text-decoration:none;">
                        <span class="blue-text">YouTube Channel</span>
                        &nbsp;
                        <span class="blue-text">
                            <i class="fab fa-youtube fa-2x" style="vertical-align:middle;"></i>
                        </span>
                    </a> --}}
                    <a class="border-0 list-group-item list-group-item-action waves-effect"
                        href="https://knowledgebase.ganderoceanic.ca" style="text-decoration:none;">
                        <span class="blue-text">ZQO Knowledgebase</span>
                        &nbsp;
                        <span class="blue-text">
                            <i class="fas fa-book fa-2x" style="vertical-align:middle;"></i>
                        </span>
                    </a>
                    <a class="border-0 list-group-item list-group-item-action waves-effect"
                        href="https://nattrak.vatsim.net/" style="text-decoration:none;">
                        <span class="blue-text">NATTRAK Website</span>
                        &nbsp;
                        <span class="blue-text">
                            <i class="fa fa-satellite-dish fa-2x" style="vertical-align:middle;"></i>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script>
        /*
            jarallax(document.querySelectorAll('.jarallax'), {
                speed: 0.5,
                videoSrc: 'mp4:https://cdn.ganderoceanic.ca/resources/media/video/ZQO_SITE_TIMELAPSE.mp4',
                videoLoop: true,
                zIndex: 5
            }); */
        $('.jarallax').jarallax({
            speed: 0.2,
            zIndex: -1
        });

        $(document).ready(function() {
            $('#dataTable').DataTable();
        } );
    </script>

@endsection
