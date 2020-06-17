@extends('layouts.master')
@section('content')
<div class="container py-4">
    <a href="{{route('network.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Network</a>
    <h1 class="blue-text font-weight-bold mt-2">Monitored Positions</h1>
    <hr>
    <div class="row">
        <div class="col-md-3">
            <h4 class="font-weight-bold blue-text">Actions</h4>
            <ul class="list-unstyled mt-3 mb-0" style="font-size: 1.05em;">
                @can('edit monitored positions')
                <li class="mb-2">
                    <a href="#" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="text-body">Create monitored positions</span></a>
                </li>
                @endcan
            </ul>
        </div>
        <div class="col-md-9">
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
                            <td title="{{$p->lastOnline()}}">{{$p->lastOnline()->diffForHumans()}}</td>
                            <td><a class="blue-text" href="{{route('network.monitoredpositions.view', strtolower($p->identifier))}}"><i class="fa fa-eye"></i> View Position</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
