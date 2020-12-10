@extends('layouts.primary', ['solidNavBar' => true])
@section('title', 'Home - ')
@section('description', 'Cool, calm and collected oceanic control services in the North Atlantic on VATSIM.')

@section('content')
    <div style="height: calc(100vh - 74px)" class="z-depth-0">
        <div class="flex-center flex-column">
            <div class="container">
                <h1 class="display-2 fw-900 blue-text">Cool. Calm. Collected.</h1>
                <h2 style="font-size:3em;" class="fw-800 mt-4">Welcome to Gander Oceanic.</h2>
            </div>
        </div>
    </div>
    <div class="jumbtron" style="margin-top: -100px">
        <div class="container blue z-depth-2 p-5 mb-5">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="d-flex flex-row justify-content-left">
                        <img style="margin-top: -7px;height: 80px;" src="{{asset('img/Twitter_Logo_Blue.png')}}" alt="">
                        <div>
                            <h2 class="font-weight-bold white-text">Latest Tweets</h2>
                            <a href="https://twitter.com/ganderocavatsim/" class="text-white">
                                <p class="mt-0" style="font-size: 1.2em;">@ganderocavatsim</p>
                            </a>
                        </div>
                    </div>
                    <div class="list-group z-depth-1">
                        @if($tweets)
                        @foreach($tweets as $t)
                            <a href="https://twitter.com/ganderocavatsim/status/{{$t['id']}}" target="_blank" class="list-group-item list-group-item-action waves-effect">
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
                    <h2 class="font-weight-bold white-text mb-4">Our Newest Controllers</h2>
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
                    <h2 class="font-weight-bold white-text">Quick Links</h2>
                    <ul class="list-unstyled mt-4" style="font-size: 1.3em;">
                        <li class="mb-3">
                            <a data-toggle="modal" data-target="#discordTopModal" href="" style="text-decoration:none;">
                                <span class="white-text">
                                    <i class="fab fa-discord fa-2x" style="vertical-align:middle;"></i>
                                </span>
                                &nbsp;
                                <span class="white-text">Join Our Discord Community</span>
                            </a>
                        </li>
                        <li class="mb-3">
                            <a href="https://twitter.com/ganderocavatsim" style="text-decoration:none;">
                                <span class="white-text">
                                    <i class="fab fa-twitter fa-2x" style="vertical-align:middle;"></i>
                                </span>
                                &nbsp;
                                <span class="white-text">Twitter</span>
                            </a>
                        </li>
                        <li class="mb-3">
                            <a href="https://www.facebook.com/ganderocavatsim" style="text-decoration:none;">
                                <span class="white-text">
                                    <i class="fab fa-facebook fa-2x" style="vertical-align:middle;"></i>
                                </span>
                                &nbsp;
                                <span class="white-text">Facebook</span>
                            </a>
                        </li>
                        <li class="mb-3">
                            <a href="https://www.youtube.com/channel/UC3norFpW3Cw4ryGR7ourjcA" style="text-decoration:none;">
                                <span class="white-text">
                                    <i class="fab fa-youtube fa-2x" style="vertical-align:middle;"></i>
                                </span>
                                &nbsp;
                                <span class="white-text">YouTube Channel</span>
                            </a>
                        </li>
                        <li class="mb-3">
                            <a href="https://knowledgebase.ganderoceanic.com" style="text-decoration:none;">
                                <span class="white-text">
                                    <i class="fas fa-book fa-2x" style="vertical-align:middle;"></i>
                                </span>
                                &nbsp;
                                <span class="white-text">Knowledge Base</span>
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
        snowStorm();
    </script>

@endsection

