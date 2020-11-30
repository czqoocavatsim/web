@extends('layouts.master', ['solidNavBar' => false])

@section('title', 'Submit Your Availability - Training Portal - ')

@section('content')
<div class="edge-header blue darken-2" style="background: url(https://camo.githubusercontent.com/d017baa65e14cf81c5cb01ff3e6b263c9e8d0eb04724b1fba4f70e472614e186/68747470733a2f2f692e696d6775722e636f6d2f3435325a654d322e706e67); background-position:center; background-position-y: -450px;"></div>
<div class="container p-5 white" style="margin-top: -100px; margin-bottom: 5rem !important;">
    <h1 class="blue-text mb-4" style="font-size: 3em;">Submit your availability.</h1>
    <p class="lead">
        Welcome to Gander Oceanic, {{Auth::user()->fullName('F')}}! We're excited to have you with us. Before we start your training, we need you to submit your availability for training sessions for the next couple of weeks. This allows us to assign you an Instructor who is best suited to your timezone.
    </p>
    <p>To begin, enter your availability for the next 2 weeks, using the Zulu/GMT time zone.</p>
    <textarea id="contentMD" style="display:none; height:" ></textarea>
    <script>
        var simplemde = new EasyMDE({ maxHeight: '200px', autofocus: true, autoRefresh: true, element: document.getElementById("contentMD"), placeholder: 'I\'m available most days between 0100 zulu and 0500 zulu. My timezone is Australia/Adelaide.'});
    </script>
</div>
@endsection
