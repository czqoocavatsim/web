@extends('layouts.master')
@section('content')
<div class="container py-4">
    <a href="{{route('dashboard.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Dashboard</a>
    <h1 class="blue-text font-weight-bold mt-2">Your Data</h1>
    <hr>
    <p>Under our <a href="{{route('privacy')}}">Privacy Policy</a>, you have the right to export and delete data from our service.</p>
    <br>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Export Data
                </div>
                <div class="card-body">
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
                                <input type="submit" value="Request Data" class="btn bg-czqo-blue-light">
                            </form>
                          </div>
                        </li>
                        <li class="active">
                            <a href="#!">
                              <span class="circle">2</span>
                              <span class="label">or export specific data</span>
                            </a>
                            <div class="step-content grey lighten-3">
                              <p>Please open a support ticket to the Web Team or email them to request specific pieces of data.</p>
                                <button onclick="location.href='{{route('tickets.index', ['create' => 'yes', 'department' => 'firchief', 'title' => 'Data request'])}}'" class="btn bg-czqo-blue-light">Support Ticket</button>
                            </div>
                          </li>
                      </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Delete Data</div>
                <div class="card-body">
                    <p>Please open a support ticket to the Web Team or email them to request deletion of your data.</p>
                    <button onclick="location.href='{{route('tickets.index', ['create' => 'yes', 'department' => 'firchief', 'title' => 'Data request'])}}'" class="btn bg-czqo-blue-light">Support Ticket</button>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">Email Preferences</div>
                <div class="card-body">
                    <p>To edit your email preferences, visit the <a href="{{route('me.preferences')}}">preferences page.</a></p>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">Questions and Concerns</div>
                <div class="card-body">
                    <p>If you have a question related to data management, please contact the Web Team.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
