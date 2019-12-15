@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    <div class="container" style="margin-top: 20px;">
        <a href="{{url('dashboard/roster/')}}"><i class="fa fa-left-chevron"></i>Back To Roster</a>
        <h2>View Controller {{ $controller->cid }}</h2>
        <h5>{{ $controller->user->fullName('FL') }}</h5>
        @if ($controller->user_id == 2)
            <div class="alert alert-info">
                <h4 class="alert-heading">Controller not linked to a user</h4>
                <p>
                    This controller has not linked their CZQO Core user account to their roster status, therefore their rating and division data will not automatically update.
                </p>
            </div>
        @endif
        <br/>
        {!! Form::open(['route' => ['roster.editcontroller', $controller->cid]]) !!}
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Attribute</th>
                <th scope="col">Value</th>
            </tr>
            </thead>
            <tbody>
            @if ($controller->user_id != 2)
                <tr>
                    <th scope="row">Rating</th>
                    <td>{{ $controller->rating }}</td>
                </tr>
                <tr>
                    <th scope="row">Division</th>
                    <td>{{$controller->division}}</td>
                </tr>
            @else
                <tr>
                    <th scope="row">Rating</th>
                    <td>
                        {!! Form::select('rating', ['C1' => 'Controller (C1)', 'C3' => 'C3 (Senior Controller)', 'I1' => 'Instructor (I1)', 'I3' => 'Instructor (I3)', 'SUP' => 'Supervisor (SUP)', 'ADM' => 'Administrator (ADM)'], $controller->rating, ['class' => 'form-control']) !!}
                    </td>
                </tr>
                <tr>
                    <th scope="row">Division</th>
                    <td>
                        {!! Form::text('division', $controller->division, ['class' => 'form-control']) !!}
                    </td>
                </tr>
            @endif
            <tr>
                <th scope="row">Status</th>
                <td>
                    {!! Form::select('status', ['certified' => 'Certified', 'not_certified' => 'Not Certified', 'training' => 'AtcTraining', 'instructor' => 'Instructor'], $controller->status, ['class' => 'form-control']) !!}
                </td>
            </tr>
            <tr>
                <th scope="row">Active</th>
                <td>
                    @if ($controller->active == 1)
                        {{ Form::checkbox('active', 'yes', true) }}
                    @else
                        {{ Form::checkbox('active', 'no', true) }}
                    @endif
                </td>
            </tr>
            </tbody>
        </table>
        <br/>
        {{ Form::submit('Update', ['class' => 'btn btn-success']) }}

        {!! Form::close() !!}
        <br/>
        <button data-toggle="modal" data-target="#deleteModal" class="btn btn-danger">Delete</button>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Delete Controller {{ $controller->cid }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <h2>Are you sure?</h2>
                        </div>
                        <div class="col">
                            <img src="https://media1.tenor.com/images/9ed3b339bbe196589360e93c8ebf90f0/tenor.gif?itemid=9148667">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="window.location.href = '/dashboard/roster/{{ $controller->cid }}/delete'" class="btn btn-outline-danger" data-dismiss="modal">Delete</button>
                    <button type="button" class="btn btn-success" >Exit</button>
                </div>
            </div>
        </div>
    </div>
@stop
