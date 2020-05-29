@extends('layouts.master')
@section('content')
<div class="container py-4">
    <a href="{{route('dashboard.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Dashboard</a>
    <h1 class="font-weight-bold blue-text">Events</h1>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <a href="{{route('events.admin.create')}}" class="mb-3 btn btn-block btn-md waves-effect btn-primary">Create Event</a>
            <table class="table dt table-hover table-bordered">
                <thead>
                    <th>Title</th>
                    <th>Date</th>
                </thead>
                <tbody>
                    @foreach ($events as $e)
                    <tr>
                        <td>
                            <a href="{{route('events.admin.view', $e->slug)}}" class="blue-text">{{$e->name}}</a>
                        </td>
                        <td data-order="{{$e->start_timestamp}}">
                            {{$e->start_timestamp_pretty()}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <script>
                $(document).ready(function() {
                    $('.table.dt').DataTable({ "order": [[ 1, "desc" ]]});
                } );
            </script>
        </div>
        <div class="col-md-6">

        </div>
    </div>
</div>
@endsection
