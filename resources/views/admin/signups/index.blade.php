@extends('layouts.primary')

@section('title', 'Event Sign-ups | ')
@section('content')
<div class="container py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div><h1 class="font-weight-bold">Event Sign-ups</h1><p class="text-muted mb-0">Create an event for each day, set its available times, and review controller responses.</p></div>
        <a href="{{ route('signups.show') }}" class="btn btn-outline-primary">View signup page</a>
    </div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <div class="card shadow-sm mb-4">
        <div class="card-header font-weight-bold">Add an event</div>
        <div class="card-body">
            <form method="POST" action="{{ route('signups.admin.events.create') }}">
                @csrf
                <div class="form-row align-items-end">
                    <div class="form-group col-md-7"><label for="event-name">Event name</label><input id="event-name" name="name" class="form-control" placeholder="e.g. Saturday event" maxlength="120" required></div>
                    <div class="form-group col-md-3"><label for="event-date">Date <span class="text-muted">(optional)</span></label><input id="event-date" type="date" name="event_date" class="form-control"></div>
                    <div class="form-group col-md-2"><button class="btn btn-primary btn-block">Add event</button></div>
                </div>
                @error('name')<div class="text-danger mb-2">{{ $message }}</div>@enderror
                @error('event_date')<div class="text-danger mb-2">Enter a valid event date.</div>@enderror
            </form>
        </div>
    </div>

    <h2 class="h4 mb-3">Events and signup times</h2>
    @forelse($events as $event)
        <div class="card shadow-sm mb-3">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                <div><strong>{{ $event->name }}</strong>@if($event->event_date)<span class="text-muted ml-2">{{ $event->event_date->format('D j M Y') }}</span>@endif <span class="badge {{ $event->is_open ? 'badge-success' : 'badge-secondary' }} ml-2">{{ $event->is_open ? 'Open for signups' : 'Closed' }}</span></div>
                <form method="POST" action="{{ route('signups.admin.events.toggle', $event) }}">@csrf<button class="btn btn-sm btn-outline-primary">{{ $event->is_open ? 'Close event' : 'Reopen event' }}</button></form>
            </div>
            <div class="card-body">
                <form class="form-inline mb-3" method="POST" action="{{ route('signups.admin.slots.create') }}">
                    @csrf<input type="hidden" name="event_id" value="{{ $event->id }}">
                    <label class="sr-only" for="slot-label-{{ $event->id }}">Time label</label><input id="slot-label-{{ $event->id }}" name="label" class="form-control mr-2 mb-2" placeholder="e.g. 18:00–20:00Z" maxlength="120" required>
                    <button class="btn btn-primary mb-2">Add time to {{ $event->name }}</button>
                </form>
                @error('label')<div class="text-danger mb-3">{{ $message }}</div>@enderror
                @forelse($event->slots as $slot)
                    <div class="d-flex flex-wrap align-items-center border-top py-3">
                        <div class="flex-grow-1"><strong>{{ $slot->label }}</strong><span class="badge {{ $slot->is_open ? 'badge-success' : 'badge-secondary' }} ml-2">{{ $slot->is_open ? 'Open' : 'Closed' }}</span></div>
                        <form method="POST" action="{{ route('signups.admin.slots.toggle', $slot) }}" class="mr-2">@csrf<button class="btn btn-sm btn-outline-primary">{{ $slot->is_open ? 'Close time' : 'Open time' }}</button></form>
                        <form method="POST" action="{{ route('signups.admin.slots.delete', $slot) }}" onsubmit="return confirm('Remove this time? Existing responses will remain recorded.')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Remove</button></form>
                    </div>
                @empty
                    <p class="text-muted border-top pt-3 mb-0">No times added yet. Add the time slots for this event above.</p>
                @endforelse
            </div>
        </div>
    @empty
        <div class="alert alert-info">No events yet. Add an event above, then add its time slots.</div>
    @endforelse

    <div class="card shadow-sm mt-4"><div class="card-header font-weight-bold">Controller responses ({{ $signups->count() }})</div><div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>Event</th><th>Name</th><th>CID</th><th>Preferred position</th><th>Availability</th><th>Submitted</th></tr></thead><tbody>
        @forelse($signups as $signup)<tr><td>{{ $signup->event ? $signup->event->name : 'Previous signups' }}</td><td>{{ $signup->full_name }}</td><td>{{ $signup->cid }}</td><td>{{ $signup->preferred_position }}</td><td>@foreach($signup->availability ?? [] as $id)<span class="badge badge-light mr-1">{{ $slotLabels[$id] ?? 'Removed time' }}</span>@endforeach</td><td>{{ $signup->submitted_at->format('Y-m-d H:i') }}Z</td></tr>@empty<tr><td colspan="6" class="text-center text-muted py-4">No signups received yet.</td></tr>@endforelse
    </tbody></table></div></div>
</div>
@endsection
