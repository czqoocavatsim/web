@extends('layouts.primary', ['solidNavBar' => true])
@section('title', 'Home - ')
@section('description', 'Cool, calm and collected oceanic control services in the North Atlantic on VATSIM.')

@section('content')
    {{-- Top Introduction --}}
    <div style="height: calc(100vh - 74px); z-index: -1" class="z-depth-0 jarallax">
        <img src="{{asset('assets/resources/media/img/website/home_banner.png')}}" alt="" class="jarallax-img">
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

    {{-- CTP Specific - Event Upcoming --}}
    @if($ctpMode == 2)
        <div class="jumbtron" style="margin-top: -100px; z-index: 999;">
        @if ($ctpEvents)
            <div class="container px-5 py-3 mb-2 blue darken-2 white-text" style="position: relative;">
                <p style="font-size: 2.5em;" class="font-weight-bold mb-0">{{$ctpEvents->name}} is underway!</p>
                <p style="font-size: 1.4em;" class="mb-0">Cross the Pond is currently underway, with <b>{{$ctpAircraft->ganwick}}</b> aircraft currently inside the Gander (CZQO) and Shanwick (EGGX) FIRs on their way towards their destinations!</p>

                <div id="countdown" class="d-flex gap-2 flex-wrap text-center"
                    style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                </div>

                <p style="font-size: 1.8em; margin-top: 15px;" class="mb-0">
                    <div class="row" style="text-align: left">
                        <div class="col-md-2">Gander OCA (CZQO)<br>
                            {{$ctpAircraft->czqo}} Aircraft in FIR
                        </div>
                        <div class="col-md-2">Shanwick OCA (EGGX)<br>
                            {{$ctpAircraft->eggx}} Aircraft in FIR
                        </div>
                        <div class="col-md-2">New York OCA (KZNY)<br>
                            {{$ctpAircraft->kzny}} Aircraft in FIR
                        </div>
                        <div class="col-md-3">Santa Maria OCA (LPPO)<br>
                            {{$ctpAircraft->lppo}} Aircraft in FIR
                        </div>
                        <div class="col-md-3">Reykjavik FIR (BIRD)<br>
                            {{$ctpAircraft->bird}} Aircraft in FIR
                        </div>
                    </div>
                </p>

            </div>
        @endif
        <div class="container blue z-depth-2 px-5 pt-5 pb-3 mb-5">
            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="d-flex flex-row-justify-content-between align-items-center">
                        <h2 class="white-text font-weight-bold"><u>Cross the Pond Controllers</u></h2>
                        <a href="{{ route('map') }}" class="float-right ml-auto mr-0 white-text"
                            style="font-size: 1.2em;">View airspace map&nbsp;&nbsp;<i class="fas fa-map"></i></a>
                    </div>
                    <ul class="list-unstyled ml-0 mt-3 p-0 onlineControllers">
                        @if (count($controllers) < 1)
                            <li class="mb-2">
                                <div class="d-flex flex-row justify-content-between align-items-center mb-1">
                                    <h4 class="m-0 white-text"><i class="fas fa-times" style="margin-right: 1rem;"></i>Hmmmm, weird... There doesn't seem to be any controllers currently connected.</h4>
                                </div>
                            </li>
                        @else
                            <div class="row">
                                @foreach($controllers as $controller)
                                    <div class="col-md-3 white-text">
                                        <p style="margin-bottom: 0px; font-size: 1.6em; text-color: white;">{{$controller->callsign}}</p>
                                        <p style="margin-bottom: 0px; font-size: 1.1em; text-color: white;">
                                            @if ($controller->session_start->diff(\Carbon\Carbon::now())->h > 0)
                                                {{ $controller->session_start->diff(\Carbon\Carbon::now())->h }}hr {{ $controller->session_start->diff(\Carbon\Carbon::now())->i }}min
                                            @else
                                                {{ $controller->session_start->diff(\Carbon\Carbon::now())->i }}min
                                            @endif
                                        </p>
                                        <p style="margin-bottom: 15px; font-size: 0.9em; text-color: white;"><i class="fas fa-user"></i>
                                            @if($controller->user)
                                                {{$controller->user->fullName('FLC')}}
                                            @else
                                                {{$controller->cid}}
                                            @endif
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- General Information - Online Controllers & News - Hidden during CTP Event--}}
    @if($ctpMode == false || $ctpMode == 1)
    <div class="jumbtron" style="margin-top: -100px; z-index: 999;">

        {{-- CTP Event is occuring this Month --}}
        @if ($ctpMode == 1)
            <div class="container px-5 py-3 mb-2 blue darken-2 white-text" style="position: relative;">
                <p style="font-size: 2.5em;" class="font-weight-bold mb-0">{{$ctpEvents->name}} is on its way!</p>
                <p style="font-size: 1.4em;" class="mb-0">Cross the Pond takes flight on {{\Carbon\Carbon::parse($ctpEvents->oca_start)->format('l, jS \\of F Y')}}</p>

                <p style="font-size: 1.4em;" class="mb-0">
                    <div class="row" style="text-align: left">
                        <div class="col-md-3"><a href="https://ctp.vatsim.net/" target="_blank" style="color: white; text-decoration: underline; font-size: 1.4em;">CTP Website</a></div>
                        @if($ctpEvents->app !== null) @hasanyrole('Certified Controller')<div class="col-md-3"><a href="{{$ctpEvents->app}}" target="_blank" style="color: white; text-decoration: underline; font-size: 1.4em;">Apply to Control</a></div>@endhasanyrole @endif
                        {{-- <div class="col-md-3"><a href="1" target="_blank" style="color: white; text-decoration: underline; font-size: 1.4em;">Test</a></div> --}}
                    </div>
                </p>

                <div id="countdown" class="d-flex gap-2 flex-wrap text-center"
                    style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                </div>

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
                        <h2 class="white-text font-weight-bold">Current Activity</h2>
                        <a href="{{ route('map') }}" class="float-right ml-auto mr-0 white-text"
                            style="font-size: 1.2em;">View airspace map&nbsp;&nbsp;<i class="fas fa-map"></i></a>
                    </div>
                    <p style="font-size: 1.8em; margin-top: 15px;" class="mb-0">
                    <div class="row white-text" style="text-align: left; text-color: white; text-align: center;" >
                        <div class="col-md-4"><u>Gander OCA (CZQO)</u><br>
                            {{$ctpAircraft->czqo}} Aircraft in FIR
                        </div>
                        <div class="col-md-4"><u>Shanwick OCA (EGGX)</u><br>
                            {{$ctpAircraft->eggx}} Aircraft in FIR
                        </div>
                        <div class="col-md-4"><u>New York OCA (KZNY)</u><br>
                            {{$ctpAircraft->kzny}} Aircraft in FIR
                        </div>
                    </div>
                </p>
                    <ul class="list-unstyled ml-0 mt-3 p-0 onlineControllers">
                        @if (count($controllers) < 1)
                            <li class="mb-2">
                                <div class="d-flex flex-row justify-content-between align-items-center mb-1">
                                    <h4 class="m-0 white-text"><i class="fas fa-times" style="margin-right: 1rem;"></i>No controllers currently connected to VATSIM.</h4>
                                </div>
                            </li>
                        @else
                        <table class="table table-hover" style="color: white; text-align: center;">
                            <thead>
                            <tr>
                                <th scope="col">Callsign</th>
                                <th scope="col">Controller</th>
                                <th scope="col">Time Online</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($controllers as $controller)
                                <tr>
                                    <th scope="row"><b>{{$controller->callsign}}</b>
                                        @if($controller->is_instructing == 1)<br><span class="badge bg-danger">Instructing</span>@endif
                                        @if($controller->is_student == 1)<br><span class="badge bg-warning">Training</span>@endif
                                        @if($controller->is_ctp == 1)<br><span class="badge bg-info">CTP Controller</span>@endif</th>
                                    <td>
                                        @if($controller->user)
                                            {{$controller->user->fullName('FLC')}}
                                        @else
                                            {{$controller->cid}}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($controller->session_start->diff(\Carbon\Carbon::now())->h > 0)
                                            {{ $controller->session_start->diff(\Carbon\Carbon::now())->h }}hr {{ $controller->session_start->diff(\Carbon\Carbon::now())->i }}min
                                        @else
                                            {{ $controller->session_start->diff(\Carbon\Carbon::now())->i }}min
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif



    {{-- Controller Statistics --}}
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
                                            {{ $index }}<sup>th</sup>
                                        @endif
                                    </span>
                                    <p class="mb-0 ml-1">
                                        <span style="font-size: 1.4em;">
                                            @if($c->user)
                                            <img src="{{ $c->user->avatar() }}" style="height: 35px; !important; width: 35px !important; margin-left: 10px; margin-right: 5px; margin-bottom: 3px; border-radius: 50%;">
                                            <div class="d-flex flex-column ml-2">
                                                <h5 class="fw-400">{{ $c->user->fullName('FL') }} 
                                                    @if($c->visiting_origin == "zny")<span class="badge bg-secondary">KZNY</span>@elseif($c->visiting_origin == "eggx")<span class="badge bg-danger">EGGX</span>@else<span class="badge bg-primary">CZQO</span>@endif
                                                </h5>
                                                <p>
                                                    @if($c->monthly_hours < 1)
                                                        {{ str_pad(round(($c->monthly_hours - floor($c->monthly_hours)) * 60), 2, '0', STR_PAD_LEFT) }}m recorded this month
                                                    @else
                                                        {{ floor($c->monthly_hours) }}h {{ str_pad(round(($c->monthly_hours - floor($c->monthly_hours)) * 60), 2, '0', STR_PAD_LEFT) }}m recorded this month
                                                    @endif
                                                </p>
                                            </div>
                                            @else
                                            <img src="{{asset('assets/resources/media/img/brand/sqr/ZQO_SQ_TSPBLUE.png')}}" style="height: 35px; !important; width: 35px !important; margin-left: 10px; margin-right: 5px; margin-bottom: 3px; border-radius: 50%;">
                                            <div class="d-flex flex-column ml-2">
                                                <h5 class="fw-400">{{ $c->id }} 
                                                    @if($c->visiting_origin == "zny")<span class="badge bg-secondary">KZNY</span>@elseif($c->visiting_origin == "eggx")<span class="badge bg-danger">EGGX</span>@endif
                                                </h5>
                                                <p>
                                                    @if($c->monthly_hours < 1)
                                                        {{ str_pad(round(($c->monthly_hours - floor($c->monthly_hours)) * 60), 2, '0', STR_PAD_LEFT) }}m recorded this month
                                                    @else
                                                        {{ floor($c->monthly_hours) }}h {{ str_pad(round(($c->monthly_hours - floor($c->monthly_hours)) * 60), 2, '0', STR_PAD_LEFT) }}m recorded this month
                                                    @endif
                                                </p>
                                            </div>
                                            @endif
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
                    Login with VATSIM to check our {{\Carbon\Carbon::now()->format('F')}} top controllers!
                @endif
            </div>

            {{-- new certifications --}}
            <div class="col-md-4 mb-4">
                <h2 class="font-weight-bold blue-text mb-4">Our Newest Controllers</h2>
                @if(auth()->check())
                <ul class="list-unstyled">
                    @foreach ($certifications as $cert)
                        <li class="mb-1">
                            <div class="d-flex flex-row">
                                <p class="mb-0 ml-1">
                                    <span style="font-size: 1.4em;">
                                        <img src="{{ $cert->controller->avatar() }}" style="height: 35px !important; width: 35px !important; margin-right: 10px; margin-bottom: 3px; border-radius: 50%;">
                                        <div class="d-flex flex-column ml-2">
                                            <h5 class="fw-400">{{ $cert->controller->fullName('FL') }}</h5>
                                            <p title="{{ $cert->timestamp->toDayDateTimeString() }}">
                                                {{ $cert->timestamp->diffForHumans() }}</p>
                                        </div>
                                    </span>
                                </p>
                            </div>
                        </li>
                    @endforeach
                    @if (count($topControllers) < 1)
                        No data available.
                    @endif
                </ul>
                @else
                    Login with VATSIM to see our most recent certified controllers.
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
                                            {{ $index }}<sup>th</sup>
                                        @endif
                                    </span>
                                    <p class="mb-0 ml-1">
                                        <span style="font-size: 1.4em;">
                                            @if($c->user)
                                            {{-- Gander Oceanic User Model Exists --}}
                                            <img src="{{ $c->user->avatar() }}" style="height: 35px; !important; width: 35px !important; margin-left: 10px; margin-right: 5px; margin-bottom: 3px; border-radius: 50%;">
                                            <div class="d-flex flex-column ml-2">
                                                <h5 class="fw-400">{{ $c->user->fullName('FL') }}
                                                    @if($c->visiting_origin == "zny")<span class="badge bg-secondary">KZNY</span>@elseif($c->visiting_origin == "eggx")<span class="badge bg-danger">EGGX</span>@else<span class="badge bg-primary">CZQO</span>@endif
                                                </h5>
                                                <p>
                                                    @if($c->currency < 1)
                                                        {{ str_pad(round(($c->currency - floor($c->currency)) * 60), 2, '0', STR_PAD_LEFT) }}m recorded in {{\Carbon\Carbon::now()->format('Y')}}
                                                    @else
                                                        {{ floor($c->currency) }}h {{ str_pad(round(($c->currency - floor($c->currency)) * 60), 2, '0', STR_PAD_LEFT) }}m recorded in {{\Carbon\Carbon::now()->format('Y')}}
                                                    @endif
                                                </p>
                                            </div>
                                            @else
                                            {{-- User Model does not Exist --}}
                                            <img src="{{asset('assets/resources/media/img/brand/sqr/ZQO_SQ_TSPBLUE.png')}}" style="height: 35px; !important; width: 35px !important; margin-left: 10px; margin-right: 5px; margin-bottom: 3px; border-radius: 50%;">
                                            <div class="d-flex flex-column ml-2">
                                                <h5 class="fw-400">{{ $c->id }}
                                                    @if($c->visiting_origin == "zny")<span class="badge bg-secondary">KZNY</span>@elseif($c->visiting_origin == "eggx")<span class="badge bg-danger">EGGX</span>@endif
                                                </h5>
                                                <p>
                                                    @if($c->currency < 1)
                                                        {{ str_pad(round(($c->currency - floor($c->currency)) * 60), 2, '0', STR_PAD_LEFT) }}m recorded in {{\Carbon\Carbon::now()->format('Y')}}
                                                    @else
                                                        {{ floor($c->currency) }}h {{ str_pad(round(($c->currency - floor($c->currency)) * 60), 2, '0', STR_PAD_LEFT) }}m recorded in {{\Carbon\Carbon::now()->format('Y')}}
                                                    @endif
                                                </p>
                                            </div>
                                            @endif
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
                    Login with VATSIM to check our {{\Carbon\Carbon::now()->format('Y')}} top controllers!
                @endif
            </div>
        </div>
    </div>




    {{-- Bottom Section --}}
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
                    @if(Auth::check())
                        @if(Auth::user()->member_of_czqo !== 1)
                            <a class="border-0 list-group-item list-group-item-action waves-effect" data-toggle="modal"
                                data-target="#discordTopModal" href="" style="text-decoration:none;">
                                <span class="blue-text">Join Our Discord Community</span>
                                &nbsp;
                                <span class="blue-text">
                                    <i class="fab fa-discord fa-2x" style="vertical-align:middle;"></i>
                                </span>
                            </a>
                        @endif
                    @endif
                    <a class="border-0 list-group-item list-group-item-action waves-effect"
                        href="https://knowledgebase.ganderoceanic.ca" style="text-decoration:none;">
                        <span class="blue-text">ZQO Knowledge Base</span>
                        &nbsp;
                        <span class="blue-text">
                            <i class="fas fa-book fa-2x" style="vertical-align:middle;"></i>
                        </span>
                    </a>
                    <a class="border-0 list-group-item list-group-item-action waves-effect"
                        href="https://nattrak.vatsim.net/" style="text-decoration:none;">
                        <span class="blue-text">natTrak Oceanic Clearance</span>
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
        $('.jarallax').jarallax({
            speed: 0.2,
            zIndex: -1
        });

        $(document).ready(function() {
            $('#dataTable').DataTable();
        } );


        const eventDate = new Date("{{ \Carbon\Carbon::parse($ctpEvents->oca_start)->toIso8601String() }}").getTime();
        const countdownElement = document.getElementById('countdown');

        const updateCountdown = () => {
            const now = new Date().getTime();
            const distance = eventDate - now;

            if (distance < 0) {
                countdownElement.innerHTML = "<div class='box bg-success text-white p-3 rounded'>The event is currently underway!</div>";
                clearInterval(timer);
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            countdownElement.innerHTML = `
                <div class="box bg-primary text-white p-3 rounded" style="margin-right: 3px;">
                    <div style="font-size: 2em;">${days}</div>
                    <div>Days</div>
                </div>
                <div class="box bg-primary text-white p-3 rounded" style="margin-right: 3px;">
                    <div style="font-size: 2em;">${hours}</div>
                    <div>Hours</div>
                </div>
                <div class="box bg-primary text-white p-3 rounded" style="margin-right: 3px;">
                    <div style="font-size: 2em;">${minutes}</div>
                    <div>Minutes</div>
                </div>
                <div class="box bg-primary text-white p-3 rounded" style="margin-right: 3px;">
                    <div style="font-size: 2em;">${seconds}</div>
                    <div>Seconds</div>
                </div>
            `;
        };

        const timer = setInterval(updateCountdown, 1000);
        updateCountdown();
    </script>

@endsection
