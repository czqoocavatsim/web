@extends('layouts.primary', ['solidNavBar' => false])


@section('title', 'Controller Roster - ')
@section('description', "Gander Oceanic's Oceanic Controller Roster")

@section('content')

<div class="jarallax card card-image blue rounded-0"  data-jarallax data-speed="1">
    <img class="jarallax-img" src="{{asset('assets/resources/media/img/website/euroscope_client.png')}}" alt="">
    <div class="text-white text-left rgba-stylish-strong py-3 pt-5 px-4">
        <div class="container">
            <div class="py-5">
                <h1 class="font-weight-bold">Controller Roster</h1>
                <h4 class="font-weight-bold">List of Controllers from Gander, Shanwick and New York Oceanic Control Areas (OCA).</h4>
            </div>
        </div>
    </div>
</div>
<div class="container py-4">
        <p class="text-muted">Please note that the 'full name' field on this roster is dependent on the controller's name settings on the CZQO Core system.<br></p>
        <p class="text-muted">This Roster shows the combined roster of Gander, Shanwick and New York Oceanic controllers.
            <br><span class="badge bg-danger">EGGX</span> = Certified by VATSIM UK
            <br><span class="badge bg-secondary">KZNY</span> = Certified by New York ARTCC
            <br><span class="badge bg-primary">CZQO</span> = Certified by Gander Oceanic</p>
        <p class="text-muted">Controllers with active status are endorsed to open & control either CZQO_CTR, EGGX_CTR, NY_FSS and/or NAT_FSS positions.<br></p>
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
                    <th scope="row"><b>{{$controller->user->id}}</b>

                    </th>
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
                        @if($controller->origin == "eggx")<span class="badge bg-danger">EGGX</span>
                        @elseif($controller->origin == "zny")<span class="badge bg-secondary">KZNY</span>
                        @else<span class="badge bg-primary">CZQO</span>@endif
                    </td>
                    @elseif ($controller->certification == "not_certified")
                        <td class="bg-danger text-white">
                            Not Certified
                        </td>
                    @elseif ($controller->certification == "training")
                        <td class="bg-warning text-dark">
                            In Training
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
