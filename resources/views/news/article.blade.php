@extends('layouts.master', ['solidNavBar' => false])

@section('title', $article->title.' - ')
@section('description', $article->summary)
@section('image', $article->image)

@section('content')

<div class="jarallax card card-image rounded-0"  data-jarallax data-speed="0.2">
    <img class="jarallax-img" src="{{$article->image}}" alt="">
    <div class="text-white text-left rgba-stylish-light py-3 pt-5 px-4">
        <div class="container">
            <div class="pt-5 pb-3">
                <a href="{{route('news')}}" class="white-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> News</a>
            </div>
            <div class="pb-5">
                <h1 class="font-weight-bold" style="font-size: 3em;">{{$article->title}}</h1>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid grey lighten-4 py-4">
    <div class="container">
        <h4 class="blue-text font-weight-bold">{{$article->summary}}</h4>
        <div class="d-flex flex-row align-items-center mt-3">
            @if($article->show_author)
            <span class="mr-2">
                    <img src="{{$article->user->avatar()}}" style="height: 35px; !important; width: 35px !important; margin-right: 5px; border-radius: 50%;">
                    Authored by {{$article->user->fullName('FL')}}
            </span>
            <span class="mr-2">
                •
            </span>
            @endif
            <span>
                Published {{$article->published->toFormattedDateString()}}
            </span>
        </div>
    </div>
</div>
<div class="container py-4" style="padding-bottom: 100px !important;">
    @if(!$article->visible)
    <div class="alert bg-czqo-blue-light">
        This article is not visible to the public.
    </div>
    @endif
    {{$article->html()}}
</div>
@stop
