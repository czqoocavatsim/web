<!DOCTYPE HTML>
<html lang="en">
    <head>
        <!--
        {{App\CoreSettings::where('id', 1)->firstOrFail()->sys_name}}
        {{App\CoreSettings::where('id', 1)->firstOrFail()->release}} ({{App\CoreSettings::where('id', 1)->firstOrFail()->sys_build}})
        Built on Bootstrap 4 and Laravel 5

        Written by Liesel Downes

          sSSs. sSSSSSs   sSSSs     sSSSs
         S           s   S     S   S     S
        S           s   S       S S       S
        S          s    S       S S       S
        S         s     S       S S       S
         S       s       S   s S   S     S
          "sss' sSSSSSs   "sss"ss   "sss"

        For Flight Simulation Use Only - Not To Be Used For Real World Navigation. All content on this web site may not be shared, copied, reproduced or used in any way without prior express written consent of Gander Oceanic. © Copyright {{App\CoreSettings::where('id', 1)->firstOrFail()->copyright_year}} Gander Oceanic, All Rights Reserved.

        Taking a peek under the hood, and like what you see? Want to help out? Send Liesel an email!
        -->
        <!--Metadata-->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!--Rich Preview Meta-->
        <title>@yield('title'.' - ', 'Gander Oceanic VATSIM')</title>
        <meta name="description" content="@yield('description', '')">
        <meta name="theme-color" content="#000000">
        <meta name="og:title" content="@yield('title'.' - ', 'Gander Oceanic VATSIM')">
        <meta name="og:description" content="@yield('description', '')">
        <meta name="og:image" content="@yield('image',asset('favicon.ico'))">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
        <!-- Bootstrap core CSS -->

        <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.1.3/materia/bootstrap.min.css" rel="stylesheet" integrity="sha384-5bFGNjwF8onKXzNbIcKR8ABhxicw+SC1sjTh6vhSbIbtVgUuVTm2qBZ4AaHc7Xr9" crossorigin="anonymous">
        <!-- JQuery -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <!-- Bootstrap tooltips -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
        <!-- Bootstrap core JavaScript -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <!--CZQO specific CSS-->
        <link href="{{ asset('css/structure.css') }}" rel="stylesheet">
        <link href="{{ asset('css/czqo.css') }}" rel="stylesheet">
        <!--Leaflet-->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js" integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg==" crossorigin=""></script>
        <!--TinyMCE-->
        <script src='https://cloud.tinymce.com/5/tinymce.min.js?apiKey=k2zv68a3b4m423op71lnifx4a9lm0a2ee96o58zafhrdnddb'></script>
        <!--DataTables-->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.css"/>
        <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.js"></script>
        <!--CSS Emoticons-->
        <link href="{{asset('css/jquery.cssemoticons.css')}}" media="screen" rel="stylesheet" type="text/css" />
        <script src="{{asset('/js/jquery.cssemoticons.js')}}" type="text/javascript"></script>
        <!--Fullcalendar-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@4.0.2/main.css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
        <!--IntroJS-->
        <link rel="stylesheet" href="{{asset('introjs/introjs.min.css')}}">
        <script src="{{asset('introjs/intro.min.js')}}"></script>
    </head>
    <style>
        .dropdown-menu {
            animation-duration: 0.3s;
            animation-name: slideIn;
            -webkit-animation-name: slideIn;
            -webkit-animation-duration: 0.3s;
            animation-fill-mode: both;
            -webkit-animation-fill-mode: both;
        }

        @keyframes slideIn {
            0% {
                transform: translateY(1rem);
                opacity: 0;
            }
            100% {
                transform:translateY(0rem);
                opacity: 1;
            }
            0% {
                transform: translateY(1rem);
                opacity: 0;
            }
        }

        @-webkit-keyframes slideIn {
            0% {
                -webkit-transform: transform;
                -webkit-opacity: 0;
            }
            100% {
                -webkit-transform: translateY(0);
                -webkit-opacity: 1;
            }
            0% {
                -webkit-transform: translateY(1rem);
                -webkit-opacity: 0;
            }
        }

        .slideIn {
            -webkit-animation-name: slideIn;
            animation-name: slideIn;
        }
    </style>
    <body>
        @section('navbarprim')
        <!--Navigation bar-->
        <div class="container-fluid pt-3 pb-3 bg-primary">
            <div class="container text-center">
                <a href="/" class="pull-left"><img style="max-width: 310px; height: 30px;" src="{{ asset('img/Banner.png') }}"></a>
            </div>
        </div>
        <nav style="min-height: 40px; background-color: #1B7BC8 !important" class="navbar navbar-expand-md navbar-dark bg-primary p-0">
            <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div  class="collapse navbar-collapse m-0 p-0" id="navbarColor01">
                <ul class="navbar-nav mr-auto m-0 p-0">
                    <li class="nav-item">
                        <a class="nav-link py-0" href="{{url('/')}}">
                            Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-0 {{ Request::is('roster/*') || Request::is('roster') ? 'active' : '' }}" href="{{route('roster.public')}}">
                            Roster
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('controllerbookings')}}" class="nav-link py-0 {{ Request::is('bookings/*') || Request::is('bookings') ? 'active' : '' }}">Bookings</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link py-0 dropdown-toggle {{ Request::is('dashboard/application') || Request::is('sector-files') ? 'active' : '' }}" style="cursor:pointer" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">ATC</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown01">
                            @if (Auth::check() && Auth::user()->permissions >= 1)
                                <a class="dropdown-item {{ Request::is('dashboard/application/list') ? 'active' : '' }}" href="{{url ('/dashboard/application/list')}}">Your Applications</a>
                            @else
                                <a class="dropdown-item {{ Request::is('dashboard/application') ? 'active' : '' }}" href="{{url ('/dashboard/application/')}}">Apply for CZQO</a>
                            @endif
                            <a class="dropdown-item {{ Request::is('atcresources') ? 'active' : '' }}" href="{{route('atcresources.index')}}">ATC Resources</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link py-0 dropdown-toggle {{ Request::is('pilots/oceanic-clearance') || Request::is('pilots/position-report') || Request::is('pilots/vatsim-resources') || Request::is('pilots/tutorial') || Request::is('pilots/tracks') ? 'active' : '' }}" style="cursor:pointer" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pilots</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown01">
                            <a class="dropdown-item {{ Request::is('pilots/oceanic-clearance') ? 'active' : '' }}" href="{{url('/pilots/oceanic-clearance')}}">Oceanic Clearance Generator</a>
                            <a class="dropdown-item {{ Request::is('pilots/position-report') ? 'active' : '' }}" href="{{url('/pilots/position-report')}}">Position Report Generator</a>
                            <a class="dropdown-item" href="https://www.vatsim.net/pilots/resources" target="_blank">VATSIM Resources</a>
                            <a class="dropdown-item {{ Request::is('pilots/tutorial') ? 'active' : '' }}" href="{{url('/pilots/tutorial')}}">Oceanic Tutorial</a>
                            <a class="dropdown-item {{ Request::is('pilots/tracks') ? 'active' : ''}}" href="{{url('/pilots/tracks')}}">NAT Tracks</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-0 {{ Request::is('staff') ? 'active' : '' }}" href="{{url ('/staff')}}" aria-expanded="false">Staff</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle py-0 {{ Request::is('policies') || Request::is('meetingminutes') ? 'active' : ''}}" style="cursor:pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Publications</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown01">
                            <a class="dropdown-item {{ Request::is('policies') ? 'active' : '' }}" href="{{route('policies')}}">Policies</a>
                            <a class="dropdown-item {{ Request::is('meetingminutes') ? 'active' : '' }}" href="{{route('meetingminutes')}}">Meeting Minutes</a>
                            <a class="dropdown-item {{ Request::is('news') ? 'active' : '' }}" href="{{route('news.allpublic')}}">News</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link py-0" href="https://vatcan.ca" target="_blank">VATCAN</a>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                    @unless (Auth::check())
                        <a class="btn shadow-none text-white p-0 m-0" href="{{URL('/login')}}" role="button">
                            <i class="fa fa-user"></i>&nbsp;
                            Login
                        </a>
                    @endunless
                    @auth
                        <div class="dropdown p-0">
                            <a role="button" id="notificationMenu" href="#" class="btn text-white p-0 m-0 shadow-none" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @if (count(Auth::user()->notifications) >= 1)
                                    <i class="fa fa-bell" style="color: yellow;"></i>
                                    {{count(Auth::user()->notifications)}}
                                @else
                                    <i class="fa fa-bell" style="color: white;"></i>
                                @endif
                            </a>
                            <div class="dropdown-menu">
                                <div class="container">
                                    <h5>Notifcations</h5>
                                    @if (count(Auth::user()->notifications) > 0)
                                        <div class="list-group">
                                            @foreach (Auth::user()->notifications as $notification)
                                                <a href="{{route('notification.redirect', $notification->id)}}" class="list-group-item list-group-item-action" >
                                                    {{$notification->content}}
                                                    <small>{{$notification->dateTime}}</small>
                                                </a>
                                            @endforeach
                                            <a href="{{url('/notificationclear')}}" class="list-group-item list-group-item-action list-group-item-primary text-center">
                                                CLEAR ALL
                                            </a>
                                        </div>
                                    @else
                                        None to be found!
                                    @endif
                                </div>
                            </div>
                        </div>
                        <a class="btn shadow-none {{ Request::is('dashboard') || Request::is('dashboard/*') ? 'active' : '' }}" href="{{URL('/dashboard')}}" role="button">
                            <i class="fa fa-tachometer-alt" style="color: white;"></i>
                        </a>
                        <div class="dropdown">
                            <a class="text-white pl-2 shadow-none dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{Auth::user()->fullName('FLC')}}
                            </a>
                            <div class="dropdown-menu pb-0">
                                <div class="container text-center">
                                    <h5 style="font-weight: bold;">{{Auth::user()->fullName('FLC')}}</h5>
                                    <h6>
                                        @if (Auth::user()->permissions == 4)
                                            Executive
                                        @elseif (Auth::user()->permissions == 3)
                                            Staff
                                        @elseif (Auth::user()->permissions == 2)
                                            Instructor
                                        @elseif (Auth::user()->permissions == 1)
                                            Gander Controller
                                        @else
                                            Guest
                                        @endif
                                        @if(Auth::user()->staffProfile)<br/>
                                        {{Auth::user()->staffProfile->position}}
                                        @endif
                                    </h6>
                                    <br/>

                                    <div class="text-center">
                                        <img src="{{Auth::user()->avatar}}" style="width: 125px; height: 125px; margin-bottom: 10px; border-radius: 50%;">
                                    </div>
                                </div>
                                <div>
                                    <div class="btn-group-vertical m-0" role="group" style="width: 100%;">
                                        <a style="border-top-left-radius: 0; border-top-right-radius: 0" class="btn btn-outline-primary {{ Request::is('dashboard') || Request::is('dashboard/*') ? 'active' : '' }}" href="{{URL('/dashboard')}}" role="button">
                                            <i class="fa fa-tachometer-alt"></i>&nbsp;
                                            Dashboard
                                        </a>
                                        @if (Auth::user()->permissions >= 2)
                                        <a class="btn btn-outline-primary {{ Request::is('dashboard/training') || Request::is('dashboard/training*') ? 'active' : '' }}" href="{{URL('/dashboard/training')}}" role="button">
                                            <i class="fa fa-book"></i>&nbsp;
                                            Training
                                        </a>
                                            {{--<a class="btn btn-outline-primary" href="//czqo.vatcan.ca/mail" target="_blank" role="button">
                                                <i class="fa fa-envelope"></i>&nbsp;
                                                Webmail
                                            </a>--}}
                                        @endif
                                        <a class="btn btn-danger text-white" href="{{ URL('/logout') }}">
                                            <i class="fa fa-key"></i>&nbsp;Logout
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endauth
                </form>
            </div>
        </div>
        </nav>
        @if (\App\CoreSettings::where('id', 1)->firstOrFail()->banner)
            <div class="alert alert-{{\App\CoreSettings::where('id', 1)->firstOrFail()->bannerMode}}" style="margin: 0; border-radius: 0; border: none;">
                <div class="text-center align-self-center">
                    <h4 style="margin: 0;">{{\App\CoreSettings::where('id', 1)->firstOrFail()->banner}}&nbsp;|&nbsp;<a class="alert-link" href="{{\App\CoreSettings::where('id', 1)->firstOrFail()->bannerLink}}">Learn more <i class="fa fa-arrow-circle-right"></i></a></h4>
                </div>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger" style="margin: 0; border-radius: 0; border: none;">
                <div class="container">
                    @foreach ($errors->all() as $error)
                        {{ $error }} <br>
                    @endforeach
                </div>
            </div>
        @endif
        @if (\Session::has('success'))
            <div class="alert alert-success" style="margin: 0; border-radius: 0; border: none;">
                <div class="container">
                    {!! \Session::get('success') !!}
                </div>
            </div>
        @endif
        @if (\Session::has('error'))
            <div class="alert alert-danger" style="margin: 0; border-radius: 0; border: none;">
                <div class="container">
                    {!! \Session::get('error') !!}
                </div>
            </div>
        @endif
        @if (\Session::has('info'))
            <div class="alert alert-info" style="margin: 0; border-radius: 0; border: none;">
                <div class="container">
                    {!! \Session::get('info') !!}
                </div>
            </div>
        @endif
        @show
        @yield('content')
        <!-- Footer -->
        <footer class="footer" style="margin-top: 20px;">
            <div class="container">
                <div class="row">
                    <div class="col-md-12" style="font-size: 12px;">
                        For Flight Simulation Use Only - Not To Be Used For Real World Navigation. All content on this web site may not be shared,
                        copied, reproduced or used in any way without prior express written consent of Gander Oceanic. © Copyright
                        {{App\CoreSettings::where('id', 1)->firstOrFail()->copyright_year}} Gander Oceanic, All Rights Reserved.
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{url('/dashboard/tickets')}}">Contact Us&nbsp;</a>
                        <a href="{{url('/privacy')}}">Privacy Policy</a>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class="col-md-12">
                        <a href="https://vatcan.ca">
                            <img style="height: 50px;" src="{{ asset('img/vatcanlogofull.png') }}">
                        </a>&nbsp;
                        <a href="https://vatsim.net">
                            <img style="height: 50px;" src="{{ asset('img/vatsim.png') }}">
                        </a>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class="col-md-12">
                        <small class="text-muted">{{App\CoreSettings::where('id', 1)->firstOrFail()->sys_name}} {{App\CoreSettings::where('id', 1)->firstOrFail()->release}} ({{App\CoreSettings::where('id', 1)->firstOrFail()->sys_build}}) <a href="{{url('/changelog')}}">Change Log</a></small>
                    </div>
                </div>
            </div>
        </footer>
        <!--/.Footer-->

         <div class="modal fade" id="welcomeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
             <div class="modal-dialog modal-dialog-centered" role="document">
                 <div class="modal-content">
                     <div class="modal-header">
                         <h5 class="modal-title" id="exampleModalLongTitle">Welcome to CZQO!</b></h5>
                     </div>
                     <div class="modal-body">
                         Welcome to the Gander Oceanic Core system. Here you can apply for CZQO certification, organise your training, and access important resources. Before
                         we allow you to use the system, we require you to accept our Privacy Policy. The Policy is available <a target="_blank" href="{{url('/privacy')}}">here.</a>
                         By default, you are <b>not</b> subscribed to all emails. Go to Dashboard and Email Preferences to subscribe! It is highly recommended.
                     </div>
                     <div class="modal-footer">
                         <a role="button" href="{{ URL('/logout') }}" class="btn btn-outline-danger" >I disagree</a>
                         <a href="{{url('/privacyaccept')}}" role="button" class="btn btn-success">I agree</a>
                     </div>
                 </div>
             </div>
         </div>
         @if (Auth::check() && Auth::user()->init == 0 && Request::is('privacy') == false)
             <script>
                 $('#welcomeModal').modal({backdrop: 'static'});
                 $('#welcomeModal').modal('show');
             </script>
         @endif
         <script type="text/javascript">
             Dropzone.options.dropzone =
                 {
                     maxFilesize: 12,
                     renameFile: function(file) {
                         var dt = new Date();
                         var time = dt.getTime();
                         return time+file.name;
                     },
                     acceptedFiles: ".jpeg,.jpg,.png,.gif",
                     addRemoveLinks: true,
                     timeout: 5000,
                     success: function(file, response)
                     {
                         console.log(response);
                     },
                     error: function(file, response)
                     {
                         return false;
                     }
                 };
         </script>
    </body>
</html>
