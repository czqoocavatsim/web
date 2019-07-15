@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
<div class="container" style="margin-top: 20px">
    <h1>Controller Roster</h1><br/>
    <nav class="navbar navbar-light bg-light">
        <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
            <button data-toggle="modal" data-target="#addControllerModal" type="button" class="btn btn-outline-primary btn-sm">Add a controller</button>{{--
            <button data-toggle="modal" data-target="#startTicketModal" type="button" class="btn btn-outline-primary btn-sm ml-2">Check roster for activity</button>
            <button data-toggle="modal" data-target="#startTicketModal" type="button" class="btn btn-outline-primary btn-sm ml-2">Get activity report</button>--}}
        </div>
    </nav><br/>
    @if (empty($roster))
        <div class="alert alert-info">Oops, no controllers. lmao</div>
    @else
        <p>Returned {{count($roster)}} records</p>
        <table id="dataTable" class="table table-hover">
            <thead>
            <tr>
                <th scope="col">CID</th>
                <th scope="col">Name</th>
                <th scope="col">Rating</th>
                <th scope="col">Status</th>
                <th scope="col">Activity</th>
                <th scope="col">View</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($roster as $controller)
                <tr>
                    <th scope="row">{{$controller->cid}}</th>
                    <td>
                        {{$controller->user->fullName('FL')}}
                        @if ($controller->user_id == 2)
                            <i title="Not linked to a user account." class="fas fa-unlink"></i>
                        @endif
                    </td>
                    <td>
                        {{$controller->rating}}
                    </td>
                    @if ($controller->status == "certified")
                        <td class="bg-success text-white">
                            Certified
                        </td>
                    @elseif ($controller->status == "not_certified")
                        <td class="bg-danger text-white">
                            Not Certified
                        </td>
                    @elseif ($controller->status == "instructor")
                        <td class="bg-info text-white">
                            Instructor
                        </td>
                    @elseif ($controller->status == "training")
                        <td class="bg-warning text-dark">
                            Training
                        </td>
                    @else
                        <td>
                            {{$controller->status}}
                        </td>
                    @endif
                    @if ($controller->active)
                        <td class="bg-success text-white">Active</td>
                    @else
                        <td class="bg-danger text-white">Inactive</td>
                    @endif
                    <td>
                        <a href="{{url('/dashboard/roster/'.$controller->cid)}}"><i class="fa fa-eye"></i></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <script>
            $(document).ready(function() {
                $('#dataTable').DataTable( {
                    "order": [[ 0, "asc" ]]
                } );
            } );
        </script>
    @endif
</div>
<!--add a controller modal-->
<div class="modal fade" id="addControllerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add a controller</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="mb-2">
                    The CID entered will automatically check against users in the CZQO Core database, and if so, populate their data with CERT information. This will override information you input.
                </p>
                {!! Form::open(['route' => 'roster.addcontroller']) !!}
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">VATSIM CID</label>
                    {!! Form::text('cid', null, ['class' => 'form-control', 'placeholder' => 'e.g. 1300001', 'maxlength' => 7]) !!}
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Full Name</label>
                    {!! Form::text('full_name', null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Rating</label>
                    {!! Form::select('rating', ['C1' => 'Controller (C1)', 'C3' => 'C3 (Senior Controller)', 'I1' => 'Instructor (I1)', 'I3' => 'Instructor (I3)', 'SUP' => 'Supervisor (SUP)', 'ADM' => 'Administrator (ADM)'], ['placeholder' => 'Please choose one..'], ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Division (e.g. VATPAC)</label>
                    {!! Form::text('division', null, ['class' => 'form-control']) !!}
                    <small>Please ensure that name styling is maintained.</small>
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Status</label>
                    {!! Form::select('status', ['certified' => 'Certified', 'not_certified' => 'Not Certified', 'training' => 'Training', 'instructor' => 'Instructor'], ['placeholder' => 'Please choose one..'], ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Active</label>
                    {{ Form::checkbox('active', 'yes', true) }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                {!! Form::submit('Add Controller', ['class' => 'btn btn-primary']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@stop