@extends('layouts.primary')

@section('content')

<div class="container py-4">
    @if (\Session::has('alreadyApplied'))
        <div class="alert bg-czqo-blue-light">
            {{\Session::get('alreadyApplied')}}
        </div>
    @endif
    <a href="{{route('training.applications.show', $application->reference_id)}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i>  Your application (#{{$application->reference_id}})</a>
    <h1 class="font-weight-bold blue-text">Updates</h1>
    @if (count($updates) < 1)
        No updates found
    @else
    @foreach($updates as $update)
        <div class="card shadow-none  grey lighten-3 p-4 mb-3">
            <p style="font-size: 1.02rem;" title="{{$update->created_at}} GMT">{{$update->created_at->toDayDateTimeString()}} - {{$update->created_at->diffForHumans()}}</p>
            <h3 class="font-weight-bold {{$update->update_type}}-text">{{$update->update_title}}</h3>
            <div>{{$update->updateContentHtml()}}</div>
        </div>
    @endforeach
    @endif
</div>

@endsection
