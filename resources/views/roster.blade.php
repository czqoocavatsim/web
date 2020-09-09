@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('title', 'ATC Roster - ')
@section('description', "Gander Oceanic's Oceanic Controller Roster")

@section('content')
<div class="container" style="margin-top: 20px;">
        <h1 class="blue-text font-weight-bold">Controller Roster</h1>
        <hr>
        <p>Please note that the 'full name' field on this roster is dependant on the controller's name settings on the CZQO Core. As such, it is best to rely on the CID to determine whether they are on the roster.</p>
        <table id="rosterTable" class="table table-hover">
            <thead>
                <tr>
                    <th scope="col"><b>CID</b></th>
                    <th scope="col">Full Name</th>
                    <th scope="col">Rating</th>
                    <th scope="col">Division</th>
                    <th scope="col">Status</th>
                    <th scope="col">Certification</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($roster as $controller)
                <tr>
                    <th scope="row"><b>{{$controller->cid}}</b></th>
                    <td>
                        {{$controller->user->fullName('FL')}}
                    </td>
                    <td>
                        {{$controller->user->rating_short}}
                    </td>
                    <td>
                        {{$controller->user->division_name}} ({{$controller->user->division_code}})
                    </td>
                    @if ($controller->active)
                        <td class="bg-success text-white">Active</td>
                    @else
                        <td class="bg-danger text-white">Inactive</td>
                    @endif
                        <td>
                            {{$controller->certification}}
                        </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    <script>
        $(document).ready(function() {
            $('#rosterTable').DataTable( {
                "order": [[ 0, "asc" ]]
            } );
        } );
    </script>
</div>
@stop
