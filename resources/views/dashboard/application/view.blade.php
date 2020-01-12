@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    <div class="container" style="margin-top: 20px;">
        <div class="container" style="margin-top: 20px;">
            <a href="{{route('application.list')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Applications</a>
        <h1 class="blue-text font-weight-bold mt-2">Applications #{{$application->application_id}}</h1>
        <hr>
        <br/>
        <h6>Status</h6>
        @if ($application->status == 0)
            <p class="mb-1 text-info">
                <i class="fa fa-clock"></i>&nbsp;
                Pending
            </p>
        @elseif ($application->status == 2)
            <p class="mb-1 text-success">
                <i class="fa fa-check"></i>&nbsp;
                Accepted
            </p>
        @elseif ($application->status == 1)
            <p class="mb-1 text-danger">
                <i class="fa fa-times"></i>&nbsp;
                Denied
            </p>
        @elseif ($application->status == 3)
            <p class="mb-1 text-dark">
                <i class="fa fa-times"></i>&nbsp;
                Withdrawn
            </p>
        @endif
        <p class="mb-1">
            Processed by:
            @if (!$application->processed_by    )
            no one yet.
            @else
            {{\App\User::find($application->processed_by)->fullName('FLC')}}
            @endif
        </p>
        <p class="mb-1">
            Processed at:
            @if (!$application->processed_at)
            not yet processed.
            @else
            {{$application->processed_at}}
            @endif
        </p>
        <br/>
        <h6>Comments from Staff</h6>
        <div class="border p-2 pl-4">
            @if ($application->staff_comment != null)
                {!! html_entity_decode($application->staff_comment) !!}
            @else
                None
            @endif
        </div>
        <br/>
        <h6>Applicant Statement</h6>
        <div class="border p-2 pl-4">
            {!! html_entity_decode($application->applicant_statement) !!}
        </div>
        <br/>
        <h6>Submitted at {{ $application->submitted_at }} Zulu</h6>
        <br/>
        <div>
            <a class="btn btn-secondary" href="{{route('application.list')}}">
                Go back
            </a>
            @if ($application->status == "Pending")
                <a class="btn btn-danger" href="{{url('dashboard/application/' . $application->application_id . '/withdraw')}}">
                    Withdraw
                </a>
            @endif
        </div>
        <br/>
    </div>
@stop
