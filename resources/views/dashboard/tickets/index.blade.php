@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
        <div class="container" style="margin-top: 20px;">
        <a href="{{route('dashboard.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Dashboard</a>
        <h1 class="blue-text font-weight-bold mt-2">Support Tickets</h1>
        <hr>
        <div class="mb-2">
            <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                <button data-toggle="modal" data-target="#startTicketModal" type="button" class="btn btn-outline btn-sm bg-czqo-blue-light">Start A Ticket</button>
            </div>
        </div>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Open Tickets</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Closed Tickets</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">On Hold Tickets</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active pt-2" id="home" role="tabpanel" aria-labelledby="home-tab">
                @if (count($openTickets) < 1)
                    No open tickets.
                @else
                    @foreach ($openTickets as $ticket)
                        <a href="{{url('/dashboard/tickets/' . $ticket->ticket_id)}}" class="list-group-item list-group-item-action flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">{{$ticket->title}}</h5>
                            </div>
                            <p class="mb-1 text-info">
                                <h6>#{{ $ticket->ticket_id }}</h6>
                                {{count($ticket->replies)}} replies
                            </p>
                            <small>Submitted at {{ $ticket->submission_time }} Zulu</small>
                        </a>
                    @endforeach
                @endif
            </div>
            <div class="tab-pane fade pt-2" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                @if (count($closedTickets) < 1)
                    No closed tickets.
                @else
                    @foreach ($closedTickets as $ticket)
                        <a href="{{url('/dashboard/tickets/' . $ticket->ticket_id)}}" class="list-group-item list-group-item-action flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">{{$ticket->title}}</h5>
                            </div>
                            <p class="mb-1 text-info">
                            <h6>#{{ $ticket->ticket_id }}</h6>
                            {{count($ticket->replies)}} replies
                            </p>
                            <small>Submitted at {{ $ticket->submission_time }} Zulu</small>
                        </a>
                    @endforeach
                @endif
            </div>
            <div class="tab-pane fade pt-2" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                @if (count($onHoldTickets) < 1)
                    No on hold tickets.
                @else
                    @foreach ($onHoldTickets as $ticket)
                        <a href="{{url('/dashboard/tickets/' . $ticket->ticket_id)}}" class="list-group-item list-group-item-action flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">{{$ticket->title}}</h5>
                            </div>
                            <p class="mb-1 text-info">
                            <h6>#{{ $ticket->ticket_id }}</h6>
                            {{count($ticket->replies)}} replies
                            </p>
                            <small>Submitted at {{ $ticket->submission_time }} Zulu</small>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>

    </div>
    <!--create a ticket modal-->
    <div class="modal fade" id="startTicketModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Start a new ticket</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {!! Form::open(['route' => 'tickets.startticket']) !!}
                <div class="modal-body">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Staff Member</label>
                        <select name="staff_member" id="staff_member_select" class="form-control">
                            <option value="" selected hidden>Please select one...</option>
                            @foreach ($staff_members as $s)
                            <option value="{{$s->shortform}}">{{$s->position}} - {{$s->user->fullName('FLC')}}</option>
                            @endforeach
                        </select>
                        <small>For general feedback, choose the Executive Team.</small>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Title</label>
                        {!! Form::text('title', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Message</label>
                        {!! Form::textarea('message', null, ['class' => 'form-control', 'id' => 'createTicketMessage']) !!}
                        <small>Minimum 25 characters</small>
                        <script>
                            var simplemde = new SimpleMDE({ element: document.getElementById("createTicketMessage") });
                        </script>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <script>
        //Handle create=yes in url
        var url = new URL(this.location.href);
        if (url.searchParams.get('create') == 'yes') {
            $("#startTicketModal").modal();
        }
    </script>
@stop
