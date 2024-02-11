@extends('layouts.primary', ['adminNavBar' => true])
@section('content')
<script src="{{asset('js/instructing.js')}}"></script>
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
                <a class="myczqo-tab no-click" data-myczqo-tab="none" href="https://ganderoceanic.ca/training-system-support">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-question fa-fw"></i>
                            <span style="font-size: 1.1em;">Support/WIP functions</span>
                        </div>
                    </li>
                </a>
                @if(Auth::user()->instructorProfile && Auth::user()->instructorProfile->current)
                <a class="myczqo-tab {{Request::is('admin/training/instructing/your-students') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('training.admin.instructing.your-students')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-user fa-fw"></i>
                            <span style="font-size: 1.1em;">Your Students</span>
                        </div>
                    </li>
                </a>
                {{-- <a class="myczqo-tab {{Request::is('admin/training/instructing/your-upcoming-sessions') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('training.admin.instructing.your-upcoming-sessions')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-calendar fa-fw"></i>
                            <span style="font-size: 1.1em;">Your Upcoming Sessions</span>
                        </div>
                    </li>
                </a> --}}
                @endif
                <li class="w-100 my-3" style="border:none;">
                    <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                        <span style="font-size: 1em;" class="text-muted">INSTRUCTING</span>
                    </div>
                </li>
                <a class="myczqo-tab {{Request::is('admin/training/instructing/board') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('training.admin.instructing.board')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-stream fa-fw"></i>
                            <span style="font-size: 1.1em;">Overview</span>
                        </div>
                    </li>
                </a>
                <a class="myczqo-tab {{Request::is('admin/training/instructing/calendar') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('training.admin.instructing.calendar')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-clock fa-fw"></i>
                            <span style="font-size: 1.1em;">Calendar</span>
                        </div>
                    </li>
                </a>
                <a class="myczqo-tab {{Request::is('admin/training/instructing/instructors') || Request::is('admin/training/instructing/instructors/*') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('training.admin.instructing.instructors')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-user-shield fa-fw"></i>
                            <span style="font-size: 1.1em;">Instructors</span>
                        </div>
                    </li>
                </a>
                <a class="myczqo-tab {{Request::is('admin/training/instructing/students') || Request::is('admin/training/instructing/students/*') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('training.admin.instructing.students')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-graduation-cap fa-fw"></i>
                            <span style="font-size: 1.1em;">Students</span>
                        </div>
                    </li>
                </a>
                <a class="myczqo-tab {{Request::is('admin/training/instructing/training-sessions') || Request::is('admin/training/instructing/training-sessions/*') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('training.admin.instructing.training-sessions')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-user-friends fa-fw"></i>
                            <span style="font-size: 1.1em;">Training Sessions</span>
                        </div>
                    </li>
                </a>
                <a class="myczqo-tab {{Request::is('admin/training/instructing/ots-sessions') || Request::is('admin/training/instructing/ots-sessions/*') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('training.admin.instructing.ots-sessions')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-user-check fa-fw"></i>
                            <span style="font-size: 1.1em;">OTS Sessions</span>
                        </div>
                    </li>
                </a>
                @can('edit roster')
                <li class="w-100 my-3" style="border:none;">
                    <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                        <span style="font-size: 1em;" class="text-muted">ROSTER</span>
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
                <a class="myczqo-tab {{Request::is('admin/training/acknowledgement*') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('training.admin.acknowledgements')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-address-book fa-fw"></i>
                            <span style="font-size: 1.1em;">Controller Acknowledgements</span>
                        </div>
                    </li>
                </a>
                @endcan
                @can('view applications')
                <li class="w-100 my-3" style="border:none;">
                    <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                        <span style="font-size: 1em;" class="text-muted">APPLICATIONS</span>
                    </div>
                </li>
                <a class="myczqo-tab {{Request::is('admin/training/applications')|| Request::is('admin/training/applications/*') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('training.admin.applications')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-clock fa-fw"></i>
                            <span style="font-size: 1.1em;">Applications</span>
                        </div>
                    </li>
                </a>
                @endcan
            </ul>
        </div>
        <div class="col-md-9 pl-5 pt-3  ">
            @yield('training-content')
        </div>
    </div>
</div>

@endsection
