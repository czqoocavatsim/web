@extends('layouts.primary', ['solidNavBar' => false])

@section('title', 'New Feedback - ')

@section('content')
    <div class="card card-image blue rounded-0">
        <div class="text-white text-left pb-2 pt-5 px-4">
            <div class="container">
                <div class="pt-5 pb-3">
                    <a href="{{route('my.index')}}" class="white-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> myCZQO</a>
                </div>
                <div class="pb-5">
                    <h1 class="font-weight-bold" style="font-size: 3em;">New feedback</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="container py-4">
        <p class="lead fw-600">What kind of feedback do you want to submit?</p>
        <div class="list-group">
            @foreach ($types as $t)
                <a href="{{route('my.feedback.new.write', $t->slug)}}" class="list-group-item list-group-item-action z-depth-1 py-3 mb-3 rounded waves-effect">
                    <div class="d-flex flex-row w-100 justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-700 blue-text">{{$t->name}}</h4>
                            <p class="mb-0 fw-500">{{$t->description}}</p>
                        </div>
                        <i style="font-size: 1.6em;" class="blue-text fas fa-chevron-right fa-fw"></i>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection
