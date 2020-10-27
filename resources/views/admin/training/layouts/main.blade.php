@extends('layouts.master')
@section('content')
<script src="{{asset('js/instructing.js')}}"></script>
<div class="container py-4">
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
                <a class="myczqo-tab {{Request::is('admin/training') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('training.admin.dashboard')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-tachometer-alt fa-fw"></i>
                            <span style="font-size: 1.1em;">Dashboard</span>
                        </div>
                    </li>
                </a>
                <li class="w-100 my-3" style="border:none;">
                    <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                        <span style="font-size: 1em;" class="text-muted">ADMIN</span>
                    </div>
                </li>
                <a class="myczqo-tab {{Request::is('admin/training/roster') || Request::is('admin/training/roster/*') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('training.admin.roster')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-users fa-fw"></i>
                            <span style="font-size: 1.1em;">Controller Roster</span>
                        </div>
                    </li>
                </a>
                <a class="myczqo-tab {{Request::is('admin/training/solocertifications') || Request::is('admin/training/solocertifications/*') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('training.admin.solocertifications')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-certificate fa-fw"></i>
                            <span style="font-size: 1.1em;">Solo Certifications</span>
                        </div>
                    </li>
                </a>
                <a class="myczqo-tab {{Request::is('admin/training/applications')|| Request::is('admin/training/applications/*') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('training.admin.applications')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-clock fa-fw"></i>
                            <span style="font-size: 1.1em;">Applications</span>
                        </div>
                    </li>
                </a>
                <li class="w-100 my-3" style="border:none;">
                    <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                        <span style="font-size: 1em;" class="text-muted">INSTRUCTING</span>
                    </div>
                </li>{{--
                <a class="myczqo-tab {{Request::is('admin/training/instructing/calendar') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('training.admin.applications')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-clock fa-fw"></i>
                            <span style="font-size: 1.1em;">Calendar</span>
                        </div>
                    </li>
                </a> --}}
                <a class="myczqo-tab {{Request::is('admin/training/instructing/instructors') || Request::is('admin/training/instructing/instructors/*') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('training.admin.instructing.instructors')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-user-shield fa-fw"></i>
                            <span style="font-size: 1.1em;">Instructors</span>
                        </div>
                    </li>
                </a>
            </ul>
        </div>
        <div class="col-md-9">
            @yield('training-content')
        </div>
    </div>
</div>
@endsection
