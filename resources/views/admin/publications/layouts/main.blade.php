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
                <li class="w-100 my-3" style="border:none;">
                    <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                        <span style="font-size: 1em;" class="text-muted">PUBLICATIONS</span>
                    </div>
                </li>
                @can('edit policies')
                <a class="myczqo-tab {{Request::is('admin/publications/policies') || Request::is('admin/publications/policies/*') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('publications.policies')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-file-alt fa-fw"></i>
                            <span style="font-size: 1.1em;">Policies</span>
                        </div>
                    </li>
                </a>
                @endcan
                @can('edit atc resources')
                <a class="myczqo-tab {{Request::is('admin/publications/atc-resources') || Request::is('admin/publications/atc-resources/*') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('publications.atc-resources')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-file-alt fa-fw"></i>
                            <span style="font-size: 1.1em;">ATC Resources</span>
                        </div>
                    </li>
                </a>
                @endcan
            </ul>
        </div>
        <div class="col-md-9">
            @yield('publications-content')
        </div>
    </div>
</div>
@endsection
