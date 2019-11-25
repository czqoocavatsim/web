@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('title', 'Staff - ')

@section('content')
<style>
    .staff_img_container {
        height: 125px;
        width: 100%;
        margin-bottom: 10px;
        text-align: center;
    }

    .staff_img_container .staff_img_object {
        width: 125px;
        background: inherit;
        display: inline-block;
        vertical-align: middle;
    }

    .staff_img_container img {
        height: 100%;
        width: 100%;
        border-radius: 50%;
        background: inherit;

    }

    .staff_img_container .img_overlay {
         position: absolute;
         z-index: 998;
         top: 0;
         opacity: 0;
         height: 125px;
         width: 125px;
         border-radius: 50%;
         background: inherit;
        transition: 0.5s ease;
    }

    .staff_img_container .img_overlay_text{
        z-index: 999;
        color: white;
        margin-top: 40%;
        opacity: 1;
        font-size: 1.5em;
    }

    .staff_img_container .img_overlay_text a {
        transition: 0.4s ease;
        color: #fff;
    }

    .staff_img_container .img_overlay_text a:hover {
        text-decoration: underline;
    }

    .staff_img_container .img_overlay:hover {
        opacity: 0.6;
        background: #000;
    }
</style>
<div class="container" style="margin-top: 20px;">
    <h2>Gander Oceanic Staff</h2>
    <br>
    <h4><b>Executive</b></h4>
    <div class="row" class="staff_img_container">
        @foreach ($staff as $member)
            <div class="col-sm-4">
                <div style="text-align: center;">
                    @if ($member->user_id == 1)
                        <img src="https://www.drupal.org/files/profile_default.png" style="width: 125px; margin-bottom: 10px; border-radius: 50%;">
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
                                <img style="height: 125px;" src="{{$member->user->avatar}}">
                                <div class="img_overlay">
                                    <div class="img_overlay_text">
                                        <a href="#" data-toggle="modal" data-target="#viewStaffBio{{$member->id}}">View Bio</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h4 style="margin-bottom: 2px;">
                            <b>{{$member->user->fullName('FLC')}}</b>
                        </h4>
                        <p style="margin: 0;"><i>{{$member->position}}</i></p>
                        <p>{{$member->description}}</p>
                        <p><a href="mailto:{{$member->email}}"><i class="fa fa-envelope"></i>&nbsp;{{$member->email}}</a>
                        </p>
                    @endif
                </div>
            </div>
        @endforeach
    </div><br/>
    <h4><b>Instructors</b></h4>
    <div class="row">
        @foreach ($instructors as $instructor)
            <div class="col-sm-4">
                <div style="text-align: center;">
                    <div class="staff_img_container">
                        <div class="staff_img_object">
                            <img style="height: 125px;" src="{{$instructor->user->avatar}}">
                            <div class="img_overlay">
                                <div class="img_overlay_text">
                                    <a href="#" data-toggle="modal" data-target="#viewInstructorBio{{$instructor->id}}">View Bio</a>
                                </div>
                            </div>
                        </div>
                    </div>                    <h4 style="margin-bottom: 2px;"><b>{{$instructor->user->fullName('FL')}}</b></h4>
                    <p style="margin: 0;"><i>{{$instructor->qualification}}</i></p>
                    <p>
                        <a href="mailto:{{$instructor->email}}"><i class="fa fa-envelope"></i>&nbsp;{{$instructor->email}}</a>
                    </p>
                </div>
            </div>
        @endforeach
    </div>

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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Dismiss</button>
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Dismiss</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
@stop
