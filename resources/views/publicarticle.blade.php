@extends('layouts.master')

@section('title', $article->title.' - ')
@section('description', $article->summary)

@section('content')
    <div class="card card-image blue rounded-0" style="background-image: url({{$article->image}}); background-size: cover; background-position: center;">
        <div class="text-white text-left py-1 px-4 rgba-black-light">
            <div class="container">
                <div class="py-5">
                    <h1 class="h1" style="font-size: 3em;">{{$article->title}}</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="container py-4">
        <h4 class="blue-text">{{$article->summary}}</h4>
        <div class="d-flex flex-row align-items-center">
            <i class="far fa-clock"></i>&nbsp;&nbsp;<span @if($article->edited) title="Last edited {{$article->edited_pretty()}}" @endif>Published {{$article->published_pretty()}}</span>&nbsp;&nbsp;•&nbsp;&nbsp;<i class="far fa-user-circle"></i>&nbsp;&nbsp;{{$article->author_pretty()}}
        </div>
        <hr>
        @if(!$article->visible)
        <div class="alert bg-czqo-blue-light">
            This article is not visible to the public.
        </div>
        @endif
        {{$article->html()}}
    </div>
@stop
