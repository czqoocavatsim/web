@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    <div class="container" style="margin-top: 20px;">
        <h2>Your Tickets</h2>
        <nav class="navbar navbar-light bg-light">
            <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                <button data-toggle="modal" data-target="#startTicketModal" type="button" class="btn btn-outline-success btn-sm">Start A Ticket</button>
            </div>
            <form class="form-inline">
                <input class="form-control form-control-sm" placeholder="Search for a ticket.." type="search" disabled>
            </form>
        </nav>
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
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
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
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
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
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
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
                <div class="modal-body">
                    {!! Form::open(['route' => 'tickets.startticket']) !!}
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Department</label>
                        {!! Form::select('department', ['firchief' => 'FIR Chief', 'chiefinstructor' => 'Chief Instructor', 'webmaster' => 'Webmaster', 'feedback' => 'Controller Feedback/Other'], ['placeholder' => 'Please choose one..'], ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Title</label>
                        {!! Form::text('title', null, ['class' => 'form-control']) !!}
                    </div>
                    <script>
                        tinymce.init({
                            selector: '#createTicketMessage',
                            plugins: 'link media table',
                            menubar: 'edit insert format'
                        });
                    </script>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Message</label>
                        {!! Form::textarea('message', null, ['class' => 'form-control', 'id' => 'createTicketMessage']) !!}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop