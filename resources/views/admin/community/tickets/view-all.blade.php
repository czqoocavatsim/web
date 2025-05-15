@extends('admin.community.layouts.main')
@section('community-content')
    <div class="container py-4">

        <h3 class="fw-700 blue-text mb-0">Current Open Tickets</h3>
            @if($open_tickets->isEmpty())
                <div class="alert alert-success">There are currently no open tickets</div>
            @else
                <table id="dataTable" class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">Category</th>
                        <th scope="col">User</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($open_tickets as $ticket)
                        <tr>
                            <th scope="row">{{$ticket->type->name}}</th>
                            <td>{{$ticket->user->FullName('FLC')}}</td>
                            <td>
                                @if($ticket->status == 0)
                                    Pending
                                @elseif($ticket->status == 1)
                                    In Progress by {{$ticket->Assigneduser->FullName('FLC')}}
                                @endif
                            </td>
                            <td>
                                <a class="blue-text" href="{{route('community.tickets.view', $ticket->slug)}}"><i class="fa fa-eye"></i> View Ticket</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif

            <h3 class="fw-700 blue-text mb-0 mt-4">Closed Tickets</h3>
            @if($closed_tickets->isEmpty())
                <p>There are currently no closed tickets
            @else
                <table id="dataTable2" class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">Category</th>
                        <th scope="col">User</th>
                        <th scope="col">Completed By</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($closed_tickets as $ticket)
                        <tr>
                            <th scope="row">{{$ticket->type->name}}</th>
                            <td>{{$ticket->user->FullName('FLC')}}</td>
                            <td>{{$ticket->Assigneduser->FullName('FLC')}}</td>
                            <td>
                                <a class="blue-text" href="{{route('community.tickets.view', $ticket->id)}}"><i class="fa fa-eye"></i> View Ticket</a>
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

    $(document).ready(function() {
        $('#dataTable2').DataTable();
    } );
</script>
@endsection
