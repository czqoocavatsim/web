@extends('layouts.master')

@section('title', 'News - ')
@section('description', 'News from Gander Oceanic')

@section('content')
    <div class="container py-4">
        <h1 class="blue-text font-weight-bold">News</h1>
        <hr>
        <div class="card-columns">
            @foreach($news as $n)
            <div class="card homepage-news blue white-text darken-3 my-2 h-100">
                <a href="{{route('news.articlepublic', $n->slug)}}">
                    @if ($n->image)
                    <div style="background-image:url({{$n->image}}); background-position: center; background-size:cover; height: 125px;" class="homepage-news-img waves-effect"></div>
                    @else
                    <div style="height: 125px;" class="blue waves-effect homepage-news-img"></div>
                    @endif
                </a>
                <div class="card-body pb-2">
                    <a class="card-title font-weight-bold white-text" href="{{route('news.articlepublic', $n->slug)}}"><h4>{{$n->title}}</h4></a>
                    <p>{{$n->summary}}</p>
                    <small><i class="far fa-clock"></i>&nbsp;&nbsp;<span @if($n->edited) title="Last edited {{$n->edited_pretty()}}" @endif>Published {{$n->published_pretty()}}</span><br/><i class="far fa-user-circle"></i>&nbsp;&nbsp;{{$n->author_pretty()}}</small>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@stop
