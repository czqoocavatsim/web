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
                        <a href="">Pick Up Ticket</a>
                    @elseif($ticket->status == 1)
                        <span class="badge bg-warning">In Progress</span>
                    @else
                        <span class="badge bg-success">Completed</span>
                    @endif
                </p>
                <p style="font-size: 1.1em;"><b>Assigned Agent:</b> {{$ticket->assignedUser->fullName('FLC')}}</p>
            </div>
        </div>
        
        <div class="comment-box mb-2" style="background-color:rgba(0, 38, 255, 0.227); min-height: 15vh;">
            <div class="comment-content">{{$ticket->submission_content}}</div>
        </div>

        <h3 class="fw-700 blue-text mb-1 mt-4">Ticket Communications</h3>
        @if($ticket->status == 0 || $ticket->status == 1)<a href="{{route('community.tickets.all')}}" style="font-size: 1.2em;"> <i class="fas fa-plus"></i> Send A Message</a>@endif

        {{-- Comment from the Original Author --}}
        <div class="comment-box mb-2" style="background-color:rgba(0, 38, 255, 0.467)">
            <div class="comment-content">Comment from Ticket Author if conversation is needed after the initial ticket post.</div>
            <div class="comment-footer">
                <span><i class="fa fa-user text-muted"></i> [AUTHOR NAME] on [POSTED TIME]</span>
            </div>
        </div>

        <div class="comment-box mb-2" style="background-color:rgba(221, 0, 255, 0.467)">
            <div class="comment-content">Staff Response Comment.</div>
            <div class="comment-footer">
                <span><i class="fa fa-user text-muted"></i> [AUTHOR NAME] on [POSTED TIME]</span>
            </div>
        </div>

        <div class="comment-box mb-2" style="background-color:rgba(95, 95, 95, 0.467)">
            <div class="comment-content">Staff hidden comment for internal discussion.</div>
            <div class="comment-footer">
                <span><i class="fa fa-user text-muted"></i> [AUTHOR NAME] on [POSTED TIME]</span>
            </div>
        </div>
    </div>
    
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
