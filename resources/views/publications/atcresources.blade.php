@extends('layouts.primary', ['solidNavBar' => false])

@section('title', 'ATC Resources - ')
@section('description', 'Sector files and resources for Gander controllers')

@section('content')
<div class="jarallax card card-image blue rounded-0"  data-jarallax data-speed="1">
    <img class="jarallax-img" src="{{asset('assets/resources/media/img/website/euroscope_client.png')}}" alt="">
    <div class="text-white text-left rgba-stylish-strong py-3 pt-5 px-4">
        <div class="container">
            <div class="py-5">
                <h1 class="font-weight-bold">ATC Resources</h1>
                <h4 class="font-weight-bold">Official documents and files for use when controlling Gander Oceanic</h4>
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
