@extends('layouts.master')
@section('content')
    <div class="container py-4">
        <a href="{{route('settings.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Settings</a>
        <h1 class="blue-text font-weight-bold mt-2">Activity Log</h1>
        <hr>
        <p>This log is strictly confidential.</p>
        @if (count($entries) < 1)
            <p>No logs</p>
        @else
            <table class="table dt table-hover">
                <thead>
                    <tr>
                        <th scope="col">Time</th>
                        <th scope="col">Subject</th>
                        <th scope="col">Causer</th>
                        <th scope="col">Description</th>
                        <th scope="col">Changes</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($entries as $entry)
                    <tr>
                        <th data-order="{{$entry->created_at}}" scope="row">{{$entry->created_at->toDayDateTimeString()}}</th>
                        <td>
                            {{$entry->subject->id ?? ''}} ({{substr($entry->subject_type, strrpos($entry->subject_type, "\\") + 1)}})
                        </td>
                        <td>
                            {{$entry->causer->id ?? ''}} ({{substr($entry->causer_type, strrpos($entry->causer_type, "\\") + 1)}})
                        <td>
                            {{$entry->description}}
                        </td>
                        <td>
                            @if($entry->changes)
                            {{$entry->changes}}
                            @else
                            N/A
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
            $('.table.dt').DataTable({ "order": [[ 0, "desc" ]]});
        } );
    </script>
@endsection
