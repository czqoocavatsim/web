@extends('layouts.primary', ['solidNavBar' => false])

@section('title', 'ATC Resources - ')
@section('description', 'Sector files and resources for Gander controllers')

@section('content')
<div class="jarallax card card-image rounded-0"  data-jarallax data-speed="0.2">
    <img class="jarallax-img" src="https://images.thestar.com/tpWKLTZ4lJ1WTB--7inZbspeJKM=/1200x808/smart/filters:cb(2700061000)/https://www.thestar.com/content/dam/thestar/news/canada/2018/03/03/not-enough-air-traffic-controllers-are-women-minorities-nav-canada-says/assil_bedewi.jpg" alt="">
    <div class="text-white text-left pb-2 pt-5 px-4 mask rgba-stylish-strong">
        <div class="container">
            <div class="py-5">
                <h1 class="font-weight-bold" style="font-size: 3em;">ATC Resources</h1>
                <p style="font-size: 1.2em;" class="mt-3 mb-0">
                    Official documents and files for use when controlling Gander Oceanic
                </p>
            </div>
        </div>
    </div>
</div>
<div class="container py-4">
    <div class="list-group mt-2">
        @foreach($resources as $resource)
            @if(Auth::check() && Auth::user()->cannot('view atc only resources') && $resource->atc_only)
                @continue
            @endif
            <a href="{{$resource->url}}" class="list-group-item rounded mb-3 list-group-item-action z-depth-1 waves-effect shadow-none p-3">
                <div class="d-flex flex-row justify-content-between">
                    <div class="d-flex flex-row align-items-center">
                        <i class="far fa-file-alt fa-fw mr-3 blue-text" style="font-size: 3em;"></i>
                        <div class="d-flex flex-column text-left">
                            <h4 class="fw-700">{{$resource->title}}</h4>
                            <p class="mb-0">{{$resource->description}}</p>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection
