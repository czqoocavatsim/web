@extends('layouts.primary')
@section('title', $page->name . " - ")
@section('description', $page->description)
@section('image', $page->thumbnail)
@section('content')

{!! $page->content !!}

@if ($page->response_form_enabled)
<div class="container py-4">
    <div class="card p-4 shadow-none grey lighten-5">
        <div class="card-body">
            <h3 class="font-weight-bold blue-text">{{$page->response_form_title}}</h3>
            @if (Auth::check() && !$page->userHasResponded())
                <p style="font-size: 1.2em;">
                    {{$page->response_form_description}}
                </p>
                <form id="response-form">
                    <label for="">Enter your response here:</label>
                    <textarea id="contentMD" name="content"></textarea>
                    <script>
                        var simplemde = new EasyMDE({ element: document.getElementById("contentMD") });
                    </script>
                    <p class="text-muted">Your response must comply with the VATSIM Code of Conduct.</p>
                    <button class="btn btn-primary" style="font-size: 1.1em; font-weight: 600;"><i class="fas fa-paper-plane"></i>&nbsp;&nbsp;Submit</button>
                </form>
            @elseif (Auth::check() && $page->userHasResponded())
                You have responded to this form. A copy of your response was emailed to you.
            @elseif (!Auth::check())
                Please login to respond to this form.
            @endif
        </div>
    </div>
</div>
@endif

@endsection
