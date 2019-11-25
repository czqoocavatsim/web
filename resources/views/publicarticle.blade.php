@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('title', $article->title.' - ')
@section('description', \App\User::find($article->user_id)->fullName('FLC').' - ')

@section('content')
    <style>
        .article-container {
            margin-top: 20px;
        }
    </style>
    <div class="container article-container">
        <h1>{{$article->title}}</h1>
        <h4>{{\App\User::find($article->user_id)->fname}} {{\App\User::find($article->user_id)->lname}} {{\App\User::find($article->user_id)->id}} | Published {{$article->date}}</h4>
        <hr class="my-3">
        <p>{!! html_entity_decode($article->content) !!}</p>
    </div>
@stop
