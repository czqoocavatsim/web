@extends('layouts.master', ['solidNavBar' => false])

@section('title', 'Staff - ')

@section('content')
@include('layouts.large-page-header-blue', ['title' => 'Staff'])
<div class="container py-4">
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="list-group" style="position: sticky; top: 20px">
                @foreach($groups as $g)
                <a href="#{{$g->slug}}" class="list-group-item list-group-item-action">
                    {{$g->name}}
                </a>
                @endforeach
                <a href="#instructors" class="list-group-item list-group-item-action">Instructors</a>
            </div>
        </div>
        <div class="col-md-9">
            @foreach($groups as $g)
            <a id="{{$g->slug}}"><h3 class="mb-3 blue-text font-weight-bold">{{$g->name}}</h3></a>
            <p style="margin-top: 5px; margin-bottom: 10px;">{{$g->description}}</p>
            @if ($g->slug == 'seniorstaff')
                <div class="row">
                    @foreach($g->members as $member)
                        @if($member->shortform == 'ocachief')
                        <div class="col-md-12 mb-3">
                            <div class="card shadow-none grey lighten-4 p-4 text-center" style="height: 100%;">
                                <img @if(!$member->vacant()) src="{{$member->user->avatar()}}" @else src="https://cdn.ganderoceanic.com/resources/user.png" @endif class="mx-auto" style="height: 120px; width:120px;margin-bottom: 15px; border-radius: 50%;">
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
                        @else
                        <div class="col-md-6 mb-3">
                            <div class="card shadow-none grey lighten-4 p-4" style="height: 100%;">
                                <div class="d-flex flex-row">
                                    @if(!$member->vacant())
                                    <img src="{{$member->user->avatar()}}" style="height: 80px; width:80px;margin-right: 15px; border-radius: 50%;">
                                    @else
                                    <img src="https://cdn.ganderoceanic.com/resources/user.png" style="height: 80px; width:80px;margin-right: 15px; border-radius: 50%;">
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
                        @endif
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
                                <img src="https://cdn.ganderoceanic.com/resources/user.png" style="height: 80px; width:80px;margin-right: 15px; border-radius: 50%;">
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
            <hr>
            @endforeach
            <a id="instructors"><h3 class="mb-3 blue-text font-weight-bold">Instructors</h3></a>
            <div class="row">
                @foreach ($instructors as $instructor)
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-none grey lighten-4 p-4" style="height: 100%;">
                            <div class="d-flex flex-row">
                                <img src="{{$instructor->user->avatar()}}" style="height: 80px; width:80px;margin-right: 15px; border-radius: 50%;">
                                <div class="d-flex flex-column">
                                    <h4 class="font-weight-bold">
                                        {{$instructor->user->fullname('FL')}}
                                    </h4>
                                    <p>{{$instructor->staffPageTagline()}}</p>
                                    <p class="mb-0">
                                        <a href="mailto:{{$instructor->email()}}"><i class="fa fa-envelope"></i>&nbsp;Email</a>&nbsp;&nbsp;•&nbsp;&nbsp;<a href=""  data-toggle="modal" data-target="#viewInstructorBio{{$instructor->id}}"><i class="fas fa-user"></i>&nbsp;Biography</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <p class="text-muted mt-3">
        Icons made by <a href="https://www.flaticon.com/authors/freepik" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon"> www.flaticon.com</a>
    </p>
</div>

@foreach ($staff as $member)
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
