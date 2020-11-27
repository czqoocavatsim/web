@extends('admin.training.layouts.main')
@section('title', 'Dashboard - Training - ')
@section('training-content')
<h1 class="blue-text mb-2"><span id="greeting">Hello</span>, {{Auth::user()->fullName('F')}}!</h1>
<p class="lead mb-4">@if(Auth::user()->instructorProfile->current)You are a <span class="blue-text">{{Auth::user()->instructorProfile->staffPageTagline()}}</span> with <span class="blue-text">{{count(Auth::user()->instructorProfile->studentsAssigned)}}</span> students assigned to you.@else Welcome. @endif</p>
<div class="row">
    <div class="col-md-6">
        @can('view applications')
        <div class="card p-4 z-depth-1 shadow-none">
            <h4 class="blue-text mb-3">{{count($applications)}} pending applications</h4>
            @if(count($applications) < 1)
                None pending! Well done.
            @else
                <div class="list-group">
                    @foreach ($applications as $a)
                        <a href="{{route('training.admin.applications.view', $a->reference_id)}}" class="list-group-item waves-effect list-group-item-action">
                            <div class="d-flex flex-row w-100 justify-content-between align-items-center">
                                <div>
                                    <h5>{{$a->user->fullName('FLC')}}</h5>
                                    <p class="mb-0">Submitted {{$a->created_at->diffForHumans()}}</p>
                                </div>
                                <i style="font-size: 1.6em;" class="blue-text fas fa-chevron-right fa-fw"></i>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
            <ul class="list-unstyled mt-4 mb-0">
                <li>
                    <a href="{{route('training.admin.applications')}}" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Applications</span></a>
                </li>
            </ul>
        </div>
        @endcan
    </div>
    <div class="col-md-6">
        <div class="card p-4 z-depth-1 shadow-none">
            <h4 class="blue-text mb-3">{{count($readyForPickup)}} students ready for pickup</h4>
            @if(count($readyForPickup) < 1)
                None in the waiting list!
            @else
                <div class="list-group">
                    @foreach ($readyForPickup as $s)
                        <a href="{{route('training.admin.instructing.students.view', $s->user->id)}}" class="list-group-item rounded waves-effect list-group-item-action">
                            <div class="d-flex flex-row w-100 justify-content-between align-items-center">
                                <div>
                                    <h5>{{$s->user->fullName('FLC')}}</h5>
                                    <p class="mb-0">Waiting for {{$s->created_at->diffInDays()}} days</p>
                                </div>
                                <i style="font-size: 1.6em;" class="blue-text fas fa-chevron-right fa-fw"></i>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var date = new Date();
        if (date.getHours() < 12) { $("#greeting").text("Good morning") }
        if (date.getHours() < 17) { $("#greeting").text("Good afternoon") }
        if (date.getHours() > 17) { $("#greeting").text("Good evening") }
    })
</script>
@endsection
