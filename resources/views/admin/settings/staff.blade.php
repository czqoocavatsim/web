@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    <div class="container" style="margin-top: 20px;">
        <a href="{{route('settings.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Settings</a>
        <h1 class="blue-text font-weight-bold mt-2">Staff</h1>
        <hr>
        <p>To set a position to vacant, set it to user ID 1.</p>
        <div class="row">
            @foreach ($staff as $s)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <form method="POST" action="{{route('settings.staff.editmember', $s->id)}}">
                    @csrf
                    <div class="card-header">{{$s->position}} ({{$s->shortform}})</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5>
                                    {{$s->user->fullName('FLC')}}
                                    @if ($s->user->id == 1)
                                    (Vacant)
                                    @endif
                                </h5>
                                <label>User ID (CID)</label>
                                <input required type="text" name="cid" class="form-control form-control-sm" value="{{$s->user_id}}">
                            </div>
                            <div class="col">
                                <div class="text-center">
                                    <img src="{{$s->user->avatar}}" style="width: 75px; height: 75px; margin-bottom: 10px; border-radius: 50%;">
                                </div>
                            </div>
                        </div>
                        <br/>
                        <label>Description</label>
                        <textarea required class="form-control" name="description">{{$s->description}}</textarea>
                        <br/>
                        <label>Email</label>
                        <input required type="email" class="form-control" name="email" value="{{$s->email}}">
                        <br/>
                        <input required type="submit" class="btn btn-success btn-block btn-sm" value="Save">
                    </div>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@stop
