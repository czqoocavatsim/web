@extends('layouts.master', ['solidNavBar' => false])

@section('title', 'Staff - ')

@section('content')
<div class="card card-image blue rounded-0">
    <div class="text-white text-left rgba-stylish-light pb-2 pt-5 px-4">
        <div class="container">
            <div class="py-5">
                <h1 class="font-weight-bold" style="font-size: 3em;">Staff</h1>
            </div>
        </div>
    </div>
</div>
<div class="container py-4">
    <div class="row">
        <div class="col-md-3">
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
                        <div class="@if($member->shortform == 'ocachief') col-md-12 @else col-md-6 @endif mb-3">
                            <div class="card shadow-none grey lighten-4 p-4">
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
            @else
            <div class="row justify-content-center  mb-2" class="staff_img_container">
                @foreach ($g->members as $member)
                    <div class="col-sm-4">
                        <div style="text-align: center;">
                            @if ($member->user_id == 1)
                                <img src="https://cdn.ganderoceanic.com/resources/user.png" style="width: 125px; margin-bottom: 10px; border-radius: 50%;">
                                <h4 style="margin-bottom: 2px;">
                                    <b>Vacant</b>
                                </h4>
                                <p style="margin: 0;"><i>{{$member->position}}</i></p>
                                <p>{{$member->description}}</p>
                                <p><a href="mailto:{{$member->email}}"><i class="fa fa-envelope"></i>&nbsp;{{$member->email}}</a>
                                </p>
                            @else
                                <div class="staff_img_container">
                                    <div class="staff_img_object">
                                        <img style="height: 125px;" src="{{$member->user->avatar()}}">
                                        <div class="img_overlay">
                                            <div class="img_overlay_text">
                                                <a href="#" data-toggle="modal" data-target="#viewStaffBio{{$member->id}}">View Bio</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h4 style="margin-bottom: 2px;">
                                    <b>{{$member->user->fullName('FL')}}</b>
                                </h4>
                                <p style="margin: 0;"><i>{{$member->position}}</i></p>
                                <p>{{$member->description}}</p>
                                <p><a href="mailto:{{$member->email}}"><i class="fa fa-envelope"></i>&nbsp;{{$member->email}}</a>
                                </p>
                            @endif
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
                    <div class="col-sm-4">
                        <div style="text-align: center;">
                            <div class="staff_img_container">
                                <div class="staff_img_object">
                                    <img style="height: 125px;" src="{{$instructor->user->avatar()}}">
                                    <div class="img_overlay">
                                        <div class="img_overlay_text">
                                            <a href="#" data-toggle="modal" data-target="#viewInstructorBio{{$instructor->id}}">View Bio</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h4 style="margin-bottom: 2px;"><b>{{$instructor->user->fullName('FL')}}</b></h4>
                            <p style="margin: 0;"><i>{{$instructor->staffPageTagline()}}</i></p>
                            <p>
                                <a href="mailto:{{$instructor->email()}}"><i class="fa fa-envelope"></i>&nbsp;{{$instructor->email()}}</a>
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    Icons made by <a href="https://www.flaticon.com/authors/freepik" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon"> www.flaticon.com</a>
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
