@extends('admin.training.layouts.main')
@section('training-content')
<a href="{{route('training.admin.applications')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Applications</a>
<h2 class="blue-text mt-2 pb-2">#{{$application->reference_id}} - {{$application->user->fullName('FLC')}}</h2>
<div class="py-2">
    <h3 class="blue-text mb-3">Details</h3>
    <div class="row ">
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
            <h6>Statement</h6>
            <div style="border: 1px solid #929292;" class="p-3">
                {{$application->applicantStatementHtml()}}
            </div>
        </div>
        <div class="col-md-3">
            <h6>Actions</h6>
            <ul class="list-unstyled mt-3 mb-0" style="font-size: 1.05em;">
                <li class="mb-2">
                    <a href="" data-toggle="modal" data-target="#refereesModal" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="text-body">View referees</span></a>
                </li>
                @if($application->status == 0)
                <li class="mb-2">
                    <a href="{{route('training.admin.applications.accept', $application->reference_id)}}" style="text-decoration:none;"><span class="green-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="text-body">Accept application</span></a>
                </li>
                <li class="mb-2">
                    <a href="" data-toggle="modal" data-target="#rejectModal" style="text-decoration:none;"><span class="red-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="text-body">Reject application</span></a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>

<div class="py-2">
    <h3 class="blue-text mb-3">Applicant</h3>
    <div class="row">
        <div class="col-md">
            <h5>Identity</h5>
            <ul class="list-unstyled">
                <li>Subdivision: {{$application->user->subdivision_code ? $application->user->subdivision_name.'('.$application->user->subdivision_code.')' : 'None'}}</li>
                <li>Division: {{$application->user->division_name}} ({{$application->user->division_code}})</li>
                <li>Region: {{$application->user->region_name}} ({{$application->user->region_code}})</li>
                <li>Rating: {{$application->user->rating_GRP}} ({{$application->user->rating_short}})</li>
                @can('view user details')
                <li>Email: {{$application->user->email}}</li>
                @endcan
            </ul>
        </div>
        <div class="col-md">
            <h5>Network Activity</h5>
            <ul class="list-unstyled">
                <li>Hours on C1+: {{$hoursTotal}}</li>
                <li>Total ATC Hours: {{$hoursObj->atc}}</li>
                <li class="mt-3 mb-2">
                    <a target="_blank" href="https://stats.vatsim.net/search_id.php?id={{$application->user->id}}" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="text-body">Go to VATSIM Stats</span></a>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="py-2">
    <h3 class="blue-text mb-3">Comments</h3>
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
                                        <img src="{{$comment->user->avatar()}}" style="height: 30px; width: 30px; margin-right: 7px; border-radius: 50%;">
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
                @if($application->status == 0)
                <hr>
                <p>Write a comment</p>
                <form action="{{route('training.admin.applications.comment.post')}}" method="POST">
                    @csrf
                    <input type="hidden" name="reference_id" value="{{$application->reference_id}}">
                    <textarea name="comment" required id="" style="height: 100px; width: 100%; border-radius: 2.5%; border: 1px solid #eeeeee;">{{old('comment')}}</textarea>
                    <button class="btn btn-sm btn-light" style="font-weight: 400;">Submit Staff Comment</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>


<!--Begin referees modal-->
<div class="modal fade" id="refereesModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Referees</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @foreach($application->referees as $r)
                <h5 class="font-weight-bold">{{$r->referee_full_name}}</h5>
                <ul class="list-unstyled">
                    <li>
                        <p>
                            <span class="font-weight-bold">Email</span>
                            <br>
                            {{$r->referee_email}}
                        </p>
                    </li>
                    <li>
                        <p>
                            <span class="font-weight-bold">Staff position</span>
                            <br>
                            {{$r->referee_staff_position}}
                        </p>
                    </li>
                </ul>
                @endforeach
            </div>
        </div>
    </div>
</div>
<!--End referees modal-->


<!--Begin reject modal-->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject application</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if(!$comments->where('user_id', '!=', $application->user_id))
                    <div class="alert red lighten-1">
                        Please send a comment detailing reasons for rejection before rejecting this application.
                    </div>
                @else
                    <p>Are you sure you wish to reject this application?</p>
                    <a role="button" href="{{route('training.admin.applications.reject', $application->reference_id)}}" class="btn btn-danger mt-3">Reject application</a>
                @endif
            </div>
        </div>
    </div>
</div>
<!--End reject modal-->

@endsection
