@extends('layouts.master')

@section('content')

<div class="container py-4">
    <h1 class="font-weight-bold blue-text">Your applications</h1>
    <hr>
    @if (count($applications) > 0)
        @foreach ($applications as $a)
        @endforeach
    @else
        You have not made an application.
    @endif
    @can('start-application')
    <div class="mt-5">
        <a href="{{route('training.applications.apply')}}" class="blue-text" style="font-size: 1.2em;"><i class="fas fa-plus"></i>&nbsp;&nbsp;Start an application</a>
    </div>
    @endcan
</div>

@endsection
