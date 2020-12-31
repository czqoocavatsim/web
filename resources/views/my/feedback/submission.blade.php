@extends('layouts.primary', ['solidNavBar' => false])

@section('title', 'Feedback Submission - ')

@section('content')
    <div class="card card-image blue rounded-0">
        <div class="text-white text-left pb-2 pt-5 px-4">
            <div class="container">
                <div class="pt-5 pb-3">
                    <a href="{{route('my.feedback')}}" class="white-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Feedback</a>
                </div>
                <div class="pb-5">
                    <h1 class="font-weight-bold" style="font-size: 3em;">Feedback you submitted on {{$submission->created_at->toFormattedDateString()}}</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="container py-4">
        <p class="lead fw-600">Feedback type</p>
        <p class="fw-400">{{$submission->type->name}}</p>
        @foreach($submission->fields as $field)
            <p class="lead fw-600">{{$field->name}}</p>
            <p class="fw-400">{{$field->content}}</p>
        @endforeach
        <p class="lead fw-600">Content</p>
        {{$submission->submissionContentHtml()}}
    </div>
@endsection
