@extends('admin.training.layouts.main')
@section('training-content')
<a href="{{route('training.admin.applications')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Applications</a>
<h2 class="blue-text mt-2 pb-2">Withdrawn Applications</h2>
<table id="dataTable" class="table table-hover">
    <thead>
    <tr>
        <th scope="col">Reference #</th>
        <th scope="col">Name</th>
        <th scope="col">Last Updated</th>
        <th scope="col">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($applications as $a)
        <tr>
            <th scope="row"><b>#{{$a->reference_id}}</b></th>
            <td>{{$a->user->fullName('FL')}}</td>
            <td data-order="{{$a->updated_at}}">{{$a->updated_at->toDayDateTimeString()}}</td>
            <td>
                <a class="blue-text" href="{{route('training.admin.applications.view', $a->reference_id)}}"><i class="fa fa-eye"></i> View</a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    } );
</script>
@endsection
