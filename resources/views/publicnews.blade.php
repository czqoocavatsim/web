@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    <div class="container" style="margin-top: 20px;">
        <h1>CZQO News</h1>
        <br/>
        <ul class="list-group">
            @foreach ($news as $article)
                <a href="{{route('news.articlepublic', $article->id)}}" class="list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">{{$article->title}}</h5>
                        <small>Published {{$article->date}}</small>
                    </div>
                    <h6>{{App\User::find($article->user_id)->fullName('FLC')}}</h6>
                </a>
            @endforeach
        </ul>
    </div>
@stop