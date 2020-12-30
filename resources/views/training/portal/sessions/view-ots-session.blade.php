@extends('training.portal.layouts.main')
@section('title', 'Sessions - Training Portal - ')
@section('page-header-title', 'Your sessions')

@section('portal-content')
<a href="{{route('training.portal.sessions')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Your sessions</a>
<div class="d-flex flex-row align-items-center mt-3">
    <img src="{{$session->instructor->user->avatar()}}" class="z-depth-1" style="height: 50px; width:50px;margin-right: 15px; margin-bottom: 3px; border-radius: 50%;">
    <img src="{{$session->student->user->avatar()}}" class="z-depth-1" style="height: 50px; z-index: 50; margin-left: -30px; width:50px;margin-right: 15px; margin-bottom: 3px; border-radius: 50%;">
    <div>
        <h2 class="blue-text mt-2 mb-1 font-weight-bold">OTS Session with {{$session->instructor->user->fullName('FL')}}</h2>
        <h5 class="fw-500">
            Scheduled for {{$session->scheduled_time->toDayDateTimeString()}}
        </h5>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <h5 class="blue-text fw-500">Scheduled start</h5>
        <h5 class="d-flex flex-row align-items-center">
            {{$session->scheduled_time->toDayDateTimeString()}}
        </h5>
        <h5 class="mt-4 blue-text fw-500">Position conducted on</h5>
        @if ($session->position)
            <div class="list-group-item z-depth-1 rounded">
                <div class="d-flex flex-row w-100 align-items-center h-100 justify-content-between">
                    <div class="d-flex flex-row align-items-center">
                        <i class="fas fa-wifi fa-fw mr-2"></i>
                        <div class="d-flex flex-column align-items-center h-100">
                            <h5 class="mb-0 fw-500">{{$session->position->identifier}}</h5>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="list-group-item z-depth-1 rounded">
                <p>Not assigned by assessor.</p>
            </div>
        @endif
    </div>
    <div class="col-md-6">
        <h5 class="blue-text fw-500">Assessor</h5>
        <div class="list-group-item z-depth-1 rounded">
            <div class="d-flex flex-row w-100 align-items-center h-100 justify-content-between">
                <div class="d-flex flex-row align-items-center">
                    <img src="{{$session->instructor->user->avatar()}}" style="height: 30px; width:30px;margin-right: 15px; border-radius: 50%;">
                    <div class="d-flex flex-column align-items-center h-100">
                        <h5 class="mb-0 fw-500">{{$session->instructor->user->fullName('FLC')}}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<h3 class="font-weight-bold blue-text mb-3 mt-5">Remarks from assessor</h3>
{{$session->remarksHtml()}}
@endsection
