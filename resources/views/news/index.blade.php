@extends('layouts.master', ['solidNavBar' => false])

@section('title', 'News - ')
@section('description', 'News from Gander Oceanic')

@section('content')
    <div class="card card-image blue rounded-0">
        <div class="text-white text-left pb-2 pt-5 px-4">
            <div class="container">
                <div class="py-5">
                    <h1 class="font-weight-bold" style="font-size: 3em;">News</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="container py-4">
        <div class="row">
            @foreach($news as $n)
                <div class="col-md-6 mb-3">
                    <div class="view" style="height: 250px !important; @if($n->image) background-image:url({{$n->image}}); background-size: cover; @else background: var(--czqo-blue); @endif">
                        <div class="mask rgba-stylish-light flex-left p-4 justify-content-end d-flex   flex-column h-100">
                            <div class="container">
                                <h2 class="font-weight-bold white-text">
                                    <a href="{{route('news.articlepublic', $n->slug)}}" class="white-text">
                                        {{$n->title}}
                                    </a>
                                </h2>
                                <p class="white-text mb-2" style="font-size: 1.2em;">
                                    {{$n->summary}}
                                </p>
                                <p class="white-text mb-0" style="font-size: 1.1em;">
                                    <small><i class="far fa-clock"></i>&nbsp;&nbsp;<span @if($n->edited) title="Last edited {{$n->edited_pretty()}}" @endif>Published {{$n->published_pretty()}}</span>&nbsp;&nbsp;&nbsp;<i class="far fa-user-circle"></i>&nbsp;&nbsp;{{$n->author_pretty()}}</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            @if(count($news) < 1)
            <div class="col-md-6">
                No articles found.
            </div>
            @endif
        </div>
    </div>
@stop
