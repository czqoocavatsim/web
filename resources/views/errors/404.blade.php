@extends('layouts.master')
@section('title', 'Error 404 - ')

@section('content')
    <div class="container py-5">
        <h1 class="font-weight-bold blue-text">Oops... that page is missing</h1>
        <h4 class="font-weight-bold">ERROR 404</h4>
        <div class="mt-4">
            <p style="font-size: 1.2em;">
                We couldn't find anything at <a href="{{Request::url()}}">{{Request::url()}}</a>.
                <br>
                If you believe this is a mistake, please contact us.
            </p>
        </div>
        <a href="{{route('index')}}" class="btn bg-czqo-blue-light">Go Home</a>
    </div>
@endsection
