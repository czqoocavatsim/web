@extends('layouts.master', ['solidNavBar' => false])

@section('content')
<div class="card card-image @yield('page-header-colour', 'blue') rounded-0" style="background-url:url(@yield('page-header-background')">
    <div class="text-white text-left pb-2 pt-5 px-4">
        <div class="container">
            <div class="py-5">
                <h1 class="font-weight-bold" style="font-size: 3em;">@yield('page-header-title', 'Training Portal')</h1>
            </div>
        </div>
    </div>
</div>
<div class="container py-4" style="padding-bottom: 5rem !important;">
    <div class="row">
        <div class="col-md-3">
            <ul class="list-unstyled w-100">
                <a class="myczqo-tab {{Request::is('admin/training') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('training.admin.dashboard')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-tachometer-alt fa-fw"></i>
                            <span style="font-size: 1.1em;">Training Dashboard</span>
                        </div>
                    </li>
                </a>
            </ul>
        </div>
        <div class="col-md-9">
            @yield('portal-content')
        </div>
    </div>
</div>
@endsection
