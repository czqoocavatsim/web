@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')

    <div class="container" style="margin-top: 20px;">
        <h2>View Users</h2>
        @if (empty($users))
            <div class="alert alert-info">Oops, no users. lmao</div>
        @else
            <p>Returned {{count($users)}} records</p>
            <table id="dataTable" class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Rating</th>
                    <th scope="col">Permission</th>
                    <th scope="col">View</th>
                    <th scope="col">Edit</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    @if ($user->deleted == 1)
                    @else
                    <tr>
                        <th scope="row">{{$user->id}}</th>
                        <td>{{$user->fname}} {{$user->lname}}</td>
                        <td>{{$user->rating}}</td>
                        <td>
                            @if ($user->permissions == 0)
                                0 - Guest
                            @elseif ($user->permissions == 1)
                                1 - Controller
                            @elseif ($user->permissions == 2)
                                2 - Instructor/Mentor/Developer
                            @elseif ($user->permissions == 3)
                                3 - Director (Non Executive)
                            @elseif ($user->permissions == 4)
                                4 - Director (Executive)
                            @else
                                Not Found
                            @endif
                        </td>
                        <td>
                            <a href="{{url('/dashboard/users/'.$user->id)}}"><i class="fa fa-eye"></i></a>
                        </td>
                        <td>
                            <a href="{{url('/dashboard/users/'.$user->id.'/edit')}}"><i class="fa fa-edit"></i></a>
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
            $('#dataTable').DataTable( {
                "order": [[ 3, "desc" ]]
            } );
        } );
    </script>
@stop