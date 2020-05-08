@extends('layouts.master')
@section('title', 'Error 403 - ')

@section('content')
    <div class="container py-5">
        <h1 class="font-weight-bold blue-text">You can't access this page</h1>
        <h4 class="font-weight-bold">ERROR 403</h4>
        <div class="mt-4">
            <p style="font-size: 1.2em;">
                You are not permitted to access this page. You may need to login, or you may not have the required permissions.
                <br>
                If you believe this is a mistake, please contact us.
            </p>
            <p class="border p-3">
                {{$exception->getMessage()}}
            </p>
        </div>
        <a href="{{route('index')}}" class="btn bg-czqo-blue-light">Go Home</a>
    </div>
@endsection
