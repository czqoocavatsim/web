@extends('layouts.primary')
@section('title', 'Manage your data - ')
@section('content')
<div class="container py-4">
    <a href="{{route('my.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> myCZQO</a>
    <h1 class="blue-text font-weight-bold mt-2">Manage your data</h1>
    <hr>
    <p class="my-2" style="font-size: 1.1em;">
        Gander Oceanic takes your privacy seriously. To request removal or alteration of your data, please contact us via the methods provided on this page. To learn more, read our <a href="{{route('privacy')}}">Privacy Policy.</a>
    </p>
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow-none grey lighten-5">
                <div class="card-body">
                    <h4 class="font-weight-bold blue-text">Account Information</h4>
                    <ul class="list-unstyled">
                        <li>
                            <span class="font-weight-bold">First Name (Display): </span> {{Auth::user()->fullName('F')}}
                        </li>
                        <li>
                            <span class="font-weight-bold">First Name (CERT): </span> {{Auth::user()->fname}}
                        </li>
                        <li>
                            <span class="font-weight-bold">Last Name: </span> {{Auth::user()->lname}}
                        </li>
                        <li>
                            <span class="font-weight-bold">CID: </span> {{Auth::user()->id}}
                        </li>
                        <li class="mt-3">
                            <span class="font-weight-bold">Email: </span> {{Auth::user()->email}}
                        </li>
                        <li>
                            <span class="text-muted">To change your email, go to myVATSIM</span>
                        </li>
                        <li class="mt-3">
                            <span class="font-weight-bold">Rating: </span> {{Auth::user()->rating_GRP}} ({{Auth::user()->rating_short}})
                        </li>
                        <li>
                            <span class="font-weight-bold">Region: </span> {{ Auth::user()->region_name }}
                        </li>
                        <li>
                            <span class="font-weight-bold">Division: </span> {{ Auth::user()->division_name }}
                        </li>
                        @if (Auth::user()->subdivision_name)
                        <li>
                            <span class="font-weight-bold">vACC/ARTCC: </span> {{ Auth::user()->subdivision_name }}
                        </li>
                        @endif
                        <li class="mt-3">
                            <span class="font-weight-bold">Role: </span> {{Auth::user()->highestRole()->name}}
                        </li>
                        @if(Auth::user()->staffProfile)
                        <li>
                            <span class="font-weight-bold">Staff Role: </span> {{Auth::user()->staffProfile->position}}
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="card shadow-none grey lighten-5 mt-4">
                <div class="card-body">
                    <h4 class="font-weight-bold blue-text">Delete Data</h4>
                    <p>Please email the IT Director if you would like to delete any data.</p>
                    <button class="btn btn-light">Support Ticket</button>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-none grey lighten-5">
                <div class="card-body">
                    <h4 class="font-weight-bold blue-text">Export Data</h4>
                    To export your data, you may either:
                    <ul class="mt-0 pt-0 pl-0 stepper stepper-vertical">
                        <li class="active">
                          <a href="#!">
                            <span class="circle">1</span>
                            <span class="label">Export all data</span>
                          </a>
                        <div class="step-content grey lighten-3">
                        <p>To export all data, please fill out the form below. We require your email address for verification purposes.</p>
                        <form action="{{route('me.data.export.all')}}" method="POST">
                            @csrf
                            <div class="md-form">
                                <input name="email" type="email" id="inputMDEx" class="form-control">
                                <label for="inputMDEx">Email address</label>
                            </div>
                            @if($errors->exportAll->any())
                            <div class="alert alert-danger">
                                <h4>Error</h4>
                                <ul class="pl-0 ml-0">
                                    @foreach ($errors->exportAll->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            @if (Session::has('exportAll'))
                            <div class="alert alert-success pb-0">
                                <p>{{Session::get('exportAll')}}</p>
                            </div>
                            @endif
                            <input type="submit" value="Request Data" class="btn btn-light">
                        </form>
                        </div>
                    </li>
                    <li class="active">
                        <a href="#!">
                            <span class="circle">2</span>
                            <span class="label">or export specific data</span>
                        </a>
                        <div class="step-content grey lighten-3">
                            <p>Please email the IT Director to request specific data.</p>
                        </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
