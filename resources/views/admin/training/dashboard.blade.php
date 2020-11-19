@extends('admin.training.layouts.main')
@section('training-content')
<h1 class="font-weight-bold blue-text pb-2">Dashboard</h1>
<p class="lead"><span id="greeting">Hello</span>, {{Auth::user()->fullName('F')}}!</p>
<div class="row">
    <div class="col-md-6">
        @can('view applications')
        <div class="card p-3 grey lighten-5 shadow-none">
            <h4 class="font-weight-bold blue-text mb-3">{{count($applications)}} pending applications</h4>
            @if(count($applications) < 1)
                None pending! Well done.
            @else
                <div class="list-group">
                    @foreach ($applications as $a)
                        <a href="{{route('training.admin.applications.view', $a->reference_id)}}" class="list-group-item grey lighten-5 list-group-item-action">
                            <div class="d-flex flex-row w-100 justify-content-between align-items-center">
                                <div>
                                    <h5>{{$a->user->fullName('FLC')}}</h5>
                                    <p class="mb-0">Submitted {{$a->created_at->diffForHumans()}}</p>
                                </div>
                                <i style="font-size: 1.6em;" class="blue-text fas fa-chevron-right fa-fw"></i>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
        @endcan
    </div>
</div>
<script>
    $(document).ready(function () {
        var date = new Date();
        if (date.getHours() < 12) { $("#greeting").text("Good morning") }
        if (date.getHours() < 17) { $("#greeting").text("Good afternoon") }
        if (date.getHours() > 17) { $("#greeting").text("Good evening") }
    })
</script>
@endsection
