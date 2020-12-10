@extends('layouts.primary', ['solidNavBar' => false])

@section('navbarprim')

    @parent

@stop

@section('title', 'Solo Certifications - ')

@section('content')

<div class="card card-image blue rounded-0">
    <div class="text-white text-left pb-2 pt-5 px-4">
        <div class="container">
            <div class="py-5">
                <h1 class="font-weight-bold" style="font-size: 3em;">Solo Certification</h1>
            </div>
        </div>
    </div>
</div>
<div class="container py-4">
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
