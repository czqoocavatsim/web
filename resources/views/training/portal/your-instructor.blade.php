@extends('training.portal.layouts.main')
@section('page-header-title', 'Your instructor')
@section('portal-content')
<div class="container py-4">
    <div class="d-flex flex-row align-items-center">
        <img src="{{$instructor->user->avatar()}}" style="height: 50px; width:50px;margin-right: 15px; margin-bottom: 3px; border-radius: 50%;">
        <div>
            <h2 class="blue-text mt-2 mb-1 fw-600">{{$instructor->user->fullName('FLC')}}</h2>
            <h5 class="fw-400">{{$instructor->staffPageTagline()}}</h5>
        </div>
    </div>
    <p class="my-2" style="font-size: 1.1em;">{{$instructor->user->bio ?? 'No biography provided.'}}</p>
    <h5 class="mt-4 blue-text fw-400">Their email</h5>
    <p>{{$instructor->staff_email}}</p>
    @if ($instructor->user->hasDiscord())
        <h5 class="mt-4 blue-text fw-400">Their Discord username</h5>
        <p class="mt-1" style="font-size: 1.1em;"><img style="border-radius:50%; height: 30px;" class="img-fluid" src="{{$instructor->user->getDiscordAvatar()}}" alt="">&nbsp;&nbsp;{{$instructor->user->discord_username}}</p>
    @endif
</div>
@endsection
