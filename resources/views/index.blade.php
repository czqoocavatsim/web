@extends('layouts.primary', ['solidNavBar' => true])
@section('title', 'Home - ')
@section('description', 'Cool, calm and collected oceanic control services in the North Atlantic on VATSIM.')

@section('content')
    {{-- Top Introduction --}}
    <div style="height: calc(100vh - 74px); z-index: -1" class="z-depth-0 jarallax">
        <img src="{{asset('assets/resources/media/img/website/787.png')}}" alt="" class="jarallax-img">
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
                <p style="font-size: 1.4em;" class="mb-0">Cross the Pond is currently underway, with <b><x id="ganwick-count">{{$ctpAircraft->ganwick}}</x></b> aircraft currently inside the Gander (CZQO) and Shanwick (EGGX) FIRs on their way towards their destinations!</p>
                
                <div id="countdown" class="d-flex gap-2 flex-wrap text-center"
                    style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                </div>

                <p style="font-size: 1.8em; margin-top: 15px;" class="mb-0">
                    <div class="row" style="text-align: left">
                        <div class="col-md-2"><u>Gander OCA (CZQO)</u><br>
                            <div id="czqo-count">{{$ctpAircraft->czqo}} Aircraft in FIR</div>
                        </div>
                        <div class="col-md-2"><u>Shanwick OCA (EGGX)</u><br>
                            <div id="eggx-count">{{$ctpAircraft->eggx}} Aircraft in FIR</div>
                        </div>
                        <div class="col-md-2"><u>New York OCA (KZNY)</u><br>
                            <div id="kzny-count">{{$ctpAircraft->kzny}} Aircraft in FIR</div>
                        </div>
                        <div class="col-md-2"><u>Reykjavik FIR (BIRD)</u><br>
                            <div id="bird-count">{{$ctpAircraft->bird}} Aircraft in FIR</div>
                        </div>
                        <div class="col-md-4"><u>Santa Maria OCA (LPPO)</u><br>
                            <div id="lppo-count">{{$ctpAircraft->lppo}} Aircraft in FIR</div>
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

                    <p style="font-size: 1.1em; margin-top: -0px;" class="mb-0 white-text"><b>Last Updated:</b> <x id="timer-info">{{ \Carbon\Carbon::now('UTC')->format('Hi') }}Z</x><p>

                    <ul class="list-unstyled ml-0 mt-3 p-0 onlineControllers">
                        <x id="controller-info">
                            @include('partials.homepage.ctp-controllers');
                        </x>
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
                    
                    <p style="font-size: 1.1em; margin-top: 0px;" class="mb-1 white-text"><b>Last Updated:</b> <x id="timer-info">{{ \Carbon\Carbon::now('UTC')->format('Hi') }}Z</x><p>

                    <p style="font-size: 1.8em; margin-top: 15px;" class="mb-0">
                    <div class="row white-text" style="text-align: left; text-color: white; text-align: center;" >
                        <div class="col-md-4 col-sm-4"><u>Gander OCA (CZQO)</u><br>
                            <div id="czqo-count">{{$ctpAircraft->czqo}} Aircraft in FIR</div>
                        </div>
                        <div class="col-md-4 col-sm-4"><u>Shanwick OCA (EGGX)</u><br>
                            <div id="eggx-count">{{$ctpAircraft->eggx}} Aircraft in FIR</div>
                        </div>
                        <div class="col-md-4 col-sm-4"><u>New York OCA (KZNY)</u><br>
                            <div id="kzny-count">{{$ctpAircraft->kzny}} Aircraft in FIR</div>
                        </div>
                    </div>
                </p>
                    <ul class="list-unstyled ml-0 mt-3 p-0 onlineControllers">
                        {{-- Updated every minute. Rendered by JS from Partials Folder --}}
                        <x id="controller-info">
                            @include('partials.homepage.general-controllers');
                        </x>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif



    {{-- Statistics --}}
    <div class="container my-5">
        <h1 class="font-weight-bold blue-text mb-1"><u>Gander OCA Statistics</u></h1>
        <a href="/stats"><p style="font-size: 1.2em;" class="mb-3">Access the Full Statistics Page</p></a>
        <div class="row">
            {{-- Month Pilot Stats --}}
            @if (auth()->check())
                @include('partials.statistics.pilot-month')
            @else
                <h4 class="font-weight-bold blue-text mb-1">{{\Carbon\Carbon::now()->format('F')}} Top Pilots</h4>
                Login with VATSIM to see this data
            @endif

            {{-- New Certifications --}}
            @if(auth()->check())
                @include('partials.statistics.aircraft-airline')
            @else
                <h4 class="font-weight-bold blue-text mb-1">Newest Controllers</h4>
                Login with VATSIM to see this data
            @endif

            {{-- New Certifications --}}
            @if(auth()->check())
                @include('partials.statistics.controller-month')
            @else
                <h4 class="font-weight-bold blue-text mb-1">{{\Carbon\Carbon::now()->format('F')}} Top Controllers</h4>
                Login with VATSIM to see this data
            @endif

            {{-- Aircraft Type Stats --}}
            @if (auth()->check())
                @include('partials.statistics.aircraft-type-year')
            @else
                <h2 class="font-weight-bold blue-text mb-1">{{\Carbon\Carbon::now()->format('F')}} Top Aircraft Types</h2>
                Login with VATSIM to see this data
            @endif

            {{-- New Certifications --}}
            @if(auth()->check())
                @include('partials.statistics.certifications')
            @else
                <h4 class="font-weight-bold blue-text mb-1">{{\Carbon\Carbon::now()->format('Y')}} Top Airlines</h4>
                Login with VATSIM to see this data
            @endif

            {{-- New Certifications --}}
            @if(auth()->check())
                @include('partials.statistics.controller-year')
            @else
                <h4 class="font-weight-bold blue-text mb-1">{{\Carbon\Carbon::now()->format('Y')}} Top Controllers</h4>
                Login with VATSIM to see this data
            @endif
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
    {{-- Script that runs at any time --}}
    <script>
        $('.jarallax').jarallax({
            speed: 0.2,
            zIndex: -1
        });

        $(document).ready(function() {
            $('#dataTable').DataTable();
        } );
    </script>

    {{-- Countdown Timer for the CTP Events --}}
    @if($ctpEvents !== null)
        <script>
            const eventDate = new Date("{{ \Carbon\Carbon::parse($ctpEvents->oca_start)->toIso8601String() }}").getTime();
            const countdownElement = document.getElementById('countdown');

            const updateCountdown = () => {
                const now = new Date().getTime();
                const distance = eventDate - now;

                if (distance < 0) {
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
    @endif

@if($ctpMode == false)
<script>
    setInterval(() => {
    fetch('/api/update-homepage/general')
        .then(res => res.json())
        .then(data => {
            document.getElementById('czqo-count').innerText = data.czqo + ' Aircraft in FIR';
            document.getElementById('eggx-count').innerText = data.eggx + ' Aircraft in FIR';
            document.getElementById('kzny-count').innerText = data.kzny + ' Aircraft in FIR';
            document.getElementById('timer-info').innerText = new Date().toISOString().slice(11,16).replace(':','') + 'Z';
        });
    }, 60000);

    setInterval(() => {
        fetch('/api/update-homepage/controllers/1')
            .then(res => res.text())
            .then(html => {
                document.getElementById('controller-info').innerHTML = html;
            });
    }, 60000);

</script>
@elseif($ctpMode == 1)
<script>
    setInterval(() => {
    fetch('/api/update-homepage/general')
        .then(res => res.json())
        .then(data => {
            document.getElementById('czqo-count').innerText = data.czqo + ' Aircraft in FIR';
            document.getElementById('eggx-count').innerText = data.eggx + ' Aircraft in FIR';
            document.getElementById('kzny-count').innerText = data.kzny + ' Aircraft in FIR';
            document.getElementById('timer-info').innerText = new Date().toISOString().slice(11,16).replace(':','') + 'Z';
        });
    }, 60000);

    setInterval(() => {
        fetch('/api/update-homepage/controllers/1')
            .then(res => res.text())
            .then(html => {
                document.getElementById('controller-info').innerHTML = html;
            });
    }, 60000);
</script>
@elseif($ctpMode == 2)
<script>
    setInterval(() => {
    fetch('/api/update-homepage/general')
        .then(res => res.json())
        .then(data => {
            document.getElementById('ganwick-count').innerText = data.ganwick + ' Aircraft in FIR';
            document.getElementById('czqo-count').innerText = data.czqo + ' Aircraft in FIR';
            document.getElementById('eggx-count').innerText = data.eggx + ' Aircraft in FIR';
            document.getElementById('lppo-count').innerText = data.lppo + ' Aircraft in FIR';
            document.getElementById('bird-count').innerText = data.bird + ' Aircraft in FIR';
            document.getElementById('kzny-count').innerText = data.kzny + ' Aircraft in FIR';
            document.getElementById('timer-info').innerText = new Date().toISOString().slice(11,16).replace(':','') + 'Z';

        });
    }, 60000);

    setInterval(() => {
        fetch('/api/update-homepage/controllers/2')
            .then(res => res.text())
            .then(html => {
                document.getElementById('controller-info').innerHTML = html;
            });
    }, 60000);
</script>
@endif


@endsection
