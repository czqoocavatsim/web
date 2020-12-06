@extends('admin.community.layouts.main')
@section('community-content')
    <div class="container py-4">
        <h1 class="blue-text font-weight-bold mt-2">Users</h1>
        <h4 class="mb-3">There are <span class="font-weight-bold blue-text">{{$userCount}}</span> users</h4>
        @if (empty($users))
            <div class="alert alert-danger">No users found</div>
        @else
            <table id="dataTable" class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Rating</th>
                    <th scope="col">Role</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <th scope="row"><b>{{$user->id}}</b></th>
                        <td>{{$user->fullName('FL')}}</td>
                        <td>{{$user->rating_short ?? 'N/A'}}</td>
                        <td>
                            {{$user->highestRole()->name}}
                        </td>
                        <td>
                            <a class="blue-text" href="{{route('community.users.view', $user->id)}}"><i class="fa fa-eye"></i> View User</a>
                        </td>
                    </tr>
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
</div>
@endsection
