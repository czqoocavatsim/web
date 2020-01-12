@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    <div class="container" style="margin-top: 20px;">
        <div class="container" style="margin-top: 20px;">
            <a href="{{route('tickets.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Back</a>
        <h1 class="blue-text font-weight-bold mt-2">Ticket #{{ $ticket->ticket_id }}</h1>
        <hr>
        <h4>{{$ticket->title}}</h4>
        <p>
            Status:
            @if ($ticket->status == 0)
                Open
            @elseif ($ticket->status == 1)
                Closed
            @else
                On Hold
            @endif
            <br/>
            Staff Member: {{$ticket->staff_member->user->fullName('FLC')}} ({{$ticket->staff_member->position}})
            <br/>
            Submitted by {{$ticket->user->fullName('FLC')}} on <span title="{{$ticket->submission_time}}">{{$ticket->submission_time_pretty()}}</span><br/>
            Last updated <span title="{{$ticket->updated_at}}">{{$ticket->updated_at_pretty()}}</span>
        </p>
        <h5>Message</h5>
        <div class="markdown border p-3">
            {{$ticket->html()}}
        </div>
        <br/>
        <h5>Replies</h5>
        @if (count($replies) < 1)
            No replies yet!
        @else
            <div class="list-group">
                @foreach ($replies as $reply)
                    <div class="list-group-item" @if ($reply->user_id == 1) style="background-color: #bfe0fb;" @endif">
                    <h6>{{$reply->user->fullName('FLC')}} on <span title="{{$reply->submission_time}}">{{$reply->submission_time_pretty()}}</span></h6>
                        <div id="replyContent{{$reply->id}}" class="text markdown">
                            {{$reply->html()}}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        <br/>
        @if ($ticket->status != 1)
        <h5>Write a reply</h5>
        {!! Form::open(['route' => ['tickets.reply', $ticket->ticket_id]]) !!}
        {!! Form::textarea('message', null, ['class' => 'form-control', 'id' => 'addReplyMessage']) !!}
        <small>Minimum 25 characters</small>
        <script>
            var simplemde = new SimpleMDE({ element: document.getElementById("addReplyMessage") });
        </script>
        <br/>
        {!! Form::submit('Reply', ['class' => 'btn btn-success']) !!}
        <a href="{{url('/dashboard/tickets/'.$ticket->ticket_id.'/close')}}" role="button" class="btn btn-outline-danger ml-3">Close Ticket</a>
        {!! Form::close() !!}
        @endif
    </div>
@stop
