@extends('layouts.master')
@section('content')
<div class="container py-4">
    <a href="{{route('dashboard.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Dashboard</a>
    <h1 class="font-weight-bold blue-text">Publications</h1>
    <hr>
    <div class="row">
        <div class="col-md-3">
            <h4 class="font-weight-bold blue-text">Actions</h4>
            <ul class="list-unstyled mt-3 mb-0" style="font-size: 1.05em;">
                <li class="mb-2">
                    <a href="{{route('publications.policies.create')}}" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="text-body">Upload policy</span></a>
                </li>
                <li class="mb-2">
                    <a href="" data-toggle="modal" data-target="#createUpdate" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="text-body">Upload meeting minutes</span></a>
                </li>
            </ul>
        </div>
        <div class="col-md-9">
            <h4 class="font-weight-bold blue-text">Policies</h4>
            <table class="table dt table-hover table-bordered">
                <thead>
                    <th>Title</th>
                    <th>Uploaded at</th>
                    <th>Uploaded by</th>
                </thead>
                <tbody>
                    @foreach ($policies as $p)
                    <tr>
                        <td>
                            <a class="blue-text" href="{{route('publications.policies.view', $p->id)}}">{{$p->title}}</a>
                        </td>
                        <td>{{$p->created_at->toDayDateTimeString()}}</td>
                        <td>{{$p->user->fullName('FLC')}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.table.dt').DataTable();
    } );
</script>
@endsection
