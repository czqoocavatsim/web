@extends('layouts.primary', ['adminNavBar' => true])
@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-3">
            <ul class="list-unstyled w-100">
                <li class="w-100 my-3" style="border:none;">
                    <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                        <span style="font-size: 1em;" class="text-muted">SETTINGS</span>
                    </div>
                </li>
                <a class="myczqo-tab {{Request::is('admin/settings') || Request::is('admin/settings') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('settings.index')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-home fa-fw"></i>
                            <span style="font-size: 1.1em;">Home</span>
                        </div>
                    </li>
                </a>
                <a class="myczqo-tab {{Request::is('admin/settings/site-information') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('settings.siteinformation')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-cog fa-fw"></i>
                            <span style="font-size: 1.1em;">Site Info</span>
                        </div>
                    </li>
                </a>
                <a class="myczqo-tab {{Request::is('admin/settings/emails') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('settings.emails')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-envelope fa-fw"></i>
                            <span style="font-size: 1.1em;">Site Emails</span>
                        </div>
                    </li>
                </a>
                <a class="myczqo-tab {{Request::is('admin/settings/rotation-images') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('settings.rotationimages')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-images fa-fw"></i>
                            <span style="font-size: 1.1em;">Rotation Images</span>
                        </div>
                    </li>
                </a>
                <a class="myczqo-tab {{Request::is('admin/settings/activity-log') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('settings.activitylog')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-history fa-fw"></i>
                            <span style="font-size: 1.1em;">Activity Log</span>
                        </div>
                    </li>
                </a>
                <a class="myczqo-tab {{Request::is('admin/settings/staff') || Request::is('admin/settings/staff/*') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('settings.staff')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-users fa-fw"></i>
                            <span style="font-size: 1.1em;">Staff</span>
                        </div>
                    </li>
                </a>
            </ul>
        </div>
        <div class="col-md-9">
            @yield('settings-content')
        </div>
    </div>
</div>
@endsection
