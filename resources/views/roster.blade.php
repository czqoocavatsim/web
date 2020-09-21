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
        <p>Please note that the 'full name' field on this roster is dependent on the controller's individual name settings on the CZQO Core system.</p>
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
                        @if ($controller->activeSoloCertification())
                        <i title="Solo certification active - expires {{$controller->activeSoloCertification()->expires->toDateString()}}" class="fas fa-certificate"></i>
                    @endif
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
                    @if ($controller->certification == "certified")
                    <td class="bg-success text-white">
                        Certified
                    </td>
                    @elseif ($controller->certification == "not_certified")
                        <td class="bg-danger text-white">
                            Not Certified
                        </td>
                    @elseif ($controller->certification == "training")
                        <td class="bg-warning text-dark">
                            Training
                        </td>
                    @else
                        <td>
                            {{$controller->certification}}
                        </td>
                    @endif
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
