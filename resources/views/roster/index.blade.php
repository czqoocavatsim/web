@extends('layouts.master', ['solidNavBar' => false])


@section('title', 'Controller Roster - ')
@section('description', "Gander Oceanic's Oceanic Controller Roster")

@section('content')

<div class="card card-image blue rounded-0">
    <div class="text-white text-left pb-2 pt-5 px-4">
        <div class="container">
            <div class="py-5">
                <h1 class="font-weight-bold" style="font-size: 3em;">Controller Roster</h1>
            </div>
        </div>
    </div>
</div>
<div class="container py-4">
        <p class="text-muted">Please note that the 'full name' field on this roster is dependent on the controller's name settings on the CZQO Core system.<br><i class="fas fa-certificate"></i> = Solo Certification</p>
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
            $.fn.dataTable.enum(['C1', 'C3', 'I1', 'I3', 'SUP', 'ADM'])
            $('#rosterTable').DataTable( {
                "order": [[ 0, "asc" ]]
            } );
        } );
    </script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.21/sorting/enum.js"></script>
</div>
@endsection
