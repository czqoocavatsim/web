@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    <div class="container" style="margin-top: 20px;">
        <h2>View Ticket #{{ $ticket->ticket_id }}</h2>
        <h4>{{$ticket->title}}</h4>
        <hr class="my-1">
        <br/>
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
            Department:
            @if ($ticket->department == "firchief")
                FIR Chief
            @elseif ($ticket->department == "leadinstructor")
                Lead Instructor
            @elseif ($ticket->department == "webmaster")
                Webmaster
            @else
                Feedback
            @endif
            <br/>
            Submitted by {{App\User::find($ticket->user_id)->fullName('FLC')}} at {{$ticket->submission_time}}<br/>
            Last updated at {{$ticket->updated_at}}
        </p>
        <h5>Message</h5>
        <div class="border p-3">
            {!!html_entity_decode($ticket->message)!!}
        </div>
        <br/>
        <h5>Replies</h5>
        @if (count($replies) < 1)
            No replies yet!
        @else
            <div class="list-group">
                @foreach ($replies as $reply)
                    <div class="list-group-item" @if ($reply->user_id == 1) style="background-color: #bfe0fb;" @endif">
                        <h6>{{App\User::find($reply->user_id)->fullName('FLC')}} at {{$reply->submission_time}}</h6>
                        <div id="replyContent{{$reply->id}}" class="text">
                            {!! html_entity_decode($reply->message) !!}
                        </div>
                        <script>
                            $(document).ready(function() {
                                $('.text').emoticonize();
                            })
                        </script>
                    </div>
                @endforeach
            </div>
        @endif
        <br/>
        @if ($ticket->status != 1)
        <h5>Write a reply</h5>
        {!! Form::open(['route' => ['tickets.reply', $ticket->ticket_id]]) !!}
        <script>
            tinymce.init({
                selector: '#addReplyMessage',
                plugins: 'link media table',
                menubar: 'edit insert format'
            });
        </script>
        {!! Form::textarea('message', null, ['class' => 'form-control', 'id' => 'addReplyMessage']) !!}
        <br/>
        {!! Form::submit('Reply', ['class' => 'btn btn-success']) !!}
        <a href="{{url('/dashboard/tickets/'.$ticket->ticket_id.'/close')}}" role="button" class="btn btn-outline-danger ml-3">Close Ticket</a>
        {!! Form::close() !!}
        @endif
    </div>
@stop