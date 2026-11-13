@extends('layouts.primary', ['solidNavBar' => true, 'homeNav' => true])
@section('title', 'Home - ')
@section('description', 'Cool, calm and collected oceanic control services in the North Atlantic on VATSIM.')
@push('styles')
<style>
body.zh-body{background:#23252b;color:#f2f3f5;font-family:'Mulish',system-ui,-apple-system,'Segoe UI',sans-serif}
body.zh-body main{background:#23252b}
.zh a{color:#f39c37;text-decoration:none}.zh a:hover{color:#ffc27a;text-decoration:none}
.zh h1,.zh h2,.zh h3{color:#f2f3f5;margin:0}
.zh-wrap{padding-left:64px;padding-right:64px}
/* nav */
.zh-nav{display:flex;align-items:center;gap:16px 32px;padding:0 64px;min-height:76px;background:#1b1d22;border-bottom:1px solid #383c45;position:relative;z-index:1000;font-family:'Mulish',system-ui,sans-serif}
.zh-brand img{height:44px;width:auto;display:block}
.zh-links{display:flex;gap:6px 26px;margin:0 0 0 auto;padding:0;list-style:none;font-size:15px;font-weight:600}
.zh-links a,.zh-account a{color:#f2f3f5;text-decoration:none}.zh-links a:hover{color:#f39c37}
.zh-links .dropdown-toggle::after{margin-left:6px}
.zh-nav .dropdown-menu{background:#2f3239;border:1px solid #383c45;border-radius:8px;margin-top:10px}
.zh-nav .dropdown-item{color:#f2f3f5;font-weight:600;font-size:14px}.zh-nav .dropdown-item:hover{background:#383c45;color:#f39c37}
.zh-account{display:flex;align-items:center;gap:14px;padding-left:24px;border-left:1px solid #383c45;font-size:15px;font-weight:700}
.zh-user{display:inline-flex;align-items:center;gap:10px;color:#f39c37}
.zh-user img{width:34px;height:34px;border-radius:50%}
.zh-login{color:#f39c37!important}
.zh-burger{display:none;margin-left:auto;width:44px;height:44px;align-items:center;justify-content:center;background:none;color:#f2f3f5;border:1px solid #4a4f5a;border-radius:6px}
/* hero */
.zh-hero{position:relative;min-height:600px;display:flex;flex-direction:column;justify-content:flex-end;background-image:linear-gradient(180deg,rgba(20,24,34,.55) 0%,rgba(20,24,34,.35) 40%,rgba(35,37,43,1) 100%),url('{{ asset("assets/resources/media/img/website/hero-radar.jpg") }}');background-size:cover;background-position:center 30%}
.zh-hero-text{padding:0 64px 36px;max-width:1100px}
.zh-hero h1{font-size:68px;line-height:1.05;font-weight:800;letter-spacing:-.01em}
.zh-hero p{margin:20px 0 0;font-size:22px;font-weight:600;color:#dfe3ea}
.zh-cta{display:flex;flex-wrap:wrap;gap:14px;margin-top:30px}
.zh .zh-btn{display:inline-block;padding:14px 26px;background:#f39c37;color:#1b1d22;font-weight:800;font-size:16px;border-radius:6px;border:2px solid #f39c37}
.zh .zh-btn:hover{background:#ffc27a;border-color:#ffc27a;color:#1b1d22}
.zh .zh-btn-o{background:transparent;color:#f39c37}.zh .zh-btn-o:hover{background:rgba(243,156,55,.12);color:#ffc27a}
.zh-strip{display:flex;flex-wrap:wrap;align-items:center;gap:10px 32px;margin:0 64px 40px;padding:14px 22px;background:rgba(27,29,34,.92);border:1px solid #383c45;border-radius:8px;font-size:15px}
.zh-live{display:inline-flex;align-items:center;gap:8px;font-weight:800;letter-spacing:.08em;color:#7fe0a2}
.zh-live i{width:9px;height:9px;border-radius:50%;background:#7fe0a2;display:inline-block}
.zh-strip b{font-size:18px}
.zh-ic{color:#f39c37;margin-right:8px}.zh-break{flex-basis:100%;height:0;margin:-4px 0}.zh-grp{font-weight:700}.zh-grp .zh-mut{color:#dfe3ea}.zh-mut{color:#b4b9c4}
.zh-strip .zh-map{margin-left:auto;font-weight:700}
.zh-seg{display:inline-flex;border:1px solid #4a4f5a;border-radius:6px;overflow:hidden;margin-right:6px;vertical-align:middle}.zh-seg button{background:transparent;border:0;color:#b4b9c4;font:inherit;font-size:13px;padding:2px 9px;cursor:pointer}.zh-seg button.on{background:#f39c37;color:#1b1d22;font-weight:700}.zh-seg button:focus-visible{outline:2px solid #f39c37;outline-offset:-2px}
/* cards */
.zh-row{display:flex;flex-wrap:wrap;gap:28px;padding:20px 64px 8px}
.zh-row.r2{padding-top:28px}
.zh-card{flex:1 1 420px;min-width:0;padding:28px 30px;background:#2f3239;border:1px solid #383c45;border-radius:10px}
.zh-news{flex:1.2 1 460px;min-height:340px;display:flex;flex-direction:column;justify-content:flex-end;padding:32px;background:#1d2b47;border:1px solid #2e4468;border-radius:10px;background-size:cover;background-position:center}
.zh-kicker{font-size:13px;font-weight:800;letter-spacing:.1em;color:#f39c37}
.zh-news h2{margin:8px 0 10px!important;font-size:34px;line-height:1.15;font-weight:800}
.zh-news h2 a{color:#f2f3f5!important}
.zh-news p{margin:0 0 18px;font-size:17px;line-height:1.5;color:#dfe3ea}
.zh-more{font-weight:800}
.zh-card h2{font-size:24px;font-weight:800}
.zh-card .zh-sub{font-size:13px;color:#b4b9c4}
.zh-tiles{display:flex;gap:10px;margin:18px 0}
.zh-tile{flex:1;padding:12px;background:#383c45;border-radius:6px;text-align:center}
.zh-tile b{display:block;font-size:26px;font-weight:800}.zh-tile span{font-size:13px;color:#b4b9c4}
.zh-table{font-size:15px}.zh-tr{display:flex;padding:11px 4px;border-top:1px solid #424651}
.zh-th{font-size:13px;color:#b4b9c4;font-weight:700;border-top:0;border-bottom:1px solid #4a4f5a;padding:8px 4px}
.zh-tr .c1{flex:1.2}.zh-tr .c2{flex:1.4}.zh-tr .c3{flex:.8;text-align:right;color:#b4b9c4}.zh-th .c3{color:#b4b9c4}
.zh-badge{font-style:normal;font-size:11px;font-weight:800;padding:1px 7px;border-radius:10px;margin-left:6px;color:#1b1d22}
.zh-b-red{background:#ff8a80}.zh-b-amber{background:#f39c37}.zh-b-blue{background:#4aa8e8}
.zh-empty{padding:14px 4px;color:#b4b9c4}
.zh-card .zh-kicker.p{color:#4aa8e8}
.zh-card h2.t{margin:6px 0 14px;font-size:28px}
.zh-list{margin:0 0 18px;padding:0;list-style:none;font-size:16px}
.zh-list li{padding:11px 0;border-bottom:1px solid #424651}.zh-list li:last-child{border-bottom:0}
.zh-list a{font-weight:700}
/* stats */
.zh-stats{padding:36px 64px 8px}
.zh-stats-h{display:flex;align-items:baseline;gap:16px;margin-bottom:18px}
.zh-stats-h h2{font-size:30px;font-weight:800}.zh-stats-h a{font-weight:700}
.zh-stat-grid{display:flex;flex-wrap:wrap;gap:24px}
.zh-stat{flex:1 1 300px;min-width:0;padding:22px 24px;background:#2f3239;border:1px solid #383c45;border-radius:10px}
.zh-stat h3{margin:0 0 12px!important;font-size:16px;color:#f39c37!important;font-weight:800}
.zh-stat .row-i{display:flex;justify-content:space-between;gap:12px;padding:8px 0;border-bottom:1px solid #424651}
.zh-stat .row-i:last-child{border-bottom:0}.zh-stat .row-i b{white-space:nowrap}
.zh-note{margin:14px 0 0;font-size:13px;color:#b4b9c4}
.zh-login-box{font-size:15px;color:#dfe3ea}
.zh-login-box a.zh-btn{margin-top:12px;padding:10px 18px;font-size:14px}
/* mission */
.zh-mission{padding:48px 64px 56px}.zh-mission>div{max-width:820px}
.zh-mission h2{font-size:34px;line-height:1.15;font-weight:800;color:#f39c37!important;margin-bottom:14px!important}
.zh-mission p{margin:0 0 20px;font-size:18px;line-height:1.6;color:#dfe3ea}
/* dark footer on the homepage */
body.zh-body .page-footer{background:#16181c!important;border-top:1px solid #2c2f36;color:#b4b9c4!important}
body.zh-body .page-footer a.black-text,body.zh-body .page-footer .text-body{color:#f2f3f5!important}
body.zh-body .page-footer .text-muted{color:#b4b9c4!important}
@media (max-width:991px){
 .zh-nav{padding:0 16px;min-height:64px}.zh-links,.zh-account{display:none}.zh-burger{display:inline-flex}.zh-brand img{height:36px}
 .zh-hero{min-height:520px}.zh-hero-text{padding:0 16px 24px}.zh-hero h1{font-size:38px;line-height:1.08}.zh-hero p{font-size:17px;margin-top:14px}
 .zh-cta{flex-direction:column;gap:10px}.zh .zh-btn{text-align:center;padding:13px}
 .zh-strip{margin:0 16px 24px;padding:12px 14px;font-size:14px;gap:8px 18px}.zh-strip .zh-map{margin-left:0;width:100%}
 .zh-row,.zh-row.r2{padding:12px 16px 0;gap:16px}.zh-card,.zh-news{padding:22px;flex-basis:100%}.zh-news{min-height:260px}.zh-news h2{font-size:24px}
 .zh-stats{padding:24px 16px 0}.zh-stats-h h2{font-size:24px}.zh-stat-grid{gap:16px}
 .zh-mission{padding:28px 16px 36px}.zh-mission h2{font-size:26px}.zh-mission p{font-size:16px}
}
</style>
@endpush
@section('content')
<div class="zh">
    <section class="zh-hero">
        <div class="zh-hero-text">
            <h1>Your gateway across the North Atlantic.</h1>
            <p>Welcome to Gander Oceanic. Oceanic control for the Gander and Shanwick areas on VATSIM.</p>
            <div class="zh-cta">
                <a href="/pilots" class="zh-btn">Fly the North Atlantic</a>
                @if (!Auth::check() || Auth::user()->can('start-application'))
                    <a href="{{ route('training.applications.apply') }}" class="zh-btn zh-btn-o">Become a controller</a>
                @endif
            </div>
        </div>
        @if(!empty($statsError))
        @hasanyrole('Administrator|Senior Staff|Web Team')
        <!-- stats error: {{ $statsError }} -->
        @endhasanyrole
        @endif
        <div class="zh-strip">
            <span class="zh-live"><i></i>LIVE</span>
            <span class="zh-grp" title="Aircraft currently inside each oceanic area"><i class="fas fa-plane zh-ic"></i><span class="zh-mut">Aircraft</span></span>
            <span><b class="js-czqo">{{ $ctpAircraft->czqo }}</b> <span class="zh-mut">Gander</span></span>
            <span><b class="js-eggx">{{ $ctpAircraft->eggx }}</b> <span class="zh-mut">Shanwick</span></span>
            <span><b class="js-kzny">{{ $ctpAircraft->kzny }}</b> <span class="zh-mut">New York</span></span>
            <span class="zh-mut" title="Controllers online now"><i class="fas fa-headset zh-ic"></i>Online now: <b id="online-now" style="color:#f2f3f5;font-size:15px;">{{ $controllers->count() ? $controllers->pluck('callsign')->implode(', ') : 'no controllers' }}</b></span>
            @if($last24)
            <span class="zh-break"></span>
            <span class="zh-mut" id="zh-period-box" title="Flights that spent 15 minutes or more in Gander, Shanwick or New York oceanic airspace, and controller time on oceanic positions. Switch between the last 24 hours and this month so far (from 00:00 Zulu on the 1st)."><i class="fas fa-history zh-ic"></i><span class="zh-seg" role="group" aria-label="Statistics period"><button type="button" class="on" data-period="day">Last 24h</button>@if($month)<button type="button" data-period="month">This month</button>@endif</span>: <b class="js-mov">{{ $last24['movements'] }}</b> movements &middot; <b class="js-atc">{{ $last24['atc_hours'] }}</b> h of ATC <span class="zh-mut">(<span class="js-pos">{{ $last24['positions'] }}</span> positions)</span></span>
            @endif
            @if($forecast['total'] !== null)
                <span class="zh-mut" title="Estimate from filed flight plans on the VATSIM data feed using an oceanic route: flights due to depart in the next 6 hours, plus flights already airborne and heading for oceanic airspace. Pilots who connect late or never fly are not predicted."><i class="fas fa-hourglass-half zh-ic"></i>Expected next 6h: <b class="js-exp">~{{ $forecast['total'] }}</b> flights <span class="zh-mut">(<span class="js-dep">{{ $forecast['departing'] }}</span> departing, <span class="js-app">{{ $forecast['approaching'] }}</span> en route)</span></span>
            @endif
            <a class="zh-map" href="{{ route('map') }}">View airspace map &rarr;</a>
        </div>
    </section>

    {{-- CTP: event underway --}}
    @if($ctpMode == 2)
    <div class="zh-wrap" style="padding-top:20px">
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
                            @include('partials.homepage.ctp-controllers')
                        </x>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif
    </div>
    @endif

    @if($ctpMode == false || $ctpMode == 1)
    @if ($ctpMode == 1)
    <div class="zh-wrap" style="padding-top:20px">
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
    </div>
    @endif

    <section class="zh-row">
        @if ($news)
            <article class="zh-news" style="background-image:linear-gradient(180deg,rgba(20,24,34,.15),rgba(20,24,34,.88)),url('{{ $news->image ?: 'https://ganderoceanic.ca/assets/staff_uploads/news_article/default.png' }}')">
                <div class="zh-kicker">NEWS</div>
                <h2><a href="{{ route('news.articlepublic', $news->slug) }}">{{ $news->title }}</a></h2>
                <p>{{ $news->summary }}</p>
                <a class="zh-more" href="{{ route('news.articlepublic', $news->slug) }}">Read the article &rarr;</a>
            </article>
        @endif
        <div class="zh-card">
            <div style="display:flex;align-items:baseline;gap:12px;flex-wrap:wrap"><h2>Current activity</h2><span class="zh-sub">Updated <span id="timer-info">{{ \Carbon\Carbon::now('UTC')->format('Hi') }}Z</span></span></div>
            <div class="zh-tiles">
                <div class="zh-tile"><b class="js-czqo">{{ $ctpAircraft->czqo }}</b><span>Gander OCA aircraft</span></div>
                <div class="zh-tile"><b class="js-eggx">{{ $ctpAircraft->eggx }}</b><span>Shanwick OCA aircraft</span></div>
                <div class="zh-tile"><b class="js-kzny">{{ $ctpAircraft->kzny }}</b><span>New York OCA aircraft</span></div>
            </div>
            <div id="controller-info">@include('partials.homepage.general-controllers')</div>
        </div>
    </section>
    @endif

    <section class="zh-row r2">
        <div class="zh-card">
            <div class="zh-kicker p">FOR PILOTS</div>
            <h2 class="t">Crossing the Atlantic?</h2>
            <ul class="zh-list">
                <li><a href="https://nattrak.vatsim.net/">Get your oceanic clearance</a> <span class="zh-mut">with natTrak</span></li>
                <li><a href="{{ url('/pilots/tracks') }}">Today's NAT tracks</a></li>
                <li><a href="{{ url('/pilots/position-report') }}">Position report generator</a></li>
                <li><a href="/pilots">All pilot resources</a></li>
            </ul>
        </div>
        <div class="zh-card">
            <div class="zh-kicker">FOR CONTROLLERS</div>
            <h2 class="t">Want to control oceanic?</h2>
            <ul class="zh-list">
                <li><a href="{{ (!Auth::check() || Auth::user()->can('start-application')) ? route('training.applications.apply') : route('about.who-we-are') }}">How to join the team</a></li>
                <li><a href="{{ route('trainingcalendar.index') }}">Training calendar</a></li>
                <li><a href="https://knowledgebase.ganderoceanic.ca">ZQO knowledge base</a></li>
                @if(Auth::check() && Auth::user()->member_of_czqo !== 1)
                    <li><a href="" data-toggle="modal" data-target="#discordTopModal">Join our Discord community</a></li>
                @endif
            </ul>
            @if (!Auth::check() || Auth::user()->can('start-application'))
                <a href="{{ route('training.applications.apply') }}" class="zh-btn" style="padding:12px 24px">Apply now</a>
            @endif
        </div>
    </section>

    <section class="zh-stats">
        <div class="zh-stats-h"><h2>Gander OCA statistics</h2><a href="/stats">Full statistics &rarr;</a></div>
        <div class="zh-stat-grid">
            <div class="zh-stat">
                <h3>{{ \Carbon\Carbon::now()->format('F') }} top airlines</h3>
                @forelse ($topAirlines->take(3) as $a)
                    <div class="row-i"><span>{{ $a->info->icao }} {{ $a->info->name }}</span><b>{{ $a->current }}</b></div>
                @empty
                    <div class="zh-mut">No data available.</div>
                @endforelse
            </div>
            <div class="zh-stat">
                <h3>{{ \Carbon\Carbon::now()->format('Y') }} top aircraft types</h3>
                @forelse ($yearAircraft->take(3) as $a)
                    <div class="row-i"><span>{{ $a->info->code }} {{ $a->info->name }}</span><b>{{ $a->year }}</b></div>
                @empty
                    <div class="zh-mut">No data available.</div>
                @endforelse
            </div>
            <div class="zh-stat">
                <h3>{{ \Carbon\Carbon::now()->format('F') }} top controllers</h3>
                @auth
                    @forelse ($topControllers->take(3) as $c)
                        @php $h = floor($c->current); $m = str_pad(round(($c->current - $h) * 60), 2, '0', STR_PAD_LEFT); @endphp
                        <div class="row-i"><span>{{ $c->user ? $c->user->fullName('FL') : $c->cid }}</span><b>{{ $h > 0 ? $h.'h ' : '' }}{{ $m }}m</b></div>
                    @empty
                        <div class="zh-mut">No controller connections recorded.</div>
                    @endforelse
                @else
                    <div class="zh-login-box">Controller names are shown to signed-in members.<br><a href="{{ route('auth.connect.login') }}" class="zh-btn">Log in with VATSIM</a></div>
                @endauth
            </div>
        </div>
    </section>

    <section class="zh-mission">
        <div>
            <h2>We control the skies over the North Atlantic on VATSIM.</h2>
            <p>Gander Oceanic is VATSIM's coolest, calmest and most collected provider of oceanic control. Our worldwide team of skilled controllers gives pilots flying across the North Atlantic an expert, high-quality service. Our community of pilots and controllers extends its warmest welcome and wishes you all the best for your oceanic crossings.</p>
            <a class="zh-more" href="{{ route('about.who-we-are') }}">Who we are &rarr;</a>
        </div>
    </section>
</div>

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

<script>
(function () {
    function setAll(sel, v) { document.querySelectorAll(sel).forEach(function (e) { e.textContent = v; }); }
    var zhStats = { day: @json($last24), month: @json($month) };
    var zhPeriod = 'day';
    try { var saved = localStorage.getItem('zhStatsPeriod'); if (saved === 'month' && zhStats.month) zhPeriod = 'month'; } catch (e) { /* storage can be blocked */ }
    function renderPeriod() {
        var d = zhStats[zhPeriod] || zhStats.day;
        if (!d) return;
        setAll('.js-mov', d.movements); setAll('.js-atc', d.atc_hours); setAll('.js-pos', d.positions);
        document.querySelectorAll('#zh-period-box [data-period]').forEach(function (b) { b.classList.toggle('on', b.getAttribute('data-period') === zhPeriod); b.setAttribute('aria-pressed', b.getAttribute('data-period') === zhPeriod ? 'true' : 'false'); });
    }
    document.querySelectorAll('#zh-period-box [data-period]').forEach(function (b) {
        b.addEventListener('click', function () {
            zhPeriod = b.getAttribute('data-period');
            try { localStorage.setItem('zhStatsPeriod', zhPeriod); } catch (e) { /* ignore */ }
            renderPeriod();
        });
    });
    renderPeriod();
    function refreshCounts() {
        fetch('/api/update-homepage/general').then(function (r) { return r.json(); }).then(function (d) {
            setAll('.js-czqo', d.czqo); setAll('.js-eggx', d.eggx); setAll('.js-kzny', d.kzny);
            if (d.last24) { zhStats.day = d.last24; }
            if (d.month) { zhStats.month = d.month; }
            renderPeriod();
            if (d.forecast && d.forecast.total !== null) { setAll('.js-exp', '~' + d.forecast.total); setAll('.js-dep', d.forecast.departing); setAll('.js-app', d.forecast.approaching); }
            @if($ctpMode == 2)
            ['ganwick','czqo','eggx','lppo','bird','kzny'].forEach(function (k) {
                var e = document.getElementById(k + '-count'); if (e) e.innerText = d[k] + (k === 'ganwick' ? '' : ' Aircraft in FIR');
            });
            @endif
            var t = document.getElementById('timer-info');
            if (t) t.innerText = new Date().toISOString().slice(11, 16).replace(':', '') + 'Z';
        });
    }
    function refreshControllers() {
        fetch('/api/update-homepage/controllers/{{ $ctpMode == 2 ? 2 : 1 }}').then(function (r) { return r.text(); }).then(function (html) {
            var box = document.getElementById('controller-info');
            if (box) box.innerHTML = html;
            var tmp = document.createElement('div'); tmp.innerHTML = html;
            var d = tmp.querySelector('[data-online-callsigns]'), on = document.getElementById('online-now');
            if (d && on) on.textContent = d.getAttribute('data-online-callsigns') || 'no controllers';
        });
    }
    setInterval(refreshCounts, 60000);
    setInterval(refreshControllers, 60000);
})();
</script>
@endsection
