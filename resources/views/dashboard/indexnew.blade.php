@extends('layouts.master')
@section('content')
<div class="container-fluid pt-4 pb-5 winter-neva-gradient color-block">
    <div class="container">
        <h1 class="font-weight-bold white-text mb-4" style="font-size: 3.2rem;">Hi, {{Auth::user()->fullName('F')}}!</h1>
        <h5 class="white-text">QUICK ACTIONS</h5>
        <div class="card-columns">
            <div class="card shadow-none p-3">
                <h4 class="m-0 p-0"><a href="#" class="blue-text"><i class="fas fa-chart-line"></i> View Your Activity</a></h4>
            </div>
            <div class="card shadow-none p-3">
                <h4 class="m-0 p-0"><a href="#" class="blue-text"><i class="fas fa-envelope"></i> Start A Ticket</a></h4>
            </div>
            <div class="card shadow-none p-3">
                <h4 class="m-0 p-0"><a href="#" class="blue-text"><i class="fab fa-discord"></i> Join Our Discord</a></h4>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-md-6">
                <div class="card shadow-none p-3">
                    <div class="d-flex flex-row justify-content-left align-items-middle">
                        <h3 class="font-weight-bold blue-text"><img src="{{Auth::user()->avatar()}}" style="height: 50px; width:50px;margin-right: 15px; margin-bottom: 3px; border-radius: 50%;"></h3>
                        <div>
                            <h3 class="font-weight-bold blue-text mb-1">{{Auth::user()->fullName('FL')}}</h3>
                            <h5 class="text-muted">{{Auth::user()->permissions()}}</h5>
                        </div>
                    </div>
                    <ul class="list-unstyled mt-2 ml-0 pl-0 dashboard-card-actions">
                        <span>View your profile &nbsp;<i class="fas fa-chevron-right"></i></span>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
