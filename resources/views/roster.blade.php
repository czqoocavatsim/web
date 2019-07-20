@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
<div class="container" style="margin-top: 20px;">
        <h2>Controller Roster</h2>
        <br>
        <table id="rosterTable" class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">CID</th>
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
                    <th scope="row">{{$controller->cid}}</th>
                    <td>
                        {{$controller->user->fullName('FL')}}
                    </td>
                    <td>
                        {{$controller->rating}}
                    </td>
                    <td>
                        {{$controller->division}}
                    </td>
                    @if ($controller->active)
                        <td class="bg-success text-white">Active</td>
                    @else
                        <td class="bg-danger text-white">Inactive</td>
                    @endif
                    @if ($controller->status == "certified")
                        <td class="bg-success text-white">
                            Certified
                        </td>
                    @elseif ($controller->status == "not_certified")
                        <td class="bg-danger text-white">
                            Not Certified
                        </td>
                    @elseif ($controller->status == "instructor")
                        <td class="bg-info text-white">
                            Instructor
                        </td>
                    @elseif ($controller->status == "training")
                        <td class="bg-warning text-dark">
                            Training
                        </td>
                    @else
                        <td>
                            {{$controller->status}}
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