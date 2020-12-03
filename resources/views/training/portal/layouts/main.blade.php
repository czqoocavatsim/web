@extends('layouts.master', ['solidNavBar' => false])

@section('content')
<div class="card card-image @yield('page-header-colour', 'blue') rounded-0" style="background-url:url(@yield('page-header-background')">
    <div class="text-white text-left pt-5 px-4">
        <div class="container">
            <div class="pt-5 pb-4">
                <h1 class="font-weight-bold" style="font-size: 2.5em;">@yield('page-header-title', 'Training Portal')</h1>
            </div>
        </div>
    </div>
</div>
<div class="container py-4" style="padding-bottom: 5rem !important;">
    <div class="row">
        <div class="col-md-3">
            <ul class="list-unstyled w-100">
                <a class="myczqo-tab no-click" data-myczqo-tab="none" href="{{route('my.index')}}">
                    <li class="w-100" style="border:none;">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-chevron-left fa-fw"></i>
                            <span style="font-size: 1.1em;">myCZQO</span>
                        </div>
                    </li>
                </a>
                <a class="myczqo-tab {{Request::is('training/portal') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('training.portal.index')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-tachometer-alt fa-fw"></i>
                            <span style="font-size: 1.1em;">Dashboard</span>
                        </div>
                    </li>
                </a>
                <a class="myczqo-tab {{Request::is('training/portal/help-policies') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('training.portal.help-policies')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="far fa-question-circle fa-fw"></i>
                            <span style="font-size: 1.1em;">Help & Policies</span>
                        </div>
                    </li>
                </a>
                <li class="w-100 my-3" style="border:none;">
                    <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                        <span style="font-size: 1em;" class="text-muted">APPLY</span>
                    </div>
                </li>
                @if($pendingApp = Auth::user()->pendingApplication())
                <a class="myczqo-tab {{Request::is(route('training.applications.show', $pendingApp->reference_id)) ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('training.applications.show', $pendingApp->reference_id)}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="far fa-clock fa-fw"></i>
                            <span style="font-size: 1.1em;">#{{$pendingApp->reference_id}}</span>
                        </div>
                    </li>
                </a>
                @endif
                @can('start applications')
                <a class="myczqo-tab {{Request::is('training/applications/apply') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('training.applications.apply')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 13px;" >👋</i>
                            <span style="font-size: 1.1em;">Apply to Gander</span>
                        </div>
                    </li>
                </a>
                @endcan
                <a class="myczqo-tab {{Request::is('training/applications') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('training.applications.showall')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-list fa-fw"></i>
                            <span style="font-size: 1.1em;">Your past applications</span>
                        </div>
                    </li>
                </a>
                @if($studentProfile = Auth::user()->studentProfile)
                <li class="w-100 my-3" style="border:none;">
                    <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                        <span style="font-size: 1em;" class="text-muted">Your Training</span>
                    </div>
                </li>
                @endif
            </ul>
        </div>
        <div class="col-md-9">
            @yield('portal-content')
        </div>
    </div>
</div>
@endsection
