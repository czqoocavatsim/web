@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    <div class="container" style="margin-top: 20px;">
        <div class="row">
            <div class="col">
                <h1>Position {{$position->callsign}}</h1>
            </div>
        </div>
        <table id="dataTable" class="table table-hover">
            <thead>
            <tr>
                <th scope="col">Variable</th>
                <th scope="col">Attribute</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">Callsign</th>
                    <td>{{$position->callsign}}</td>
                </tr>
                <tr>
                    <th scope="row">Type</th>
                    <td>{{$position->type}}</td>
                </tr>
                <tr>
                    <th scope="row">Staff Only</th>
                    <td>
                        @if ($position->staff_only == 0)
                            No
                        @else
                            Yes
                        @endif
                    </td>
                </tr>
        </table>
        <br/>
        <a href="#" data-toggle="modal" data-target="#editPos" role="button" class="btn btn-outline-primary">Edit Position</a>
        <a href="{{route('network.positions.delete', $position->id)}}" role="button" class="btn btn-outline-danger ml-4">Delete Position</a>
    </div>

    <div class="modal fade" id="editPos" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit {{$position->callsign}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{route('network.positions.add')}}" enctype="multipart/form-data" class="" id="">
                        @csrf
                        <div class="form-group">
                            <label>Callsign</label>
                            <input class="form-control" type="text" name="callsign" value="{{$position->callsign}}">
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <input class="form-control" type="text" name="type" value="{{$position->type}}">
                        </div>
                        <div class="form-group">
                            <input class="custom-checkbox" type="checkbox" name="staff_only">
                            <label>Staff only position (e.g. mentoring/instructing callsign)</label>
                        </div>
                        <br/>
                        <input type="submit" class="btn btn-success" value="Edit">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Dismiss</button>
                </div>
            </div>
        </div>
    </div>
@stop