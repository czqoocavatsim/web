@extends('layouts.master')

@section('content')

<div class="container py-4">
    @if (\Session::has('alreadyApplied'))
        <div class="alert bg-czqo-blue-light">
            {{\Session::get('alreadyApplied')}}
        </div>
    @endif
    <a href="{{route('training.applications.showall')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i>  Applications</a>
    <h1 class="font-weight-bold blue-text">Your application (#{{$application->reference_id}})</h1>
    <h5 class="pb-4">Submitted {{$application->created_at->toDayDateTimeString()}}</h5>
    <div id="latestUpdate">
        @if (!$latestUpdate)
            No update found
        @else
            <div class="card shadow-none  grey lighten-3 p-4 mb-3">
                <p style="font-size: 1.02rem;" title="{{$latestUpdate->created_at}} GMT">Latest update - {{$latestUpdate->created_at->diffForHumans()}}</p>
                <h3 class="font-weight-bold {{$latestUpdate->update_type}}-text">{{$latestUpdate->update_title}}</h3>
                <div>{{$latestUpdate->updateContentHtml()}}</div>
                <a href="{{route('training.applications.show.updates', $application->reference_id)}}" class="text-muted">View all updates</a>
            </div>
        @endif
    </div>

    <div class="py-2">
        <h3 class="font-weight-bold blue-text mb-3">Details</h3>
        <div class="row">
            <div class="col-md-2">
                <h6>Status</h6>
                <h3>
                    <span class="badge {{$application->statusBadgeHtml()['class']}} rounded shadow-none">
                        {!! $application->statusBadgeHtml()['html'] !!}
                    </span>
                </h3>
            </div>
            <div class="col-md-3">
                <h6>Submitted at</h6>
                <h5>{{$application->created_at->toDayDateTimeString()}}</h5>
            </div>
            <div class="col-md-4">
                <h6>Your statement</h6>
                <div style="border: 1px solid #929292;" class="p-3">
                    {{$application->applicantStatementHtml()}}
                </div>
            </div>
            <div class="col-md-3">
                <h6>Actions</h6>
                <ul class="list-unstyled mt-3 mb-0" style="font-size: 1.05em;">
                    <li class="mb-2">
                        <a href="" data-toggle="modal" data-target="#withdrawApplicationModal" style="text-decoration:none;"><span class="grey-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="text-body">Withdraw application</span></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="py-2">
        <h3 class="font-weight-bold blue-text mb-3">Comments</h3>
        <p>Use comments to communicate with staff about your application. Typically staff will use this function to request further clarification on something.</p>
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-none  grey lighten-3 p-4 mb-3">
                    @if(count($comments) < 1 )
                        <p>No comments yet.</p>
                    @else
                        <div class="d-flex flex-column">
                            @foreach($comments as $comment)
                                @if($comment->user_id != Auth::id())
                                    <div class="align-self-start mb-2" style="width: 75%;">
                                        <div class="d-flex flex-row align-items-centre">
                                            <img src="{{Auth::user()->avatar()}}" style="height: 30px; width: 30px; margin-right: 7px; border-radius: 50%;">
                                            <div style="width: 44%;" class="blue white-text rounded p-2">
                                                <span class="font-weight-bold">{{$comment->user->fullName('FL')}}</span><br>
                                                {{$comment->content}}
                                            </div>
                                        </div>
                                        <span style="font-size: 0.8em; margin-top: 1px;" class="text-muted">{{$comment->created_at->diffForHumans()}}</span>
                                    </div>
                                @else
                                    <div class="align-self-end mb-2" style="width: 75%;">
                                        <div class="d-flex flex-row align-items-centre justify-content-end">
                                            <div style="width: 44%;" class="white rounded p-2">
                                                <span class="font-weight-bold">You</span><br>
                                                {{$comment->content}}
                                            </div>
                                            <img src="{{Auth::user()->avatar()}}" style="height: 30px; width: 30px; margin-left: 7px; border-radius: 50%;">
                                        </div>
                                        <span style="font-size: 0.8em; margin-top: 4px; text-align:right; float:right;" class="text-muted">{{$comment->created_at->diffForHumans()}}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                    <hr>
                    <p>Write a comment</p>
                    <form action="{{route('training.applications.comment.post')}}" method="POST">
                        @csrf
                        <input type="hidden" name="reference_id" value="{{$application->reference_id}}">
                        <textarea name="comment" required id="" style="height: 100px; width: 100%; border-radius: 2.5%; border: 1px solid #eeeeee;">{{old('comment')}}</textarea>
                        <button class="btn btn-sm btn-primary">Submit Comment</button>
                    </form>
                </div>
            </div>
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
