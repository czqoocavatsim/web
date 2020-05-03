@extends('layouts.master')

@section('content')

<div class="container py-4">
    @if (\Session::has('alreadyApplied'))
        <div class="alert bg-czqo-blue-light">
            {{\Session::get('alreadyApplied')}}
        </div>
    @endif
    <h1 class="font-weight-bold blue-text">Your application (#{{$application->reference_id}})</h1>
    <hr>
    <h4 class="pb-2 font-weight-bold">Latest update</h4>
    <div id="latestUpdate">
        @if (!$latestUpdate)
            No update found
        @else
            <div class="card p-4 mb-3">
                <p style="font-size: 1.02rem;">{{$latestUpdate->created_at->diffForHumans()}}</p>
                <h3 class="font-weight-bold {{$latestUpdate->update_type}}-text">{{$latestUpdate->update_title}}</h3>
                <div>{{$latestUpdate->updateContentHtml()}}</div>
            </div>
            <a href="#">View all updates</a>
        @endif
    </div>
    <div class="row mt-4">
        <div class="col-md-6">
            <h4 class="blue-text font-weight-bold">Details</h4>
            <div class="card p-4">
                <p style="font-size: 1.1em">
                    Submitted at<br>
                    <span class="font-weight-bold" style="font-size: 1.2em;">{{$application->created_at->toDayDateTimeString()}}</span>
                </p>
                <p style="font-size: 1.1em">
                    Last updated at<br>
                    <span class="font-weight-bold" style="font-size: 1.2em;">{{$application->updated_at->toDayDateTimeString()}}</span>
                </p>
                <p style="font-size: 1.1em" style="mb-0">
                    Your statement<br>
                </p>
                <span style="border: 1px solid #eeeeee;" class="p-2 mb-3">
                    {{$application->applicantStatementHtml()}}
                </span>
                <p style="font-size: 1.1em">
                    Actions<br>
                </p>
                <div class="d-flex flex-row">
                    <a href="#" data-toggle="modal" data-target="#withdrawApplicationModal" class="btn btn-light" role="button">Withdraw application</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <h4 class="blue-text font-weight-bold">Comments</h4>
            <p>Use comments to communicate with FIR staff in case clarifications about your application are needed.</p>
            @if (count($comments) < 1)
                No comments made yet.
            @else
            @endif

        </div>
    </div>
</div>

<!--Withdraw application modal-->
<div class="modal fade" id="withdrawApplicationModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Withdraw application</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                If you do not wish to proceed with your application, you may withdraw it. You are free to apply again in the future.
                <form action="{{route('training.applications.withdraw')}}" method="POST">
                    @csrf
                    <input type="hidden" name="refnce_id" value="{{$application->reference_id}}">
                    <br>
                    <button id="withdrawAppB" class="btn btn-danger mt-3">Withdraw application</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
