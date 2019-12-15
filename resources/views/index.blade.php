@extends('layouts.master')

@section('content')
    <div class="card card-image" style="background-image: url({{asset('https://images-ext-2.discordapp.net/external/zrbU4E4qUOjK6Q3NSJGSn5Y60lzV0dLbNFdgf2DZ5kY/https/image.prntscr.com/image/cKAyIvYHTOOFLh1OvTJZhQ.png?width=1007&height=417')}}); background-size: cover; background-position: center;">
        <div class="text-white text-left py-3 px-4 rgba-black-strong">
            <div class="container">
                <div class="py-5">
                    <h1 class="h1 my-4 py-2" style="font-size: 3em;">Cool, calm and collected oceanic control services in the North Atlantic.</h1>
                </div>
            </div>
        </div>
    </div>
    @if (!Auth::check())
    <div class="jumbotron blue text-white py-3 mb-0">
        <div class="container">
            <div class="d-flex flex-row justify-content-between align-items-center">
                <h3 class="m-0 py-0">Join our talented controller team today!</h3>
                <a href="{{route('application.start')}}" class="m-0 btn btn-light py-2 px-4 text-nowrap" style="background-color: #fff !important;">Login & Apply</a>
            </div>
        </div>
    </div>
    @elseif (Auth::check() && !Auth::user()->rosterProfile)
    <div class="jumbotron blue text-white py-3 mb-0">
        <div class="container">
            <div class="d-flex flex-row justify-content-between align-items-center">
                <h3 class="m-0 py-0">Join our talented controller team today!</h3>
                <a href="{{route('application.start')}}" class="m-0 btn btn-light py-2 px-4 text-nowrap" style="background-color: #fff !important;">Apply</a>
            </div>
        </div>
    </div>
    @else
    <div class="jumbotron blue text-white py-3 mb-0">
        <div class="container">
            <div class="d-flex flex-row justify-content-between align-items-center">
                <h3 class="m-0 py-0">Welcome back, {{Auth::user()->fullName('F')}}!</h3>
                <a href="{{route('dashboard.index')}}" class="m-0 btn btn-light py-2 px-4 text-nowrap" style="background-color: #fff !important;">Go To Dashboard</a>
            </div>
        </div>
    </div>
    @endif
    <div class="container py-4 pt-1">
        <div class="row">
            <div class="col-md-6">
                <h3>Welcome to Gander Oceanic!</h3>
                <p>With our team of talented controllers we operate the Gander FIR in the north-western atlantic. For years we have prided ourselves in providing the coolest, calmest and most collected oceanic services to pilots flying all across the North Atlantic. From assisting new pilots in their oceanic endeavours, to providing services in Cross the Pond twice a year, this is where the magic happens! I extend my warmest welcome to visitors and controllers, young and old and hope that you enjoy the bountiful resources on the site and the incredible services by our oceanic controllers. Please contact us if you have any queries, questions or concerns!</p>
                <h5><b>Andrew Ogden, FIR Chief</b></h5>
            </div>
            <div class="col-md-6">
                <div id="map" style="height: 300px;">
                    Loading...
                </div>
            </div>
        </div>
    </div>
    <div class="jumbotron white-text blue py-3 mb-0">
        <div class="container py-2">
            <h3>News</h3>
            <div class="card-columns">
                @foreach($news as $n)
                <div class="card blue white-text darken-3 my-2 h-100">
                    {{-- <div style="background-image:url({{$n->image}}); background-position: center; height: 125px;" class="waves-effect"> --}}
                    <a href="{{route('news.articlepublic', $n->slug)}}">
                        <div style="background-color:darkslategrey; background-position: center; height: 125px;" class="waves-effect"></div>
                    </a>
                    <div class="card-body pb-2">
                        <a class="card-title font-weight-bold white-text" href="{{route('news.articlepublic', $n->slug)}}"><h4>{{$n->title}}</h4></a>
                        <p>{{$n->summary}}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="d-flex flex-row">
                <a href="#" class="float-right ml-auto mr-0 white-text" style="font-size: 1.2em;">View all news <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
    <div class="container py-4">
        <div class="row">
            <div class="col-md-6">
                <h3 class="blue-text font-weight-bold">New Controllers</h3>
                <div class="row">
                    <div class="col-md-6 d-flex flex-row justify-content-left py-2">
                        <img class="profile-img img-fluid" style="width: 50px; height: 50px;" src="https://czqo.vatcan.ca/storage/files/avatars/1364284/53636522-D935-4FA9-ADF4-22911FC4E29E.png" alt="">
                        <div style="margin-left: 10px;">
                            <h4>Liesel Downes</h4>
                            <span>9 December 2019</span>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex flex-row justify-content-left py-2">
                        <img class="profile-img img-fluid" style="width: 50px; height: 50px;" src="https://czqo.vatcan.ca/storage/files/avatars/1364284/53636522-D935-4FA9-ADF4-22911FC4E29E.png" alt="">
                        <div style="margin-left: 10px;">
                            <h4>Liesel Downes</h4>
                            <span>9 December 2019</span>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex flex-row justify-content-left py-2">
                        <img class="profile-img img-fluid" style="width: 50px; height: 50px;" src="https://czqo.vatcan.ca/storage/files/avatars/1364284/53636522-D935-4FA9-ADF4-22911FC4E29E.png" alt="">
                        <div style="margin-left: 10px;">
                            <h4>Liesel Downes</h4>
                            <span>9 December 2019</span>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex flex-row justify-content-left py-2">
                        <img class="profile-img img-fluid" style="width: 50px; height: 50px;" src="https://czqo.vatcan.ca/storage/files/avatars/1364284/53636522-D935-4FA9-ADF4-22911FC4E29E.png" alt="">
                        <div style="margin-left: 10px;">
                            <h4>Liesel Downes</h4>
                            <span>9 December 2019</span>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex flex-row justify-content-left py-2">
                        <img class="profile-img img-fluid" style="width: 50px; height: 50px;" src="https://czqo.vatcan.ca/storage/files/avatars/1364284/53636522-D935-4FA9-ADF4-22911FC4E29E.png" alt="">
                        <div style="margin-left: 10px;">
                            <h4>Liesel Downes</h4>
                            <span>9 December 2019</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h3 class="blue-text font-weight-bold">Quick Links</h3>
                <ol class="list-unstyled">
                    <li class="py-1">
                        <a href="#" class="btn btn-block btn-discord align-content-center"><i class="fab fa-discord fa-2x" style="vertical-align:middle;"></i>&nbsp;&nbsp;Join Our Discord</a>
                    </li>
                    <li class="py-1">
                        <a href="{{url('/pilots')}}" class="btn btn-block btn-light">Pilot Resources</a>
                    </li>
                    <li class="py-1">
                        <a href="" class="btn btn-block btn-light light-blue darken-1 white-text">Facebook</a>
                    </li>
                </ol>
            </div>
        </div>
    </div>
    <script src="{{asset('js/homepagemap.js')}}"></script>
@endsection

