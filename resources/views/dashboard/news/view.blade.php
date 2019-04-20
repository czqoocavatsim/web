@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    <div class="container" style="margin-top: 20px;">
        <h2>View Article {{ $article->id }}</h2>
        <h4>{{ $article->title }}</h4>
        <br/>
        <table class="table table-hover">
            <thead>
                <th scope="col">Atribute</th>
                <th scope="col">Value</th>
            </thead>
            <tbody>
                <tr>
                    <th scope="col">Author</th>
                    <td>{{App\User::find($article->user_id)->fname}} {{App\User::find($article->user_id)->lname}} {{App\User::find($article->user_id)->id}}</td>
                </tr>
                <tr>
                    <th scope="col">Published</th>
                    <td>{{$article->date}}</td>
                </tr>
                <tr>
                    <th scope="col">Archived</th>
                    <td>
                        @if ($article->archived == 0)
                        No
                        @else
                        Yes
                        @endif    
                    </td>
                </tr>
            </tbody>
        </table>
        <label>Content</label>
        {!! html_entity_decode($article->content) !!}
        <br/>
        <a href="{{url('/dashboard/news/article/' . $article->id . '/delete')}}" role="button" class="btn btn-danger">Delete</a>
        @if ($article->archived == 0)
            <a href="{{url('/dashboard/news/article/' . $article->id . '/archive/true')}}" role="button" class="btn btn-warning">Archive</a>
        @else
            <a href="{{url('/dashboard/news/article/' . $article->id . '/archive/false')}}" role="button" class="btn btn-success">Unarchive</a>
        @endif
    </div>
@stop