@extends('layouts.master')
@section('content')
<div class="container py-4">
    <a href="{{route('events.admin.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Events</a>
    <h1 class="font-weight-bold blue-text">{{$event->name}}</h1>
    <hr>
    <div class="row">
        <div class="col-md-3">
            <h4>Actions</h4>
            <a href="#" class="btn btn-block btn-sm bg-czqo-blue-light">Edit Event</a>
            <a href="#" data-toggle="modal" data-target="#deleteEvent   " class="mt-2 btn btn-block btn-sm btn-danger">Delete Event</a>
        </div>
        <div class="col-md-9">
            <h4>Details</h4>
            <table class="table">
                <thead>
                    <th></th>
                    <th></th>
                </thead>
                <tbody>
                    <tr>
                        <td>Start Time</td>
                        <td>{{$event->start_timestamp_pretty()}}</td>
                    </tr>
                    <tr>
                        <td>End Time</td>
                        <td>{{$event->end_timestamp_pretty()}}</td>
                    </tr>
                    <tr>
                        <td>Departure Airport</td>
                        <td>{{$event->departure_icao}}</td>
                    </tr>
                    <tr>
                        <td>Arrival Airport</td>
                        <td>{{$event->arrival_icao}}</td>
                    </tr>
                </tbody>
            </table>
            <h4 class="mt-4">Description</h4>
            {{$event->html()}}
            <br>
            <h4>Updates</h4>
            <a href="#" class="btn-sm btn bg-czqo-blue-light">New Update</a>
            <br>
            @if (count($updates) == 0)
                None yet!
            @else
                @foreach($updates as $u)
                    <div class="card p-3">
                        <h4>{{$u->title}}</h4>
                        <div class="d-flex flex-row align-items-center">
                            <i class="far fa-clock"></i>&nbsp;&nbsp;Created {{$u->created_pretty()}}</span>&nbsp;&nbsp;•&nbsp;&nbsp;<i class="far fa-user-circle"></i>&nbsp;&nbsp;{{$u->author_pretty()}}&nbsp;&nbsp;•&nbsp;&nbsp;<a href="#" class="red-text">Delete</a>
                        </div>
                        <hr>
                        {{$u->html()}}
                    </div>
                @endforeach
            @endif
            <br>
            <h4 class="mt-3">Controller Applications</h4>
            @if (count($applications) == 0)
                None yet!
            @else
                @foreach($applications as $a)
                    <div class="card p-3">
                        <h5>{{$a->user->fullName('FLC')}} ({{$a->user->rating_GRP}}, {{$a->user->division_name}})</h5>
                        <p>{{$a->start_availability_timestamp}} to {{$a->end_availability_timestamp}}</p>
                        <h6>Comments</h6>
                        <p>{{$a->comments}}</p>
                        <h6>Email</h6>
                        <p>{{$a->user->email}}</p>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<!--Delete event modal-->
<div class="modal fade" id="deleteEvent" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Are you sure?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Dismiss</button>
                <a href="{{route('events.admin.delete', $event->slug)}}" role="button" class="btn btn-danger">Delete Event</a>
            </div>
            </form>
        </div>
    </div>
</div>
<!--End delete event modal-->
@endsection
