@extends('layouts.primary', ['adminNavBar' => true])
@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-3">
            <ul class="list-unstyled w-100">
                <li class="w-100 my-3" style="border:none;">
                    <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                        <span style="font-size: 1em;" class="text-muted">COMMUNITY</span>
                    </div>
                </li>
                @can('view users')
                <a class="myczqo-tab {{Request::is('admin/community/users') || Request::is('admin/community/users/*') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('community.users.index')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-users fa-fw"></i>
                            <span style="font-size: 1.1em;">Users</span>
                        </div>
                    </li>
                </a>
                @endcan
            </ul>
        </div>
        <div class="col-md-9">
            @yield('community-content')
        </div>
    </div>
</div>
@endsection
