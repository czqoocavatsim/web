@extends('layouts.primary')

@section('title', $event->name.' Roster | ')
@section('content')
<div class="container py-5" style="max-width: 980px;">
    <div class="text-center mb-4">
        <span class="badge badge-info mb-2">GANDER OCEANIC OCA</span>
        <h1 class="font-weight-bold">{{ $event->name }} Roster</h1>
        <p class="text-muted mb-0">{{ $event->event_date ? $event->event_date->format('l, j F Y') : '' }} · All times UTC</p>
    </div>
    <div class="card shadow-sm"><div class="table-responsive"><table class="table table-hover mb-0">
        <thead><tr><th>Position</th><th>Controller</th><th>Shift</th></tr></thead>
        <tbody>
            @forelse($assignments as $assignment)
                <tr><td><strong>{{ $assignment->position->sector }}</strong><br><span class="text-muted">{{ $assignment->position->callsign }}</span></td><td>{{ $assignment->signup->full_name }}</td><td>{{ $assignment->starts_at->format('D j M H:i') }}–{{ $assignment->ends_at->format('H:i') }}Z</td></tr>
            @empty
                <tr><td colspan="3" class="text-center text-muted py-4">The roster is being prepared. Please check back later.</td></tr>
            @endforelse
        </tbody>
    </table></div></div>
</div>
@endsection
