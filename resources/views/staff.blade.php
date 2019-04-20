@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
<div class="container" style="margin-top: 20px;">
    <h2>Gander Oceanic Staff</h2>
    <br>
    <h4><b>Executive</b></h4>
    <div class="row">
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
                        <img src="{{$member->user->avatar}}" style="width: 125px; height:125px; margin-bottom: 10px; border-radius: 50%;">
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
                    <img src="{{ $instructor->user->avatar }}" style="width: 125px; height:125px; margin-bottom: 10px; border-radius: 50%;">
                    <h4 style="margin-bottom: 2px;"><b>{{$instructor->user->fullName('FL')}}</b></h4>
                    <p style="margin: 0;"><i>{{$instructor->qualification}}</i></p>
                    <p>
                        <a href="mailto:{{$instructor->email}}"><i class="fa fa-envelope"></i>&nbsp;{{$instructor->email}}</a>
                    </p>
                </div>
            </div>
        @endforeach
    </div>

</div>
@stop
