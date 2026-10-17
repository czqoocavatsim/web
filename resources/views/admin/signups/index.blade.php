@extends('layouts.primary')

@section('title', 'Event Sign-ups | ')
@section('content')
<div class="container py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4"><div><h1 class="font-weight-bold">Event Sign-ups</h1><p class="text-muted mb-0">Manage the available times and review controller responses.</p></div><a href="{{ route('signups.show') }}" class="btn btn-outline-primary">View signup page</a></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="card shadow-sm mb-4"><div class="card-header font-weight-bold">Availability times</div><div class="card-body">
        <form class="form-inline mb-4" method="POST" action="{{ route('signups.admin.slots.create') }}">@csrf<label class="sr-only" for="slot-label">Time label</label><input id="slot-label" name="label" class="form-control mr-2 mb-2" placeholder="e.g. 18:00–20:00Z" maxlength="120" required><button class="btn btn-primary mb-2">Add time</button></form>
        @error('label')<div class="text-danger mb-3">{{ $message }}</div>@enderror
        @forelse($slots as $slot)<div class="d-flex flex-wrap align-items-center border-top py-3"><div class="flex-grow-1"><strong>{{ $slot->label }}</strong><span class="badge {{ $slot->is_open ? 'badge-success' : 'badge-secondary' }} ml-2">{{ $slot->is_open ? 'Open' : 'Closed' }}</span></div><form method="POST" action="{{ route('signups.admin.slots.toggle', $slot) }}" class="mr-2">@csrf<button class="btn btn-sm btn-outline-primary">{{ $slot->is_open ? 'Close time' : 'Open time' }}</button></form><form method="POST" action="{{ route('signups.admin.slots.delete', $slot) }}" onsubmit="return confirm('Remove this time? Existing responses will remain recorded.')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Remove</button></form></div>@empty<p class="text-muted mb-0">No times have been added yet. Add each event time above.</p>@endforelse
    </div></div>
    <div class="card shadow-sm"><div class="card-header font-weight-bold">Controller responses ({{ $signups->count() }})</div><div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>Name</th><th>CID</th><th>Preferred position</th><th>Availability</th><th>Submitted</th></tr></thead><tbody>
        @forelse($signups as $signup)<tr><td>{{ $signup->full_name }}</td><td>{{ $signup->cid }}</td><td>{{ $signup->preferred_position }}</td><td>@foreach($signup->availability ?? [] as $id)<span class="badge badge-light mr-1">{{ $slotLabels[$id] ?? 'Removed time' }}</span>@endforeach</td><td>{{ $signup->submitted_at->format('Y-m-d H:i') }}Z</td></tr>@empty<tr><td colspan="5" class="text-center text-muted py-4">No signups received yet.</td></tr>@endforelse
    </tbody></table></div></div>
</div>
@endsection
