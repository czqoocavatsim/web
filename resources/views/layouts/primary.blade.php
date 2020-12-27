<!DOCTYPE HTML>
@php if (!isset($solidNavBar)) $solidNavBar = true @endphp
@php if (!isset($adminNavBar)) $adminNavBar = false @endphp
<html lang="en">

    <head>
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
        <link href="{{ asset('css/czqomd.css') }}" rel="stylesheet">
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

        <header>
            @if($adminNavBar)
                <!--Admin nav bar-->
                <nav id="czqoHeaderLight" class="navbar navbar-expand-xl navbar-light transparent shadow-none p-0" style="min-height:59px; z-index:999;">
                    @include('layouts.navbar-admin')
                </nav>
            @elseif(!$solidNavBar)
                <!--Non solid nav bar-->
                <div>
                    <nav id="czqoHeaderLight" class="navbar navbar-expand-xl navbar-dark transparent shadow-none p-0" style="min-height:74px; z-index:999;">
                        @include('layouts.navbar-main')
                    </nav>
                </div>
            @else
                <!--Solid nav bar-->
                <nav id="czqoHeaderLight" class="navbar navbar-expand-lg navbar-light p-0 shadow-none" style="min-height:74x;">
                    @include('layouts.navbar-main', ['logo' => 'blue'])
                </nav>
            @endif

        </header>
        <main class="flex-fill" @if(!$solidNavBar) style="margin-top: calc(-74px + -0.5rem);" @endif>
            @yield('content')
        </main>

        <!-- Start footer-->
        <footer class="page-footer text-dark font-small" style="bottom:0; background: rgb(239, 239, 239)">
            <div class="container py-5">
                <div class="d-none d-md-block">
                    <p class="mb-3">Copyright (C) Gander Oceanic OCA {{App\Models\Settings\CoreSettings::where('id', 1)->firstOrFail()->copyright_year}}. All Rights Reserved.<br>Not to be used for real world navigation. Flight simulation only.</p>
                    <div class="flex-left my-4">
                        <a href="{{route('index')}}" class="font-weight-bold black-text">Feedback</a>
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
                    <div class="d-flex flex-row justify-content-between align-items-end">
                        <div>
                            <div>
                                <img style="height: 20px;" src="https://upload.wikimedia.org/wikipedia/commons/8/8a/LGBT_Rainbow_Flag.png" alt="">
                                <img style="height: 20px;" src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b0/Transgender_Pride_flag.svg/1280px-Transgender_Pride_flag.svg.png" alt="">
                                <img src="https://cdn.discordapp.com/attachments/482817676067209217/695255571623837837/220px-Bisexual_Pride_Flag.png" style="height:20px;" alt="">
                            </div>
                            <a href="" data-toggle="modal" data-target="#lgbtModal" class="text-muted mt-3" style="display:block;">Gander Oceanic stands with the LGBTIQA+ community on VATSIM</a>
                        </div>
                        <div style="d-flex flex-row align-items-center">
                            <a href="" data-toggle="modal" data-target="#reportAnIssueModal" class="red-text" style="font-weight: 700; font-size:1.1em;"><i class="far fa-times-circle mr-2 fa-fw"></i>Report an issue</a>
                        </div>
                    </div>
                </div>
                <div class="d-md-none text-center">
                    <p class="mb-3">Copyright (C) Gander Oceanic OCA {{App\Models\Settings\CoreSettings::where('id', 1)->firstOrFail()->copyright_year}}. All Rights Reserved.<br>Not to be used for real world navigation. Flight simulation only.</p>
                    <ul class="list-unstyled">
                        <li>
                            <a class="text-body fw-600" href="https://vatcan.ca">VATCAN</a>
                        </li>
                        <li>
                            <a class="text-body fw-600" href="https://vatsim.net">VATSIM</a>
                        </li>
                        <li class="mt-3">
                            <a class="text-body fw-600" href="" data-target="#aboutCoreModal" data-toggle="modal">About CZQO Core</a>
                        </li>
                        <li>
                            <a class="text-body fw-600" href="{{route('policies')}}#policyEmbed3">Privacy Policy</a>
                        </li>
                    </ul>
                    <div>
                    <div>
                        <img style="height: 20px;" src="https://upload.wikimedia.org/wikipedia/commons/8/8a/LGBT_Rainbow_Flag.png" alt="">
                        <img style="height: 20px;" src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b0/Transgender_Pride_flag.svg/1280px-Transgender_Pride_flag.svg.png" alt="">
                        <img src="https://cdn.discordapp.com/attachments/482817676067209217/695255571623837837/220px-Bisexual_Pride_Flag.png" style="height:20px;" alt="">
                    </div>
                    <a href="" data-toggle="modal" data-target="#lgbtModal" class="text-muted mt-3 mb-4" style="display:block;">Gander Oceanic stands with the LGBTIQA+ community on VATSIM</a>
                    </div>
                    <div style="d-flex flex-row align-items-center">
                        <a href="" data-toggle="modal" data-target="#reportAnIssueModal" class="red-text" style="font-weight: 700; font-size:1.1em;"><i class="far fa-times-circle mr-2 fa-fw"></i>Report an issue</a>
                    </div>
                </div>
            </div>
        </footer>
        <!--End footer-->

    </body>

    <!--Toasify notifications-->
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
    <!--End Toastify notifications-->

    <!-- LGBT modal-->
    <div class="modal fade" id="lgbtModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span data-toggle="tooltip" title="Close dialog" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body fw-500">
                    <h3 class="fw-900 mb-3" style="background-image: linear-gradient(to left, violet, indigo, blue, green, yellow, orange, red);   -webkit-background-clip: text; color: transparent;">We stand with the LGBTIQA+ community on VATSIM.</h3>
                    <p>Gander Oceanic displays the rainbow, transgender, and bisexual flags to remind LGBTIQA+ VATSIM members who may feel out of place in this community that they are welcome and celebrated here.</p>
                    <p>As an organisation with LGBTIQA+ members, we recognise the importance of creating an welcoming environment, and a small symbol can go a long way to achieve that.</p>
                    <p>It is not a political statement nor an act of protest and we appreciate your support in creating a VATSIM community open to all, regardless of gender identity, sexuality, age, or background.</p>
                    <p>Thank you to the Jacksonville and Cleveland ARTCCs in VATUSA for joining us in this!</p>
                </div>
            </div>
        </div>
    </div>
    <!-- End LGBT modal-->

    <!-- Report an issue modal-->
    <div class="modal fade" id="reportAnIssueModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span data-toggle="tooltip" title="Close dialog" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body fw-500">
                    <h3 class="fw-800 mb-3 red-text"><i class="far fa-times-circle mr-2 fa-fw"></i>Report an issue</h3>
                    <h5 class="fw-700 mt-4 mb-2 blue-text"><i class="fa fa-chevron-right mr-2 fa-fw"></i>Bug or unintended behaviour</h5>
                    <p>
                        If you encounter a bug on a page, or something isn't working as expected, we want to hear about it! <a href="https://forms.gle/DgcgVb2g7zSioeMy9">Fill out our bug report form here.</a>
                    </p>
                    <p>Current URL: <pre>{{Request::url()}}</pre></p>
                    <h5 class="fw-700 mt-4 mb-2 blue-text"><i class="fa fa-chevron-right mr-2 fa-fw"></i>Feature requests</h5>
                    <p>
                        We'd also love to hear your ideas for what to add. To suggest a new feature, please contact the IT Director via email.
                    </p>
                    <h5 class="fw-700 mt-4 mb-2 blue-text"><i class="fa fa-chevron-right mr-2 fa-fw"></i>Anything else</h5>
                    <p>
                        Please contact the IT Director via email.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- End report an issue modal-->

    <!-- About modal-->
    <div class="modal fade" id="aboutCoreModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span data-toggle="tooltip" title="Close dialog" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body fw-500 p-5">
                    <div class="d-flex flex-row justify-content-center">
                        <img src="https://cdn.ganderoceanic.com/resources/media/img/brand/sqr/ZQO_SQ_TSPBLUE.png" class="mr-3" style="width: 125px; height: 125px;" alt="">
                        <div class="d-flex flex-column">
                            <h1 class="heading blue-text font-weight-bold display-5">Gander Oceanic Core</h1>
                            <p style="font-size: 1.4em;" class="mb-1">The website for VATSIM's<br>Gander Oceanic OCA</p>
                            <p class="text-muted" style="font-size: 0.9em;">
                                Version {{App\Models\Settings\CoreSettings::where('id', 1)->firstOrFail()->release}} ({{App\Models\Settings\CoreSettings::where('id', 1)->firstOrFail()->sys_build}})
                            </p>
                            <div class="d-flex flex-column mt-3">
                                <a href="https://github.com/czqoocavatsim" class="text-body d-flex flex-row align-items-center mb-3">
                                    <i class="fab fa-2x fa-github mr-2"></i> GitHub
                                </a>
                                <a href="https://github.com/czqoocavatsim/czqo-core/releases" class="text-body d-flex flex-row align-items-center mb-3">
                                    <i class="fas fa-history fa-2x mr-2"></i> Change Log
                                </a>
                                <a href="https://dev.ganderoceanic.com" class="text-body d-flex flex-row align-items-center">
                                    <i class="fas fa-2x mr-2 fa-feather-alt"></i> Beta
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End about modal -->

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
    <!-- End error modal-->

    <!-- Discord (top nav) modal -->
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

    @include('layouts.nav-mobile')

    <!-- Misc scripts -->
    <script>
        //Add blockquote classes
        $("blockquote").addClass('blockquote');

        //Init tooltips
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
    <!-- End misc scripts -->

</html>
