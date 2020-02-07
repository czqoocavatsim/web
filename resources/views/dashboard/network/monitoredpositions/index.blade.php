@extends('layouts.master')
@section('content')
<div class="container py-4">
    <a href="{{route('network.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Network</a>
    <h1 class="blue-text font-weight-bold mt-2">Monitored Positions</h1>
    <hr>
    <div class="row">
        <div class="col-md-8">
            <table class="table">
                <thead>
                    <th>Position</th>
                    <th>Last online</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach ($positions as $p)
                        <tr>
                            <td>{{$p->identifier}}</td>
                            <td>{{$p->lastOnlinePretty()}}</td>
                            <td><a class="blue-text" href="{{route('network.monitoredpositions.view', strtolower($p->identifier))}}">View</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <h4>Actions</h4>
            @if($errors->createMonitoredPosition->any())
            <div class="alert alert-danger">
                <h4>Error creating monitored position</h4>
                <ul class="pl-0 ml-0 list-unstyled">
                    @foreach ($errors->createMonitoredPosition->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <a href="#" data-target="#createPositionModal" data-toggle="modal" class="btn bg-czqo-blue-light btn-block">Create Position</a>
            <a href="#" class="mt-2 btn bg-czqo-blue-light btn-block">Download Positions JSON</a>
        </div>
    </div>
</div>
<!--Create position modal-->
<div class="modal fade" id="createPositionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create a position</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['route' => 'network.monitoredpositions.create']) !!}
            <div class="modal-body">
                <p>ActivityBot will monitor positions for activity and record sessions. You can specify a prefix or complete callsign.</p>
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Identifier</label>
                    {!! Form::text('identifier', null, ['class' => 'form-control', 'placeholder' => 'CZQX_']) !!}
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Staff Only</label>
                    {{ Form::checkbox('staffOnly', 'no', false) }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<!--End create position modal-->
<script>
$('.table').dataTable();
</script>
@endsection
