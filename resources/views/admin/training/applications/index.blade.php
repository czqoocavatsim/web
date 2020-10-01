@extends('admin.training.layouts.main')
@section('training-content')
<h1 class="font-weight-bold blue-text pb-2">Applications</h1>
<ul class="list-unstyled mt-2 mb-0">

@if(count($applications) > 0)
<h5>Pending Applications</h5>
    <div class="list-group rounded">
        @foreach($applications as $a)
        <a href="{{route('training.admin.applications.view', $a->reference_id)}}" class="list-group-item list-group-item-action p-3">
            <h5 class="font-weight-bold">
                #{{$a->reference_id}}
            </h5>
            <p class="m-0">
                {{$a->user->fullName('FLC')}}
                <br>
                Submitted <span title="{{$a->created_at}}">{{$a->created_at->diffForHumans()}}</span>
            </p>
        </a>
        @endforeach
    </div>
@else
No pending applications!
@endif
<ul class="list-unstyled mt-4 mb-0">
    <li class="mb-2">
        <a href="{{route('training.admin.applications.processed')}}" style="text-decoration:none;">
            <span class="blue-text">
                <i class="fas fa-chevron-right"></i>
            </span>
            &nbsp;
            <span class="black-text">View processed applications</span>
        </a>
    </li>
    <li class="mb-2">
        <a href="{{route('training.admin.applications.withdrawn')}}" style="text-decoration:none;">
            <span class="blue-text">
                <i class="fas fa-chevron-right"></i>
            </span>
            &nbsp;
            <span class="black-text">View withdrawn applications</span>
        </a>
    </li>
</ul>
<script>
    $("blockquote").addClass('blockquote');

    $(document).ready(function () {
        $('.table.dt').DataTable();
    })

    $.urlParam = function(name){
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        return results[1] || 0;
    }
/*
    if ($.urlParam('addRosterMemberModal') == '1') {
        $("#addRosterMemberModal").modal();
    } */
</script>

@endsection
