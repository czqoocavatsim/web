@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')

    <div class="container py-4">
        <a href="{{route('dashboard.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Dashboard</a>
        <h1 class="blue-text font-weight-bold mt-2">Users</h1>
        <hr>
        @if (empty($users))
            <div class="alert alert-danger">No users found</div>
        @else
            <table id="dataTable" class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Rating</th>
                    <th scope="col">Permission</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    @if ($user->deleted == 1)
                    @else
                    <tr>
                        <th scope="row"><b>{{$user->id}}</b></th>
                        <td>{{$user->fullName('FL')}}</td>
                        <td>{{$user->rating_short}}</td>
                        <td>
                            {{$user->permissions()}}
                        </td>
                        <td>
                            <a class="blue-text" href="{{route('users.viewprofile', $user->id)}}"><i class="fa fa-eye"></i> View User</a>
                        </td>
                    </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        } );
    </script>
@stop
