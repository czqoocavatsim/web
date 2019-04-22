@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    <div class="container" style="margin-top: 20px;">
        <div class="row">
            <div class="col">
                <h1>Network Positions</h1>
            </div>
            <div class="col-sm-2">
                <h4>
                    <a href="#" data-target="#addPos" data-toggle="modal" class="btn btn-primary" role="button">Add Position</a>
                </h4>
            </div>
        </div>
        <table id="dataTable" class="table table-hover">
            <thead>
            <tr>
                <th scope="col">Callsign</th>
                <th scope="col">Type</th>
                <th scope="col">Staff Only</th>
                <th scope="col">Edit</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($positions as $position)
                <tr>
                    <th scope="row">{{$position->callsign}}</th>
                    <td>{{substr($position->type, 0, 8)}}</td>
                    <td>
                        @if ($position->staff_only == 0)
                            No
                        @else
                            Yes
                        @endif
                    </td>
                    <td>
                        <a href="{{route('network.positions.view', $position->id)}}">Edit</a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="modal fade" id="addPos" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add network position</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{route('network.positions.add')}}" enctype="multipart/form-data" class="" id="">
                        @csrf
                        <div class="form-group">
                            <label>Callsign</label>
                            <input class="form-control" type="text" name="callsign" placeholder="CZQX_N_FSS">
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <input class="form-control" type="text" name="type" placeholder="Standard">
                        </div>
                        <div class="form-group">
                            <input class="custom-checkbox" type="checkbox" name="staff_only">
                            <label>Staff only position (e.g. mentoring/instructing callsign)</label>
                        </div>
                        <br/>
                        <input type="submit" class="btn btn-success" value="Add">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Dismiss</button>
                </div>
            </div>
        </div>
    </div>
@stop