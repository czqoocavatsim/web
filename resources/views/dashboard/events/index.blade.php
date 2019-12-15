@extends('layouts.master')
@section('content')
<div class="container py-4">
    <h2>Events</h2>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <a href="#" class="mb-3 btn btn-block btn-md waves-effect btn-primary">Create Event</a>
            <table class="table dt table-hover table-bordered">
                <thead>
                    <th>Title</th>
                    <th>Date</th>
                </thead>
                <tbody>
                    @foreach ($events as $e)
                    <td>
                        <a href="{{route('events.admin.view', $e->slug)}}" class="blue-text">{{$e->name}}</a>
                    </td>
                    <td>
                        {{$e->start_timestamp_pretty()}}
                    </td>
                    @endforeach
                </tbody>
            </table>
            <script>
                $(document).ready(function() {
                    $('.table.dt').DataTable();
                } );
            </script>
        </div>
        <div class="col-md-6">

        </div>
    </div>
</div>
@endsection
