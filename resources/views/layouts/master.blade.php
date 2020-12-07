<!DOCTYPE HTML>
@php if (!isset($solidNavBar)) $solidNavBar = true @endphp
@php if (!isset($adminNavBar)) $adminNavBar = false @endphp
<html lang="en">
    <head>
        <!--
        {{App\Models\Settings\CoreSettings::where('id', 1)->firstOrFail()->sys_name}}
        {{App\Models\Settings\CoreSettings::where('id', 1)->firstOrFail()->release}} ({{App\Models\Settings\CoreSettings::where('id', 1)->firstOrFail()->sys_build}})
        Built on Bootstrap 4 and Laravel 6

        Written by Liesel D

          sSSs. sSSSSSs   sSSSs     sSSSs
         S           s   S     S   S     S
        S           s   S       S S       S
        S          s    S       S S       S
        S         s     S       S S       S
         S       s       S   s S   S     S
          "sss' sSSSSSs   "sss"ss   "sss"

        For Flight Simulation Use Only - Not To Be Used For Real World Navigation. All content on this web site may not be shared, copied, reproduced or used in any way without prior express written consent of Gander Oceanic. © Copyright {{App\Models\Settings\CoreSettings::where('id', 1)->firstOrFail()->copyright_year}} Gander Oceanic, All Rights Reserved.

        Taking a peek under the hood and like what you see? Want to help out? Send Liesel an email! l.downes@ganderoceanic.com
        -->
        <!--Metadata-->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!--Rich Preview Meta-->
        <title>@yield('title', '')Gander Oceanic OCA</title>
        <meta name="description" content="@yield('description', '')">
        <meta name="theme-color" content="#0080c9">
        <meta name="og:title" content="@yield('title', '')Gander Oceanic OCA">
        <meta name="og:description" content="@yield('description', '')">
        <meta name="og:image" content="@yield('image','https://cdn.ganderoceanic.com/resources/media/img/brand/sqr/ZQO_SQ_TSPBLUE.png')">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
        <!-- Bootstrap core CSS -->
        <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.1.3/materia/bootstrap.min.css" rel="stylesheet" integrity="sha384-5bFGNjwF8onKXzNbIcKR8ABhxicw+SC1sjTh6vhSbIbtVgUuVTm2qBZ4AaHc7Xr9" crossorigin="anonymous">        <!-- Material Design Bootstrap -->
        <!-- Material Design Bootstrap -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.11/css/mdb.min.css" rel="stylesheet">
        <!-- JQuery -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <!-- Bootstrap tooltips -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
        <!-- Bootstrap core JavaScript -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <!-- MDB core JavaScript -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.11/js/mdb.min.js"></script>
        <!--CZQO specific CSS and JS-->
        @if (Auth::check())
        @switch (Auth::user()->preferences)
            @case("default")
            <link href="{{ asset('css/czqomd.css') }}" rel="stylesheet">
            @break
            @default
            <link href="{{ asset('css/czqomd.css') }}" rel="stylesheet">
        @endswitch
        @else
        <link href="{{ asset('css/czqomd.css') }}" rel="stylesheet">
        @endif
        <script src="{{asset('js/czqo.js')}}"></script>
        <!--Leaflet-->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js" integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg==" crossorigin=""></script>
        <script src="{{asset('/js/leaflet.rotatedMarker.js')}}"></script>
        <!--TinyMCE-->
        <script src="https://cdn.tiny.cloud/1/f3uqjs9q4n1tj4k8m8xwcz4yptz6wvgw2mn1jg2cf4iuaqkw/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
        <!--DataTables-->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.css"/>
        <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.js"></script>
        <!--Date picker-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <!--SimpleMDE-->
        <link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
        <script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>
        <!--Jarallax-->
        <script src="https://unpkg.com/jarallax@1/dist/jarallax.min.js"></script>
        <script src="https://unpkg.com/jarallax@1/dist/jarallax-video.min.js"></script>
        <script src="https://unpkg.com/jarallax@1/dist/jarallax-element.min.js"></script>
        <!--Toastify-->
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
        <!--Image picker and masonry-->
        <script src="{{asset('js/image-picker.min.js')}}"></script>
        <link rel="stylesheet" href="{{asset('css/image-picker.css')}}">
        <script src="{{asset('js/masonry.pkgd.min.js')}}"></script>
        <!--Chart js-->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js" integrity="sha256-R4pqcOYV8lt7snxMQO/HSbVCFRPMdrhAFMH+vr9giYI=" crossorigin="anonymous"></script>
    </head>
    <body class="d-flex flex-column min-vh-100" @if(Auth::check() && Auth::user()->preferences) @if(Auth::user()->preferences->accent_colour) data-accent="{{Auth::user()->preferences->accent_colour}}" @endif data-theme="{{Auth::user()->preferences->ui_mode}}" @else data-theme="light" @endif>
    <!--Header-->
    @include('maintenancemode::notification')
    @if (\App\Models\Settings\CoreSettings::where('id', 1)->firstOrFail()->banner)
        <div class="alert alert-{{\App\Models\Settings\CoreSettings::where('id', 1)->firstOrFail()->bannerMode}}" style="margin: 0; border-radius: 0; border: none;">
            <div class="text-center align-self-center">
                <a href="{{\App\Models\Settings\CoreSettings::where('id', 1)->firstOrFail()->bannerLink}}"><span style="margin: 0;">{{\App\Models\Settings\CoreSettings::where('id', 1)->firstOrFail()->banner}}</span></a>
            </div>
        </div>
    @endif
    <header>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <script>
                    Toastify({
                        text: "Error - {{$error}}",
                        duration: 5000,
                        close: true,
                        gravity: "bottom", // `top` or `bottom`
                        position: 'right', // `left`, `center` or `right`
                        backgroundColor: '#ff4444',
                        offset: {
                            x: 100, // horizontal axis - can be a number or a string indicating unity. eg: '2em'
                            y: 50 // vertical axis - can be a number or a string indicating unity. eg: '2em'
                        },
                        stopOnFocus: true, // Prevents dismissing of toast on hover
                    }).showToast();
            </script>
            @endforeach
        @endif
        @if (\Session::has('success'))
            <script>
                Toastify({
                    text: "{{\Session::get('success')}}",
                    duration: 5000,
                    close: true,
                    gravity: "bottom", // `top` or `bottom`
                    position: 'right', // `left`, `center` or `right`
                    backgroundColor: '#00C851',
                    offset: {
                        x: 100, // horizontal axis - can be a number or a string indicating unity. eg: '2em'
                        y: 50 // vertical axis - can be a number or a string indicating unity. eg: '2em'
                    },
                    stopOnFocus: true, // Prevents dismissing of toast on hover
                }).showToast();
            </script>
        @endif
        @if (\Session::has('error'))
        <script>
            Toastify({
                text: "{{\Session::get('error')}}",
                duration: 5000,
                close: true,
                gravity: "bottom", // `top` or `bottom`
                position: 'right', // `left`, `center` or `right`
                backgroundColor: '#ff4444',
                offset: {
                    x: 100, // horizontal axis - can be a number or a string indicating unity. eg: '2em'
                    y: 50 // vertical axis - can be a number or a string indicating unity. eg: '2em'
                },
                stopOnFocus: true, // Prevents dismissing of toast on hover
            }).showToast();
        </script>
        @endif
        @if (\Session::has('info'))
        <script>
            Toastify({
                text: "{{\Session::get('info')}}",
                duration: 5000,
                close: true,
                gravity: "bottom", // `top` or `bottom`
                position: 'right', // `left`, `center` or `right`
                backgroundColor: '#33b5e5',
                offset: {
                    x: 100, // horizontal axis - can be a number or a string indicating unity. eg: '2em'
                    y: 50 // vertical axis - can be a number or a string indicating unity. eg: '2em'
                },
                stopOnFocus: true, // Prevents dismissing of toast on hover
            }).showToast();
        </script>
        @endif
        @if($adminNavBar)
            <nav class="navbar navbar-expand-xl navbar-light transparent shadow-none p-0" style="min-height:59px; z-index:999;">
                @include('layouts.navbar-admin')
            </nav>
        @elseif(!$solidNavBar)
            <div class="d-none d-xl-block">
                <nav id="czqoHeaderLight" class="navbar navbar-expand-xl navbar-dark transparent shadow-none p-0" style="min-height:74px; z-index:999;">
                    @include('layouts.navbar-main')
                </nav>
            </div>
            <div class="d-xl-none">
                <nav id="czqoHeaderLight" class="navbar navbar-expand-lg navbar-dark transparent p-0 shadow-none" style="min-height:74px; z-index:999">
                    @include('layouts.navbar-main')
                </nav>
            </div>
        @else
            <nav id="czqoHeaderLight" class="navbar navbar-expand-lg navbar-dark blue p-0 shadow-none" style="min-height:74x;">
                @include('layouts.navbar-main')
            </nav>
        @endif
    </header>
    <!--End header-->
    <div class="flex-fill" id="czqoContent" @if(!$solidNavBar) style="margin-top: calc(-74px + -0.5rem);" @endif>
        @yield('content')
    </div>
    <!-- Footer -->
    <!-- Footer -->
    <footer class="page-footer text-dark font-small py-4" style="bottom:0">
        <div class="container">
            <p style="font-size: 0.9em;">For Flight Simulation Use Only - Not To Be Used For Real World Navigation. Any and all proprietary content available on this website may not be shared, copied, reproduced or used in any way without providing credit to the Gander Oceanic OCA.</p>
            <p style="font-size: 0.9em;">Copyright © {{App\Models\Settings\CoreSettings::where('id', 1)->firstOrFail()->copyright_year}} Gander Oceanic OCA - All Rights Reserved.</p>
            <div class="flex-left mt-3">
                <a href="{{route('feedback.create')}}" class="font-weight-bold black-text">Feedback</a>
                &nbsp;
                •
                &nbsp;
                <a href="{{route('about.core')}}" class="font-weight-bold black-text">About</a>
                &nbsp;
                •
                &nbsp;
                <a href="{{route('privacy')}}" class="font-weight-bold black-text">Privacy Policy</a>
                &nbsp;
                •
                &nbsp;
                <a href="https://github.com/gander-oceanic-fir-vatsim/czqo-core" class="font-weight-bold black-text">GitHub</a>
                &nbsp;
                •
                &nbsp;
                <a href="{{url('/branding')}}" class="font-weight-bold black-text">Branding</a>
                &nbsp;
                •
                &nbsp;
                <a href="#" data-toggle="modal" data-target="#contactUsModal" class="font-weight-bold black-text">Contact Us</a>
                &nbsp;
                •
                &nbsp;
                <a href="https://vatsim.net" class="font-weight-bold black-text">VATSIM</a>
                &nbsp;
                •
                &nbsp;
                <a href="https://vatcan.ca" class="font-weight-bold black-text">VATCAN</a>
            </div>
            <div style="margin-top: 40px;">
                <img style="height: 20px;" src="https://upload.wikimedia.org/wikipedia/commons/8/8a/LGBT_Rainbow_Flag.png" alt="">
                <img style="height: 20px;" src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b0/Transgender_Pride_flag.svg/1280px-Transgender_Pride_flag.svg.png" alt="">
                <img src="https://cdn.discordapp.com/attachments/482817676067209217/695255571623837837/220px-Bisexual_Pride_Flag.png" style="height:20px;" alt="">
            </div>
            <a href="" data-toggle="modal" data-target="#lgbtModal" class="text-muted mt-3" style="display:block;">Gander Oceanic stands with the LGBTIQA+ community on VATSIM</p>
        </div>
    </footer>
    <!-- Footer -->
    <!-- LGBT modal-->
    <div class="modal fade" id="lgbtModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Gander Oceanic displays the rainbow, transgender, and bisexual flags to remind LGBTIQA+ VATSIM members who may feel out of place in this community that they are welcome and celebrated here.</p>
                    <p>As an organisation with LGBTIQA+ members, we recognise the importance of creating an welcoming environment, and a small symbol can go a long way to achieve that.</p>
                    <p>It is not a political statement nor an act of protest and we appreciate your support in creating a VATSIM community open to all, regardless of gender identity, sexuality, age, or background.</p>
                    <p>Thank you to the Jacksonville and Cleveland ARTCCs in VATUSA for joining us in this!</p>
                </div>
            </div>
        </div>
    </div>
    <!-- End LGBT modal-->
    <!-- Contact us modal-->
    <div class="modal fade" id="contactUsModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Contact CZQO</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    To contact us, please do one of the following:
                    <ol>
                        <li>Login and open a <a href="TODO: TicketURL">support ticket.</a></li>
                        <li>Head to the <a href="{{route('staff')}}">staff page</a> and email the relevant staff member.</li>
                        <li>Join our <a href="https://discord.gg/MvPVAHP">Discord server</a> and ask in the #westons-at-the-airport channel.</li>
                    </ol>
                    <b>If your query is related to ATC coverage for your event, please visit <a href="{{route('events.index')}}">this page.</a></b>
                </div>
            </div>
        </div>
    </div>
    <!-- End contact us modal-->
    @if (\Session::has('error-modal'))
    <!-- Error modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"><span class="font-weight-bold red-text"><i class="fas fa-exclamation-circle"></i> An error occurred...</span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{\Session::get('error-modal')}}
                </div>
            </div>
        </div>
    </div>
    <script>
    $("#errorModal").modal();
    </script>
    <!-- End error modal -->
    @endif
    <!-- Start Discord (top nav) modal -->
    <div class="modal fade" id="discordTopModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header pb-2" style="border:none; text-align:center;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-center flex-column">
                        <h3 class="font-weight-bold blue-text">Join the Gander Oceanic Discord</h3>
                        <ul class="list-unstyled mt-4">
                            <li class="w-100">
                                <div class="grey lighten-3 p-4" style="border-radius: 20px;">
                                    <div class="d-flex flex-row">
                                        <img style="height: 40px; margin-right: 20px;" src="https://cdn.ganderoceanic.com/resources/media/img/brand/sqr/ZQO_SQ_TSPBLUE.png" alt="">
                                        <p class="font-weight-bold" style="width: 75%; text-align:left; font-size: 1.1em;">Chat with our Gander Oceanic controller and pilot community</p>
                                    </div>
                                </div>
                            </li>
                            <li class="w-100">
                                <div class="grey lighten-3 p-4" style="border-radius: 20px;">
                                    <div class="d-flex flex-row">
                                        <i style="font-size:35px; margin-right:20px;" class="far fa-newspaper blue-text"></i>
                                        <p class="font-weight-bold" style="width: 75%; text-align:left; font-size: 1.1em;">Get the latest CZQO and VATSIM news, and other relevant updates</p>
                                    </div>
                                </div>
                            </li>
                            <li class="w-100">
                                <div class="grey lighten-3 p-4" style="border-radius: 20px;">
                                    <div class="d-flex flex-row">
                                        <i style="font-size:35px; margin-right:20px;" class="fas fa-user-friends blue-text"></i>
                                        <p class="font-weight-bold" style="width: 75%; text-align:left; font-size: 1.1em;">Find people to have a controlling<br>session with</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        @auth
                            @if(Auth::user()->hasDiscord() && !Auth::user()->memberOfCzqoGuild())
                            <a href="{{route('me.discord.join')}}" class="class btn btn-primary mt-3">Join The Community</a>
                            <p class="text-muted text-center mt-2">You will be redirected to Discord to allow us to add you to our server. Information collected is shown on the Discord authorisation screen. Read our privacy policy for details.</p>
                            @elseif (Auth::user()->hasDiscord() && Auth::user()->memberOfCzqoGuild())
                            <p class="mt-1"><img style="border-radius:50%; height: 30px;" class="img-fluid" src="{{Auth::user()->getDiscordAvatar()}}" alt="">&nbsp;&nbsp;{{Auth::user()->getDiscordUser()->username}}<span style="color: #d1d1d1;">#{{Auth::user()->getDiscordUser()->discriminator}}</span></p>
                            <p class="text-muted text-center mt-2">You are already a member of the Gander Oceanic Discord. To unlink your account and leave the server, go to myCZQO.</p>
                            @else
                                <a href="{{route('me.discord.link', ['param' => 'server_join_process'])}}" class="class btn btn-primary mt-3">Link Your Discord To Join</a>
                                <p class="text-muted text-center mt-2">You will be redirected to Discord to connect your account, and then prompted to allow us to add you to our server. Information collected is shown on the Discord authorisation screen. Read our privacy policy for details.</p>
                            @endif
                        @else
                        <a href="{{route('auth.connect.login')}}" class="class btn btn-primary mt-3">Login With VATSIM To Join</a>
                        <p class="text-muted text-center mt-2">Once logged in, you can connect your Discord account and join the community in myCZQO.</p>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Discord (top nav) modal -->
    <!-- Start Connect modal -->
    <div class="modal fade" id="connectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Login with VATSIM</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Gander Oceanic uses VATSIM Connect (auth.vatsim.net) for authentication. This is similar to SSO, but allows you to select specific data to share with us. Click 'Login' below to continue.</p>
                    <p><small>If you are having issues with Connect, please send an email to the Deputy FIR Chief and use <a href="{{route('auth.sso.login')}}">SSO to login.</a></small></p>
                </div>
                <div class="modal-footer">
                    <a href="{{route('auth.connect.login')}}" role="button" class="btn bg-czqo-blue-light">Login</a>
                </div>
            </div>
        </div>
    </div>
    <!-- End Connect modal -->

    <!-- Frame Modal Bottom -->
    <div class="modal fade top" id="mobileNavBarModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
      aria-hidden="true">

      <!-- Add class .modal-frame and then add class .modal-bottom (or other classes from list above) to set a position to the modal -->
      <div class="modal-dialog modal-frame modal-top" role="document">
        <div class="modal-content">
            <div class="modal-body px-5 py-4">
                <h4 class="blue-text">Hello{{ Auth::check() ? ', ' . Auth::user()->fullName('F') . '!' : '!' }}</h4>
                <hr>
                <ul class="list-unstyled">
                    <li class="nav-item">
                        <a class="nav-link text-body" href="{{route('my.index')}}">
                            myCZQO
                        </a>
                    </li>
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
                            <a class="dropdown-item {{ Request::is('roster/solo-certs') ? 'active white-text' : '' }}" href="{{route('solocertifications.public')}}" aria-expanded="false">Solo Certifications</a>
                        </div>
                    </li>
                    <li class="nav-item {{ Request::is('news') ? 'active white-text' : '' }} {{ Request::is('news/*') ? 'active white-text' : '' }}">
                        <a class="nav-link text-body" href="{{route('news')}}">
                            News
                        </a>
                    </li>
                    <li class="nav-item {{ Request::is('events/*') || Request::is('events') ? 'active' : '' }}">
                        <a href="{{route('events.index')}}" class="nav-link text-body">Events</a>
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
                            <a class="dropdown-item {{ Request::is('pilots/tracks/event') ? 'active white-text' : ''}}" href="{{url('/pilots/tracks/event')}}">Event NAT Tracks</a>
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
                        <a class="nav-link text-body" href="{{route('feedback.create')}}" aria-expanded="false">Feedback</a>
                    </li>
                </ul>
                <hr>
                <div class="d-flex flex-row justify-content-between">
                    <div>
                        <img src="{{Auth::user()->avatar()}}" style="height: 27px; width: 27px; margin-right: 7px; margin-bottom: 3px; border-radius: 50%;">&nbsp;<span>{{Auth::user()->fullName("FL")}}</span>
                    </div>
                    <div>
                        <a href="{{ route('auth.logout') }}" class="red-text"><i class="fas fa-sign-out-alt mr-2"></i>Log Out</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Frame Modal Bottom -->
    <script>
        $("blockquote").addClass('blockquote');
        $(function () {
        $('[data-toggle="tooltip"]').tooltip()
        })
        $.urlParam = function(name){
            var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
            return results[1] || 0;
        }

        if ($.urlParam('discord') == '1') {
            $("#discordTopModal").modal();
        }


    </script>
    </body>
</html>
