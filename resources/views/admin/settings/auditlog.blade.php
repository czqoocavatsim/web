@extends('layouts.master')
@section('content')
    <div class="container py-4">
        <a href="{{route('settings.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Settings</a>
        <h1 class="blue-text font-weight-bold mt-2">Audit Log</h1>
        <hr>
        <p>This log is strictly confidential.</p>
        @if (count($entries) < 1)
            <p>No logs</p>
        @else
            <table class="table dt table-hover">
                <thead>
                    <tr>
                        <th scope="col">Time</th>
                        <th scope="col">User</th>
                        <th scope="col">Affected User</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($entries as $entry)
                    <tr>
                        <th scope="row">{{$entry->created_at->toDayDateTimeString()}}</th>
                        <td>
                            {{$entry->user->fullName("FLC")}}
                        </td>
                        <td>
                            {{$entry->affectedUser->fullName("FLC")}}
                        <td>
                            {{$entry->action}}
                            &nbsp;
                            @if ($entry->private == 1)
                            (private)
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
        <br/>
    </div>
    <script>
        $(document).ready(function() {
            $('.table.dt').DataTable();
        } );
    </script>
@endsection
