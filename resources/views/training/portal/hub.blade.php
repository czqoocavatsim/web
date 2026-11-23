@extends('training.portal.layouts.main')
@section('title', 'Training Hub - ')
@section('page-header-title', 'Training Hub')
@section('portal-content')
@php
    $current = collect($steps)->firstWhere('state', 'current');
    $icon = ['done' => 'fa-check-circle green-text', 'current' => 'fa-dot-circle blue-text', 'todo' => 'fa-circle text-black-50', 'failed' => 'fa-times-circle red-text'];
    $next = [
        'application' => ['Start your application', route('training.applications.apply')],
        'review' => ['View your application', ($app = Auth::user()->pendingApplication()) ? route('training.applications.show', $app->reference_id) : route('training.applications.showall')],
        'exam' => ['Go to the entrance exam', route('training.exam.show')],
        'session' => ['Submit your availability', route('training.portal.availability')],
    ];
@endphp
<div class="container py-4">
    <h4 class="blue-text mb-3">Your path to the Oceanic endorsement</h4>
    <div class="list-group-item rounded p-4 mb-4 z-depth-1 shadow-none">
        @foreach ($steps as $i => $s)
            <div class="d-flex align-items-start mb-3">
                <i class="fas {{ $icon[$s['state']] }} fa-lg mr-3 mt-1"></i>
                <div>
                    <div class="fw-500 {{ $s['state'] === 'todo' ? 'text-muted' : '' }}">{{ $i + 1 }}. {{ $s['title'] }}</div>
                    @if ($s['note'])<div class="small text-muted">{{ $s['note'] }}</div>@endif
                </div>
            </div>
        @endforeach
        @if ($current && isset($next[$current['key']]))
            <a href="{{ $next[$current['key']][1] }}" class="btn btn-primary mb-0">{{ $next[$current['key']][0] }} <i class="fas fa-arrow-right ml-1"></i></a>
        @endif
    </div>

    <h4 class="blue-text mb-3">Study material</h4>
    <div class="row mb-4">
        @foreach ([['sop', 'SOP', 'fa-clipboard-list', 'Standard operating procedures for Gander Oceanic.'], ['manual', 'Training Manual', 'fa-book-open', 'What you will be taught and assessed on.'], ['knowledgebase', 'Knowledge Base', 'fa-book', 'Guides and reference pages.']] as [$k, $t, $ic, $d])
            <div class="col-md-4 mb-3">
                <div class="list-group-item rounded p-3 h-100 z-depth-1 shadow-none">
                    <h5 class="fw-600"><i class="fas {{ $ic }} blue-text mr-2"></i>{{ $t }}</h5>
                    <p class="small mb-2">{{ $d }}</p>
                    @if ($links[$k])<a href="{{ $links[$k] }}" target="_blank" rel="noopener" class="fw-500">Open <i class="fas fa-external-link-alt"></i></a>
                    @else<span class="text-muted small">Coming soon</span>@endif
                </div>
            </div>
        @endforeach
    </div>

    <h4 class="blue-text mb-1">Videos</h4>
    <p class="small text-muted mb-3">About {{ collect($videos)->sum('minutes') }} minutes in total. All videos have captions.</p>
    @foreach ($videos as $i => $v)
        <div class="list-group-item rounded p-3 mb-3 z-depth-1 shadow-none">
            <h5 class="fw-600 mb-2">{{ $i + 1 }}. {{ $v['title'] }} <span class="small text-muted">({{ $v['minutes'] }} min)</span></h5>
            @if ($v['url'])
                <video controls preload="metadata" class="w-100 rounded" crossorigin="anonymous">
                    <source src="{{ $v['url'] }}" type="video/mp4">
                    @if ($v['captions'])<track kind="captions" srclang="en" label="English" src="{{ $v['captions'] }}" default>@endif
                </video>
            @else
                <span class="text-muted small">Coming soon</span>
            @endif
        </div>
    @endforeach
</div>
@endsection
