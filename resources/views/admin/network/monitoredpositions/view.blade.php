@extends('layouts.master')
@section('content')
<div class="container py-4">
    <a href="{{route('network.monitoredpositions.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Monitored Positions</a>
    <h1 class="blue-text font-weight-bold mt-2">{{$position->identifier}}</h1>
    <hr>
    <div class="row">
        <div class="col-md-3">
            <h4 class="font-weight-bold blue-text">Actions</h4>
            <ul class="list-unstyled mt-3 mb-0" style="font-size: 1.05em;">
                <li class="mb-2">
                    <a href="#" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="text-body">Export position data</span></a>
                </li>
                @can('edit monitored positions')
                <li class="mb-2">
                    <a href="#" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="text-body">Edit position</span></a>
                </li>
                <li class="mb-2">
                    <a href="#" style="text-decoration:none;"><span class="red-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="text-body">Delete position</span></a>
                </li>
                @endcan
            </ul>
        </div>
        <div class="col-md-9">
            <h4 class="font-weight-bold blue-text">Details</h4>
            <table class="table table-borderless table-striped">
                <tbody>
                    <tr>
                        <td>Identifier</td>
                        <td>{{$position->identifier}}</td>
                    </tr>
                    <tr>
                        <td>Created at</td>
                        <td>{{$position->created_at}}</td>
                    </tr>
                    <tr>
                        <td>Last online</td>
                        <td>{{$position->lastOnline() ?? 'Never used'}}</td>
                    </tr>
                    <tr>
                        <td>Sessions</td>
                        <td>{{count($position->sessions)}} total sessions</td>
                    </tr>
                </tbody>
            </table>
            <h4 class="font-weight-bold blue-text">Sessions</h4>
            <table class="table dt">
                <thead>
                    <tr>
                        <th>CID</th>
                        <th>Session start</th>
                        <th>Session end</th>
                        <th>Duration</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($position->sessions as $session)
                    <tr>
                        <td>{{$session->cid}}</td>
                        <td>{{$session->session_start}}</td>
                        <td>{{$session->session_end}}</td>
                        <td>{{$session->duration}} hours</td>
                        <td>asd</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <script>
                    $('.table.dt').DataTable({ "order": [[ 1, "desc" ]]});
            </script>
        </div>
    </div>
</div>
@endsection
