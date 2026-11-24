@extends('training.portal.layouts.main')
@section('title', 'My Student Page - ')
@section('page-header-title', 'My Student Page')
@section('portal-content')
@php
    $icon = ['done' => 'fa-check-circle green-text', 'current' => 'fa-dot-circle blue-text', 'todo' => 'fa-circle text-black-50', 'failed' => 'fa-times-circle red-text'];
    $done = collect($steps)->where('state', 'done')->count();
    $pct = round($done / max(count($steps), 1) * 100);
    $current = collect($steps)->firstWhere('state', 'current');
@endphp
<div class="container py-4">
    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="list-group-item rounded p-4 z-depth-1 shadow-none mb-4">
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ $user->avatar() }}" alt="" style="height:64px;width:64px;border-radius:50%;margin-right:16px;">
                    <div>
                        <h4 class="fw-600 mb-0">{{ $user->fullName('FL') }}</h4>
                        <div class="text-muted">CID {{ $user->id }}</div>
                    </div>
                </div>
                <table class="table table-sm mb-0">
                    <tr><td class="text-muted">VATSIM rating</td><td>{{ $user->rating_long ?: '-' }}</td></tr>
                    <tr><td class="text-muted">Status</td><td>
                        @if ($roster && strtolower((string) $roster->certification) === 'certified') Oceanic endorsed
                        @elseif ($student && $student->current) Student
                        @else Not a student @endif
                    </td></tr>
                    @if ($student)<tr><td class="text-muted">Student since</td><td>{{ optional($student->created_at)->format('j M Y') }}</td></tr>@endif
                    <tr><td class="text-muted">Instructor</td><td>
                        @if ($instructor && $instructor->user){{ $instructor->user->fullName('FL') }}
                        @else <span class="text-muted">Not assigned yet</span> @endif
                    </td></tr>
                </table>
            </div>

            <div class="list-group-item rounded p-4 z-depth-1 shadow-none">
                <h5 class="fw-600"><i class="fab fa-discord blue-text mr-2"></i>Join the student Discord</h5>
                <p class="small mb-3">Talk to your assigned instructor and to other students, ask questions and share what you are learning.</p>
                <a href="{{ $discord }}" target="_blank" rel="noopener" class="btn btn-primary mb-0">Join the Discord server <i class="fas fa-external-link-alt ml-1"></i></a>
                @if ($instructor && $instructor->user && $instructor->user->hasDiscord())
                    <p class="small text-muted mt-3 mb-0">Your instructor on Discord: {{ $instructor->user->discord_username }}</p>
                @endif
            </div>
        </div>

        <div class="col-lg-7 mb-4">
            <h4 class="blue-text mb-3">Your progress</h4>
            <div class="list-group-item rounded p-4 z-depth-1 shadow-none mb-4">
                <div class="progress mb-3"><div class="progress-bar blue" role="progressbar" style="width: {{ $pct }}%" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div></div>
                <div class="small text-muted mb-3">{{ $done }} of {{ count($steps) }} steps complete</div>
                @foreach ($steps as $i => $s)
                    <div class="d-flex align-items-start mb-2">
                        <i class="fas {{ $icon[$s['state']] }} mr-3 mt-1"></i>
                        <div>
                            <div class="fw-500 {{ $s['state'] === 'todo' ? 'text-muted' : '' }}">{{ $s['title'] }}</div>
                            @if ($s['note'])<div class="small text-muted">{{ $s['note'] }}</div>@endif
                        </div>
                    </div>
                @endforeach
                <a href="{{ route('training.hub') }}" class="btn btn-outline-primary btn-sm mt-2 mb-0">Open the Training Hub</a>
            </div>

            <h4 class="blue-text mb-3">Timeline</h4>
            <div class="list-group-item rounded p-4 z-depth-1 shadow-none">
                @forelse ($events as $ev)
                    <div class="d-flex align-items-start {{ $loop->last ? '' : 'mb-3 pb-3 border-bottom' }}">
                        <i class="fas {{ $ev['icon'] }} {{ $ev['colour'] }} fa-lg fa-fw mr-3 mt-1"></i>
                        <div class="flex-grow-1">
                            <div class="fw-500">{{ $ev['title'] }}</div>
                            @if ($ev['detail'])<div class="small">{{ $ev['detail'] }}</div>@endif
                        </div>
                        <div class="small text-muted text-right ml-3" style="white-space:nowrap">{{ $ev['at']->format('j M Y') }}<br>{{ $ev['at']->format('H:i') }}z</div>
                    </div>
                @empty
                    <p class="text-muted mb-0">Nothing here yet. Your application, exam and sessions will appear as they happen.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
