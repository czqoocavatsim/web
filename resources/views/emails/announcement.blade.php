@extends('layouts.email')

@section('title')
    <b>{{$news->title}}</b>
@stop

@section('to')

    <strong>Hi there,</strong>
@stop

@section('content')
    <p>
        {!! html_entity_decode($news->content) !!}
    </p>
    <hr>
    <br/>
    View this news article on the website <a href="{{route('news.articlepublic', $news->slug)}}">here.</a>
@stop

@section('end')
    <p>
        Sent by {{\App\User::find($news->user_id)->fullName('FLC')}}
        <br/>
        @if ($news->type == 'CertifiedOnly')
            You received this email because you are a certified Gander controller according to our records.
        @else
            You received this email because you have an account on the Gander Oceanic website.
        @endif
    </p>
    <b>Gander Oceanic Core</b>
@stop