@extends('layouts.master')
@section('content')
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
                <a class="myczqo-tab {{Request::is('admin/settings') || Request::is('admin/settings') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('settings.index')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-home fa-fw"></i>
                            <span style="font-size: 1.1em;">Home</span>
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
