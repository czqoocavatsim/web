@extends('layouts.master')
@section('content')
<div class="container py-4">
    <a href="{{route('news.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> News</a>
    <h1 class="font-weight-bold blue-text">{{$announcement->title}}</h1>
    <h5>Sent {{$announcement->created_at->toDayDateTimeString()}}</h5>
    @csrf
    <div class="row">
        <div class="col-md-12">
            <ul class="stepper mt-0 p-0 stepper-vertical">
                <li class="active">
                    <a href="#!">
                        <span class="circle">1</span>
                        <span class="label">Primary information</span>
                    </a>
                    <div class="step-content w-75 pt-0">
                        <div class="form-group">
                            <label for="">Announcement title</label>
                            <p>{{$announcement->title}}</p>
                        </div>
                        <div class="form-group">
                            <label for="">Target group</label>
                            <p>{{ucfirst($announcement->target_group)}}</p>
                        </div>
                        <div class="form-group">
                            <label for="">Sent by</label>
                            <p>{{$announcement->user->fullName('FLC')}}</p>
                        </div>
                    </div>
                </li>
                <li class="active">
                    <a href="#!">
                        <span class="circle">2</span>
                        <span class="label">Content of announcement</span>
                    </a>
                    <div class="step-content w-75 pt-0">
                        {{$announcement->html()}}
                    </div>
                </li>
                <li class="active">
                    <a href="#!">
                        <span class="circle">3</span>
                        <span class="label">GDPR requirements</span>
                    </a>
                    <div class="step-content w-75 pt-0">
                        <div class="form-group">
                            <label for="">Why was this announcement sent?</label>
                            <p>{{$announcement->reason_for_sending}}</p>
                        </div>
                        <div class="form-group">
                            <label for="">Other notes</label>
                            <p>{{$announcement->notes}}</p>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
