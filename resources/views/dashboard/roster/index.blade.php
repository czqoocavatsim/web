@extends('layouts.master')
@section('content')
<div class="container" style="margin-top: 20px;">
    <a href="{{route('dashboard.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Dashboard</a>
    <h1 class="blue-text font-weight-bold mt-2">Controller Roster</h1>
    <hr>
    <div class="row">
        <div class="col-md-3">
            <h4 class="font-weight-bold blue-text">Actions</h4>
            <ul class="list-unstyled mt-2 mb-0">
                <li class="mb-2">
                    <a href="" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Add controller to roster</span></a>
                </li>
                <li class="mb-2">
                    <a href="" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Export roster</span></a>
                </li>
            </ul>
        </div>
        <div class="col-md-9">
            <table class="table dt table-hover table-bordered">
                <thead>
                    <th>CID</th>
                    <th>Name</th>
                    <th>Status</th>
                </thead>
                <tbody>
                    @foreach ($roster as $r)
                        <tr>
                            <th scope="row">{{$r->cid}}</th>
                            <td>
                                {{$r->user->fullName('FL')}}
                                @if ($r->user_id == 2)
                                    <i title="Not linked to a user account." class="fas fa-unlink"></i>
                                @endif
                            </td>
                            @if ($r->status == "certified")
                                <td class="bg-success text-white">
                                    Certified
                                </td>
                            @elseif ($r->status == "not_certified")
                                <td class="bg-danger text-white">
                                    Not Certified
                                </td>
                            @elseif ($r->status == "instructor")
                                <td class="bg-info text-white">
                                    Instructor
                                </td>
                            @elseif ($r->status == "training")
                                <td class="bg-warning text-dark">
                                    Training
                                </td>
                            @else
                                <td>
                                    {{$r->status}}
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <script>
                $(document).ready(function () {
                    $('.table.dt').DataTable();
                })
            </script>
        </div>
    </div>
</div>
@endsection
