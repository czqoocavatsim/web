@extends('admin.community.layouts.main')
@section('community-content')
    <div class="container py-4">
        <a href="{{route('community.tickets.all')}}" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Return to All Tickets</a>

        <h3 class="fw-700 blue-text mb-0 mt-2">Ticket Details</h3>
        <div class="row">

            <div class="col-md-5">
                <h5 class="fw-700 blue-text mb-0">Author Details</h5>
                <p class="mt-1" style="font-size: 1.1em;"><b>Ticket Author:</b> {{$ticket->user->fullName('FLC')}}</p>
                <p style="font-size: 1.1em;"><b>Created Date:</b> {{\Carbon\Carbon::parse($ticket->created_at)->format('d/m/y H:i')}}Z</p>
                <p class="mt-1" style="font-size: 1.1em;"><b>Ticket Category:</b> {{$ticket->type->name}}</p>
            </div>
            <div class="col-md-5">
                <h5 class="fw-700 blue-text mb-0">Status</h5>
                <p class="mt-1" style="font-size: 1.1em;"><b>Ticket Status:</b>
                    @if($ticket->status == 0)
                        <span class="badge bg-primary">Pending</span>
                    @elseif($ticket->status == 1)
                        <span class="badge bg-warning">In Progress</span>
                    @else
                        <span class="badge bg-success">Completed</span>
                    @endif
                </p>
                <p style="font-size: 1.1em;"><b>Assigned Agent:</b> @if($ticket->assigned_user !== null){{$ticket->assignedUser->fullName('FLC')}}@else N/A @endif</p>
                <p class="mt-1" style="font-size: 1.1em;"><b>Ticket Actions:</b>
                    @if($ticket->status == 0)
                        <a href="{{route('community.tickets.pickup', [$ticket->slug])}}">Pick Up Ticket</a>
                    @elseif($ticket->status == 1)
                        <a href="{{route('community.tickets.drop', [$ticket->slug])}}">Drop Ticket</a>
                    @else
                        N/A
                    @endif
                </p>
            </div>
        </div>
       
{{-- Ticket Main Comment Div --}}
<div class="comment-box mb-2" style="background-color:rgba(0, 38, 255, 0.227); min-height: 15vh;">
<div class="comment-content"><b><u>Details:</u></b>
{{$ticket->submissionContentHtml()}}
@if(!$ticket_fields->isEmpty())

<b><u>Ticket Information Fields:</u></b>
@foreach($ticket_fields as $tf)
<b>Type:</b> {{$tf->name}}
<b>Content:</b> {{$tf->content}}
@endforeach
@endif
</div>

        </div>

        <h3 class="fw-700 blue-text mb-1 mt-4">Ticket Communications</h3>
        <a href="" data-toggle="modal" data-target="#createMessageModal" class="blue-text" style="font-size: 1.1em;"><i class="fas fa-plus"></i> Send a Message</a>

        @foreach($ticket_comments as $tc)
            @if($tc->comment_type == 9)
                
            @else
                <div class="comment-box mb-2" style="background-color:
                    @if($tc->comment_type == 0) rgba(0, 38, 255, 0.467)
                    @elseif($tc->comment_type == 1) rgba(221, 0, 255, 0.467) 
                    @elseif($tc->comment_type == 2) rgba(95, 95, 95, 0.467)
                    @endif
                ">

                <div class="comment-content">{{$tc->feedbackCommentHtml()}}</div>
                <div class="comment-footer">
                    <span><i class="fa fa-user text-muted"></i> {{$tc->user->FullName('FLC')}} |@if($tc->comment_type ==0) Author Comment |@elseif($tc->comment_type ==1) Staff Response |@elseif($tc->comment_type ==2) Private Staff Note |@endif Posted {{\Carbon\Carbon::parse($tc->created_at)->diffforHumans()}}</span>
                </div>
            </div>
            @endif
        @endforeach
         
    </div>

    <!--Start create session modal-->
<div class="modal fade" id="createMessageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Send a Message</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('community.tickets.comment.add', [$ticket->slug])}}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Send a message to {{$ticket->user->FullName('F')}}</p>

                    {{-- Error Details --}}
                    @if($errors->createSessionErrors->any())
                    <div class="alert alert-danger">
                        <h4>There were errors</h4>
                        <ul class="pl-0 ml-0 list-unstyled">
                            @foreach ($errors->createSessionErrors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
                    <div class="form-group mt-4">
                        <label for="comment">Comment</label>
                        <textarea id="contentMD" name="comment"></textarea>
                    </div>

                    <div class="form-group mt-4">
                        <label for="">Comment View Level</label>
                        <select required name="comment_type" id="" class="form-control">                         
                            <option value="1">Author Can See Comment (Public)</option>
                            <option value="2">SENIOR STAFF ONLY (PRIVATE)</option>
                        </select>
                    </div>

                    <div class="form-group mt-4">
                        <label for="">Ticket Status</label>
                        <select required name="ticket_status" id="" class="form-control">                         
                            @if($ticket->status == 0 || $ticket->status == 1)
                                <option value="1">Keep Ticket Open</option>
                                <option value="2">Close the Ticket</option>
                            @elseif($ticket->status == 2)
                                <option value="2">Keep Ticket Closed</option>
                                <option value="1">Open the Ticket Again</option>
                            @endif
                        </select>
                    </div>

                    <input type="hidden" name="submission_id" value="{{$ticket->id}}">

                    <p class="mt-4 mb-0 rounded bg-light p-3">Please double check all details before submission.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Create">
                </div>
            </form>
        </div>
    </div>
</div>
<!--End create session modal-->
<script>
    var simplemde = new EasyMDE({ 
        element: document.getElementById("contentMD"),
        maxHeight: '200px',
        autofocus: true,
    });

    // Ensure content is saved before submitting
    document.querySelector("form").addEventListener("submit", function() {
        document.getElementById("contentMD").value = simplemde.value();
    });
</script>

<style>
    .comment-box {
        padding: 15px;
        border-width: 0px;
        border-radius: 10px;
        width: 100%;
        min-height: 10vh;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        white-space: pre-wrap; /* Preserve line breaks */
        overflow-wrap: break-word; /* Ensure long words break */
    }

    .comment-content {
        flex-grow: 1; /* Allow content to expand */
        user-select: text; /* Make text selectable */
        font-size: 1.1em;
        color: #000000;
    }

    .comment-footer {
        display: flex;
        justify-content: space-between;
        font-size: 0.9em;
        color: #555;
        margin-top: 10px;
        font-weight: 500;
    }
</style>
    
@endsection
