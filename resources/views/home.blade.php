@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    <style>
        #topjumbo {
            background-image:url('https://cdn.discordapp.com/attachments/486457209551847424/512533733107171348/unknown.png');
            position: relative;
            color: white;
            background-position: center;
            background-color: #707070;
            background-blend-mode: multiply;
        }
    </style>

    <div id="topjumbo" class="jumbotron jumbotron-fluid" >
        <div class="container" style="z-index: 99;">
          <h1 class="display-3" style="z-index: 99;">Gander Oceanic FIR</h1>
          <h3 class="display-6" style="z-index: 99;">Cool, calm and collected oceanic control services in the North Atlantic.</h3>
        </div>
    </div>
    <div class="container" style="margin-top: 20px;" >
        <div class="row">
            <div class="col-6">
                <h3>Welcome to Gander Oceanic!</h3>
                <p>Welcome to the Gander Oceanic FIR! With our team of talented controllers we operate the Gander FIR in the north-western atlantic. For years we have prided ourselves in providing the coolest, calmest and most collected oceanic services to pilots flying all across the North Atlantic. From assisting new pilots in their oceanic endeavours, to providing services in Cross the Pond twice a year, this is where the magic happens! I extend my warmest welcome to visitors and controllers, young and old and hope that you enjoy the bountiful resources on the site and the incredible services by our oceanic controllers. Please contact us if you have any queries, questions or concerns!</p>
                <h5><b>- Andrew Ogden, Director Oceanic Operations</b></h5>
                <br class="my-0">
                @if (count($news) < 1)
                @else
                    <h5>CZQO News</h5>
                    <ul class="list-group">
                    @foreach ($news as $article)
                        @if ($article->archived == 1)
                        @else
                            <a href="{{route('news.articlepublic', $article->id)}}" class="list-group-item list-group-item-action flex-column align-items-start">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">{{$article->title}}</h5>
                                    <small>Published {{$article->date}}</small>
                                </div>
                                <h6>{{App\User::find($article->user_id)->fname}} {{App\User::find($article->user_id)->lname}} {{App\User::find($article->user_id)->id}}</h6>
                            </a>
                        @endif
                    @endforeach
                    </ul>
                    <small>
                        <a href="{{route('news.allpublic')}}">View all articles</a>
                    </small>
                @endif
            </div>
            <div class="col">
                @if (count($carouselItems) < 1)
                @else
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($carouselItems as $index => $item)
                                <div class="carousel-item @if($index == 0) {{'active'}} @endif">
                                    <img style="max-width: 100%;" src="{{$item->image_url}}" alt="{{$item->caption}}">
                                    @unless ($item->caption === "" || $item->caption === null)
                                    <div class="carousel-caption d-none d-md-block">
                                        <h4 style="text-shadow: 0px 3px 4px rgba(150, 150, 150, 1);">{{$item->caption}}</h4>
                                        @unless ($item->caption_url === "" || $item->caption_url === "")
                                            <a style="text-shadow: 0px 3px 4px rgba(150, 150, 150, 1); color: #fff;" href="{{$item->caption_url}}">See more <i class="fas fa-arrow-right"></i></a>
                                        @endunless
                                    </div>
                                    @endunless
                                </div>
                            @endforeach
                        </div>
                        @if (count($carouselItems) > 1)
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                        @endif
                    </div>
                    <br/>
                @endif
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><b>Online Oceanic Controllers</b></h5>
                        @if (count($ganderControllers) < 1 && count($shanwickControllers) < 1)
                            <div class='alert alert-info'>No controllers online.</div>
                        @else
                            <ul class="list-group">
                            @foreach ($ganderControllers as $c)
                                <a class='list-group-item'><b>{{$c['callsign']}}</b>&nbsp;{{$c['realname']}} on {{$c['frequency']}}</a>
                            @endforeach
                            @foreach ($shanwickControllers as $c)
                                <a class='list-group-item'><b>{{$c['callsign']}}</b>&nbsp;{{$c['realname']}} on {{$c['frequency']}}</a>
                            @endforeach
                            </ul>
                        @endif
                    </div>
                </div><br/>
                @if (Auth::check() && Auth::user()->permissions == 0)
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><b>Apply for Gander Certification</b></h5>
                        <p class="card-text">Are you a C1 controller with 120 hours controlling hours on your rating? Want to join the incredible group of controllers at Gander? Start your application today.</p>
                        <a href="{{url('/dashboard/application/create')}}" class="btn btn-primary">Apply for Gander Certifcation</a>
                    </div>
                </div><br/>
                @endif
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><img style="height: 60px;" src="https://discordapp.com/assets/e4923594e694a21542a489471ecffa50.svg"></h5>
                        <p class="card-text">Join the Gander Oceanic Discord server by clicking the button below. Note you will require a <a href="https://discordapp.com">Discord</a> account.</p>
                        <a href="https://discord.gg/MvPVAHP" class="btn btn-primary">Join the CZQO Discord</a>
                    </div>
                </div>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col">
                @if (count($promotions) >= 1)
                <h5>Recent Certifications</h5>
                <ul class="list-group">
                    @foreach ($promotions as $item)
                        <a target="_blank" href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">{{$item->title}}</h5>
                                <small>{{$item->date}}</small>
                            </div>
                            <p class="mb-1"></p>
                            <small></small>
                        </a>
                    @endforeach
                </ul>
                @endif
            </div>
            <div class="col">
                <h5>VATCAN News</h5>
                <ul class="list-group">
                    @foreach ($vatcanNewsJson as $article)
                        <a target="_blank" href="https://www.vatcan.ca/forums/index.php?topic={{$article['id']}}" class="list-group-item list-group-item-action flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">{{$article['subject']}}</h5>
                                <small>Published {{$article['date']}}</small>
                            </div>
                            <p class="mb-1"></p>
                            <small></small>
                        </a>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@stop