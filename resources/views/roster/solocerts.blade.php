@extends('layouts.primary')

@section('navbarprim')

    @parent

@stop

@section('title', 'Solo Certifications - ')

@section('content')
<div class="container" style="margin-top: 20px;">
        <h1 class="blue-text font-weight-bold">Solo Certifications</h1>
        <hr>
        <p>Please note that the 'full name' field on this table is dependent on the controller's individual name settings on the CZQO Core system.</p>
        <table id="rosterTable" class="table table-hover">
            <thead>
                <tr>
                    <th scope="col"><b>CID</b></th>
                    <th scope="col">Full Name</th>
                    <th scope="col">Expires</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($certs as $controller)
                <tr>
                    <th scope="row"><b>{{$controller->rosterMember->cid}}</b></th>
                    <td>
                        {{$controller->rosterMember->user->fullName('FL')}}
                    </td>
                    <td>
                        {{$controller->expires->toDateString()}}
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
