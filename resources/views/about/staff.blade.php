@extends('layouts.primary', ['solidNavBar' => false])

@section('title', 'Staff - ')

@section('content')
<div class="jarallax card card-image blue rounded-0"  data-jarallax data-speed="0.2">
    {{-- <img class="jarallax-img" src="{{asset('assets/resources/media/img/website/euroscope_client.png')}}" alt=""> --}}
    <div class="text-white text-left rgba-stylish-strong py-3 pt-5 px-4">
        <div class="container">
            <div class="py-5">
                <h1 class="font-weight-bold" style="font-size: 3em;">Our Staff</h1>
                <h4 class="font-weight-bold">List of the Staff for Gander Oceanic</h4>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="list-group" style="position: sticky; top: 20px">
                <a href="#leadership" class="list-group-item list-group-item-action">Senior Leadership Team</a>
                <a href="#events" class="list-group-item list-group-item-action">Events & Marketing Team</a>
                <a href="#tech" class="list-group-item list-group-item-action">IT Team</a>
                <a href="#operations" class="list-group-item list-group-item-action">Operations Team</a>
                <a href="#training" class="list-group-item list-group-item-action">Training Team</a>
            </div>
        </div>
        <div class="col-md-9">

            {{-- Senior Team --}}
            <a id="leadership"><h3 class="mb-3 blue-text font-weight-bold">Senior Leadership Team</h3></a>
            <p style="margin-top: 5px; margin-bottom: 10px;">The Senior Leadership Team for Gander Oceanic. Responsible for the direction and management of the FIR.</p>

            @foreach($groups as $g)
            @if ($g->slug == 'seniorstaff')
                <div class="row">
                    @foreach($g->members as $member)
                        <div class="@if($member->shortform == 'ocachief') col-md-12 @else col-md-6 @endif mb-3">
                            <div class="card shadow-none grey lighten-4 p-4" style="height: 100%;">
                                <div class="d-flex flex-row">
                                    @if(!$member->vacant())
                                    <img src="{{$member->user->avatar()}}" style="height: 80px; width:80px;margin-right: 15px; border-radius: 50%;">
                                    @else
                                    <img src="{{asset('assets/resources/user.png')}}" style="height: 80px; width:80px;margin-right: 15px; border-radius: 50%;">
                                    @endif
                                    <div class="d-flex flex-column">
                                        <h4 class="font-weight-bold">
                                            @if($member->vacant())
                                            Vacant
                                            @else
                                            {{$member->user->fullname('FLC')}}
                                            @endif
                                        </h4>
                                        <h5>{{$member->position}} (ZQO{{$member->id}})</h5>
                                        <p>{{$member->description}}</p>
                                        <p class="mb-0">
                                            <a href="mailto:{{$member->email}}"><i class="fa fa-envelope"></i>&nbsp;Email</a>@if(!$member->vacant())&nbsp;&nbsp;•&nbsp;&nbsp;<a href=""  data-toggle="modal" data-target="#viewStaffBio{{$member->id}}"><i class="fas fa-user"></i>&nbsp;Biography</a>@endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
            <div class="row">
                @foreach($g->members as $member)
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-none grey lighten-4 p-4" style="height: 100%;">
                            <div class="d-flex flex-row">
                                @if(!$member->vacant())
                                <img src="{{$member->user->avatar()}}" style="height: 80px; width:80px;margin-right: 15px; border-radius: 50%;">
                                @else
                                <img src="{{asset('assets/resources/user.png')}}" style="height: 80px; width:80px;margin-right: 15px; border-radius: 50%;">
                                @endif
                                <div class="d-flex flex-column">
                                    <h4 class="font-weight-bold">
                                        @if($member->vacant())
                                        Vacant
                                        @else
                                        {{$member->user->fullname('FL')}}
                                        @endif
                                    </h4>
                                    <h5>{{$member->position}}</h5>
                                    <p>{{$member->description}}</p>
                                    <p class="mb-0">
                                        <a href="mailto:{{$member->email}}"><i class="fa fa-envelope"></i>&nbsp;Email</a>@if(!$member->vacant())&nbsp;&nbsp;•&nbsp;&nbsp;<a href=""  data-toggle="modal" data-target="#viewStaffBio{{$member->id}}"><i class="fas fa-user"></i>&nbsp;Biography</a>@endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif
            @endforeach
            <hr>


            {{-- Events & Marketing Team --}}
            <a id="events"><h3 class="mb-3 blue-text font-weight-bold">Events & Marketing Team</h3></a>
            <div class="row">
                @foreach ($events as $event)
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-none grey lighten-4 p-4" style="height: 100%;">
                            <div class="d-flex flex-row">
                                <img src="{{$event->avatar()}}" style="height: 80px; width:80px;margin-right: 15px; border-radius: 50%;">
                                <div class="d-flex flex-column">
                                    <h4 class="font-weight-bold">
                                        {{$event->fullname('FLC')}}
                                    </h4>
                                    <p>Events & Marketing Staff</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <hr>


            {{-- IT Team --}}
            <a id="tech"><h3 class="mb-3 blue-text font-weight-bold">IT Team</h3></a>
            <div class="row">
                @foreach ($web as $weby)
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-none grey lighten-4 p-4" style="height: 100%;">
                            <div class="d-flex flex-row">
                                <img src="{{$weby->avatar()}}" style="height: 80px; width:80px;margin-right: 15px; border-radius: 50%;">
                                <div class="d-flex flex-column">
                                    <h4 class="font-weight-bold">
                                        {{$weby->fullname('FLC')}}
                                    </h4>
                                    <p>Developer</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <hr>

            {{-- Operations Team --}}
            <a id="operations"><h3 class="mb-3 blue-text font-weight-bold">Operations Team</h3></a>
            <div class="row">
                @foreach ($operations as $ops)
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-none grey lighten-4 p-4" style="height: 100%;">
                            <div class="d-flex flex-row">
                                <img src="{{$ops->avatar()}}" style="height: 80px; width:80px;margin-right: 15px; border-radius: 50%;">
                                <div class="d-flex flex-column">
                                    <h4 class="font-weight-bold">
                                        {{$ops->fullname('FLC')}}
                                    </h4>
                                    <p>Operations Staff</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <hr>

            {{-- Training Team --}}
            <a id="training"><h3 class="mb-3 blue-text font-weight-bold">Training Team</h3></a>
            <div class="row">
                @foreach ($instructors as $instructor)
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-none grey lighten-4 p-4" style="height: 100%;">
                            <div class="d-flex flex-row">
                                <img src="{{$instructor->user->avatar()}}" style="height: 80px; width:80px;margin-right: 15px; border-radius: 50%;">
                                <div class="d-flex flex-column">
                                    <h4 class="font-weight-bold">
                                        {{$instructor->user->fullname('FLC')}}
                                    </h4>
                                    <p>Instructor</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <hr>


        </div>
    </div>
    <p class="text-muted mt-3">
        Icons made by <a href="https://www.flaticon.com/authors/freepik" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon"> www.flaticon.com</a>
    </p>
</div>

@foreach ($leadership as $member)
    <div class="modal fade" id="viewStaffBio{{$member->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{$member->user->fname}}'s biography</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if ($member->user->bio)
                        {{$member->user->bio}}
                    @else
                        This person has no biography :(
                    @endif
                </div>
            </div>
        </div>
    </div>
@endforeach


@foreach ($instructors as $member)
    <div class="modal fade" id="viewInstructorBio{{$member->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{$member->user->fname}}'s biography</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if ($member->user->bio)
                        {{$member->user->bio}}
                    @else
                        This person has no biography :(
                    @endif
                </div>
            </div>
        </div>
    </div>
@endforeach
@stop
