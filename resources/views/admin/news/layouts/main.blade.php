@extends('layouts.primary', ['adminNavBar' => true])
@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-3">
            <ul class="list-unstyled w-100">
                <li class="w-100 my-3" style="border:none;">
                    <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                        <span style="font-size: 1em;" class="text-muted">NEWS</span>
                    </div>
                </li>
                @can('view articles')
                <a class="myczqo-tab {{ request()->routeIs('news.articles*') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('news.index')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-newspaper fa-fw"></i>
                            <span style="font-size: 1.1em;">Articles</span>
                        </div>
                    </li>
                </a>
                @endcan
                @can('send announcements')
                <a class="myczqo-tab {{ request()->routeIs('news.announcements*') ? 'active' : ''}} no-click" data-myczqo-tab="none" href="{{route('news.announcements.create')}}">
                    <li class="w-100">
                        <div class="d-flex h-100 flex-row justify-content-left align-items-center">
                            <i style="font-size: 1.6em; margin-right: 10px;" class="fas fa-file-alt fa-fw"></i>
                            <span style="font-size: 1.1em;">Send Announcement</span>
                        </div>
                    </li>
                </a>
                @endcan
            </ul>
            @if ($actions == true)
            <h4 class="font-weight-bold blue-text">Actions</h4>
            <ul class="list-unstyled mt-3 mb-0" style="font-size: 1.05em;">
                @can('edit event')
                <li class="mb-2">
                    <a href="" data-toggle="modal" data-target="#editarticle" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="text-body">Edit article</span></a>
                </li>
                @endcan
            </ul>
            @endif
        </div>
        <div class="col-md-9">
            @yield('news-content')
        </div>
    </div>
</div>
@endsection
