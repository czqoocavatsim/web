@extends('layouts.primary')

@section('title', 'Event Sign-ups | ')
@section('content')
<div class="container py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4"><div><h1 class="font-weight-bold">Event Sign-ups & Rosters</h1><p class="text-muted mb-0">Collect availability, set hourly staffing needs, arrange shifts, then publish when ready.</p></div><a href="{{ route('signups.show') }}" class="btn btn-outline-primary">View signup page</a></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

    <div class="card shadow-sm mb-4"><div class="card-header font-weight-bold">Add an event</div><div class="card-body">
        <form method="POST" action="{{ route('signups.admin.events.create') }}">@csrf
            <div class="form-row align-items-end">
                <div class="form-group col-md-4"><label for="event-name">Event name</label><input id="event-name" name="name" class="form-control" placeholder="e.g. Saturday Oceanic Event" maxlength="120" required></div>
                <div class="form-group col-md-3"><label for="event-start">Starts (UTC)</label><input id="event-start" type="datetime-local" step="3600" name="starts_at" class="form-control" required></div>
                <div class="form-group col-md-3"><label for="event-end">Ends (UTC)</label><input id="event-end" type="datetime-local" step="3600" name="ends_at" class="form-control" required></div>
                <div class="form-group col-md-2"><button class="btn btn-primary btn-block">Add event</button></div>
            </div>
        </form>
    </div></div>

    <h2 class="h4 mb-3">Events and roster planner</h2>
    @if($events->isNotEmpty())
    @foreach($events as $event)
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                <div><strong>{{ $event->name }}</strong>@if($event->event_date)<span class="text-muted ml-2">{{ $event->event_date->format('D j M Y') }}</span>@endif <span class="badge {{ $event->is_open ? 'badge-success' : 'badge-secondary' }} ml-2">{{ $event->is_open ? 'Signup open' : 'Signup closed' }}</span></div>
                <div class="d-flex flex-wrap">
                    @if($event->roster_published)<a class="btn btn-sm btn-outline-primary mr-2" href="{{ route('signups.roster', $event) }}" target="_blank">View published roster</a>@endif
                    <form method="POST" action="{{ route('signups.admin.events.toggle', $event) }}">@csrf<button class="btn btn-sm btn-outline-primary">{{ $event->is_open ? 'Close signups' : 'Reopen signups' }}</button></form>
                </div>
            </div>
            <div class="card-body">
                <p class="text-muted">{{ $event->starts_at ? $event->starts_at->format('D j M H:i') : 'Hours not set' }}–{{ $event->ends_at ? $event->ends_at->format('H:i') : '' }}Z</p>

                <div class="card mb-4"><div class="card-header font-weight-bold">Controllers needed each hour</div><div class="card-body">
                    <form method="POST" action="{{ route('signups.admin.events.requirements', $event) }}">@csrf
                            <div class="table-responsive"><table class="table table-sm table-bordered"><thead><tr><th>Hour (UTC)</th><th>Gander controllers</th><th>Shanwick controllers</th></tr></thead><tbody>
                                @foreach($event->planner_hours as $index => $hour)
                                    @php($target = $event->staffing_requirements[$hour->toDateTimeString()] ?? [])
                                    @php($ganderAssigned = $event->assignments->filter(function ($assignment) use ($hour) { return $assignment->position && $assignment->position->sector === 'Gander' && $assignment->starts_at->lt($hour->copy()->addHour()) && $assignment->ends_at->gt($hour); })->count())
                                    @php($shanwickAssigned = $event->assignments->filter(function ($assignment) use ($hour) { return $assignment->position && $assignment->position->sector === 'Shanwick' && $assignment->starts_at->lt($hour->copy()->addHour()) && $assignment->ends_at->gt($hour); })->count())
                                    <tr><th>{{ $hour->format('D H:i') }}–{{ $hour->copy()->addHour()->format('H:i') }}Z</th><td><div class="d-flex align-items-center"><input type="number" min="0" max="20" name="requirements[{{ $index }}][gander]" class="form-control form-control-sm mr-2" value="{{ $target['Gander'] ?? 0 }}" aria-label="Gander controllers required"><span class="small text-muted text-nowrap">Assigned: {{ $ganderAssigned }}</span></div></td><td><div class="d-flex align-items-center"><input type="number" min="0" max="20" name="requirements[{{ $index }}][shanwick]" class="form-control form-control-sm mr-2" value="{{ $target['Shanwick'] ?? 0 }}" aria-label="Shanwick controllers required"><span class="small text-muted text-nowrap">Assigned: {{ $shanwickAssigned }}</span></div></td></tr>
                                @endforeach
                            </tbody></table></div><button class="btn btn-primary btn-sm" {{ $event->planner_hours->isEmpty() ? 'disabled' : '' }}>Save staffing needs</button>
                    </form>
                    @if($event->planner_hours->isEmpty())<p class="text-muted mb-0 mt-2">Set event start and finish times to create the hourly staffing table.</p>@endif
                </div></div>

                <div class="card mb-4"><div class="card-header font-weight-bold">Roster positions (callsigns)</div><div class="card-body">
                    <form class="form-row align-items-end mb-3" method="POST" action="{{ route('signups.admin.events.positions.create', $event) }}">@csrf
                        <div class="form-group col-md-3"><label for="position-sector-{{ $event->id }}">Sector</label><select id="position-sector-{{ $event->id }}" name="sector" class="form-control"><option>Gander</option><option>Shanwick</option></select></div>
                        <div class="form-group col-md-6"><label for="position-name-{{ $event->id }}">Position name / callsign</label><input id="position-name-{{ $event->id }}" name="callsign" class="form-control" placeholder="e.g. Gander East" maxlength="120" required></div>
                        <div class="form-group col-md-3"><button class="btn btn-outline-primary btn-block">Add position</button></div>
                    </form>
                    <div class="d-flex flex-wrap">
                        @if($event->positions->isNotEmpty())
                        @foreach($event->positions as $position)
                            <form method="POST" action="{{ route('signups.admin.positions.toggle', $position) }}" class="mr-2 mb-2">@csrf<button class="btn btn-sm {{ $position->is_open ? 'btn-outline-success' : 'btn-outline-secondary' }}">{{ $position->sector }} · {{ $position->callsign }} {{ $position->is_open ? '(open)' : '(closed)' }}</button></form>
                        @endforeach
                    @else
                        <p class="text-muted mb-0">Add positions for each sector before assigning controllers.</p>
                    @endif
                    </div>
                </div></div>

                <div class="card mb-4"><div class="card-header font-weight-bold">Controller availability and shift assignment ({{ $event->signups->count() }})</div><div class="card-body">
                    @if($event->signups->isNotEmpty())
                    @foreach($event->signups as $signup)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex flex-wrap justify-content-between"><div><strong>{{ $signup->full_name }}</strong> <span class="text-muted">CID {{ $signup->cid }} · Prefers {{ $signup->preferred_position }}</span></div></div>
                            <p class="small text-muted mb-2">Available: @if(!empty($signup->availability_windows)) @foreach($signup->availability_windows as $window){{ \Carbon\Carbon::parse($window['starts_at'])->format('D H:i') }}–{{ \Carbon\Carbon::parse($window['ends_at'])->format('H:i') }}Z@if(!$loop->last), @endif @endforeach @else No availability window recorded @endif</p>
                            @foreach($signup->assignments as $assignment)
                                @php
                                    $windowForShift = collect($signup->availability_windows ?? [])->first(function ($window) use ($assignment) {
                                        return $assignment->starts_at->gte(\Carbon\Carbon::parse($window['starts_at'])) && $assignment->ends_at->lte(\Carbon\Carbon::parse($window['ends_at']));
                                    });
                                    $windowStart = $windowForShift ? \Carbon\Carbon::parse($windowForShift['starts_at']) : $assignment->starts_at->copy();
                                    $windowEnd = $windowForShift ? \Carbon\Carbon::parse($windowForShift['ends_at']) : $assignment->ends_at->copy();
                                    $windowMinutes = max(1, $windowStart->diffInMinutes($windowEnd));
                                    $stripLeft = 100 * $windowStart->diffInMinutes($assignment->starts_at) / $windowMinutes;
                                    $stripWidth = 100 * $assignment->starts_at->diffInMinutes($assignment->ends_at) / $windowMinutes;
                                @endphp
                                <form method="POST" action="{{ route('signups.admin.assignments.save') }}" class="form-row align-items-end bg-light rounded p-2 mb-2">@csrf
                                    <input type="hidden" name="event_id" value="{{ $event->id }}"><input type="hidden" name="signup_id" value="{{ $signup->id }}"><input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
                                    <div class="form-group col-md-3"><label>Position</label><select name="position_id" class="form-control form-control-sm" required>@foreach($event->positions as $position)<option value="{{ $position->id }}" {{ $assignment->position_id === $position->id ? 'selected' : '' }}>{{ $position->sector }} · {{ $position->callsign }}</option>@endforeach</select></div>
                                    <div class="form-group col-md-3"><label>Shift starts (UTC)</label><input id="assignment-start-{{ $assignment->id }}" type="datetime-local" step="3600" name="starts_at" class="form-control form-control-sm" value="{{ $assignment->starts_at->format('Y-m-d\\TH:i') }}" required></div>
                                    <div class="form-group col-md-3"><label>Shift ends (UTC)</label><input id="assignment-end-{{ $assignment->id }}" type="datetime-local" step="3600" name="ends_at" class="form-control form-control-sm" value="{{ $assignment->ends_at->format('Y-m-d\\TH:i') }}" required></div>
                                    <div class="form-group col-md-2"><button class="btn btn-sm btn-primary btn-block">Save shift</button></div>
                                    <div class="form-group col-md-1"><button form="delete-assignment-{{ $assignment->id }}" class="btn btn-sm btn-outline-danger btn-block">×</button></div>
                                    <div class="col-12"><div class="shift-timeline" data-min="{{ $windowStart->format('Y-m-d\\TH:i') }}" data-max="{{ $windowEnd->format('Y-m-d\\TH:i') }}" data-start-input="assignment-start-{{ $assignment->id }}" data-end-input="assignment-end-{{ $assignment->id }}"><div class="shift-availability"></div><div class="shift-strip" style="left: {{ $stripLeft }}%; width: {{ $stripWidth }}%;"><button type="button" class="shift-handle shift-handle-start" aria-label="Resize shift start"></button><span>{{ $assignment->position->callsign }}</span><button type="button" class="shift-handle shift-handle-end" aria-label="Resize shift end"></button></div></div><div class="small text-muted">Drag the strip to move the shift or drag either end to resize it within this controller’s availability. Click Save shift to keep the change.</div></div>
                                </form>
                                <form id="delete-assignment-{{ $assignment->id }}" method="POST" action="{{ route('signups.admin.assignments.delete', $assignment) }}">@csrf @method('DELETE')</form>
                            @endforeach
                            @if($event->positions->where('is_open', true)->isNotEmpty())
                                @foreach($signup->availability_windows ?? [] as $windowIndex => $availableWindow)
                                    <form method="POST" action="{{ route('signups.admin.assignments.save') }}" class="form-row align-items-end mt-2">@csrf
                                        <input type="hidden" name="event_id" value="{{ $event->id }}"><input type="hidden" name="signup_id" value="{{ $signup->id }}">
                                        <div class="form-group col-md-3"><label>Assign to position</label><select name="position_id" class="form-control form-control-sm" required>@foreach($event->positions->where('is_open', true) as $position)<option value="{{ $position->id }}" {{ ($signup->preferred_position === 'Either position/NAT' || $signup->preferred_position === $position->sector) ? '' : 'disabled' }}>{{ $position->sector }} · {{ $position->callsign }}</option>@endforeach</select></div>
                                        <div class="form-group col-md-3"><label>Shift starts (UTC)</label><input type="datetime-local" step="3600" name="starts_at" class="form-control form-control-sm" min="{{ \Carbon\Carbon::parse($availableWindow['starts_at'])->format('Y-m-d\\TH:i') }}" max="{{ \Carbon\Carbon::parse($availableWindow['ends_at'])->format('Y-m-d\\TH:i') }}" value="{{ \Carbon\Carbon::parse($availableWindow['starts_at'])->format('Y-m-d\\TH:i') }}" required></div>
                                        <div class="form-group col-md-3"><label>Shift ends (UTC)</label><input type="datetime-local" step="3600" name="ends_at" class="form-control form-control-sm" min="{{ \Carbon\Carbon::parse($availableWindow['starts_at'])->format('Y-m-d\\TH:i') }}" max="{{ \Carbon\Carbon::parse($availableWindow['ends_at'])->format('Y-m-d\\TH:i') }}" value="{{ \Carbon\Carbon::parse($availableWindow['starts_at'])->addHours(2)->min(\Carbon\Carbon::parse($availableWindow['ends_at']))->format('Y-m-d\\TH:i') }}" required></div>
                                        <div class="form-group col-md-3"><button class="btn btn-sm btn-outline-primary btn-block">Add shift</button></div>
                                    </form>
                                @endforeach
                            @endif
                        </div>
                    @endforeach
                    @else
                        <p class="text-muted mb-0">No controllers have signed up for this event yet.</p>
                    @endif
                </div></div>

                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <p class="small text-muted mb-2">Publish only after the shifts and positions are final. Public roster shows names, position callsigns and times; CIDs stay on this admin page.</p>
                    <form method="POST" action="{{ route('signups.admin.events.publish', $event) }}">@csrf<button class="btn {{ $event->roster_published ? 'btn-outline-danger' : 'btn-primary' }}">{{ $event->roster_published ? 'Unpublish roster' : 'Publish event roster' }}</button></form>
                </div>
            </div>
        </div>
    @endforeach
    @else
        <div class="alert alert-info">No events yet. Add an event above to start planning.</div>
    @endif

    <div class="card shadow-sm mt-4"><div class="card-header font-weight-bold">Previous controller responses ({{ $signups->whereNull('event_id')->count() }})</div><div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>Name</th><th>CID</th><th>Preferred position</th><th>Submitted</th></tr></thead><tbody>
        @if($signups->whereNull('event_id')->isNotEmpty())
            @foreach($signups->whereNull('event_id') as $signup)<tr><td>{{ $signup->full_name }}</td><td>{{ $signup->cid }}</td><td>{{ $signup->preferred_position }}</td><td>{{ $signup->submitted_at->format('Y-m-d H:i') }}Z</td></tr>@endforeach
        @else
            <tr><td colspan="4" class="text-center text-muted py-3">No previous responses.</td></tr>
        @endif
    </tbody></table></div></div>
</div>
<style>
.shift-timeline{height:28px;position:relative;border-radius:6px;background:#dce6ef;margin:4px 0 8px;touch-action:none;cursor:grab;user-select:none}.shift-timeline:active{cursor:grabbing}.shift-availability{position:absolute;inset:0;border-radius:6px;background:linear-gradient(90deg,#e8edf2,#dce6ef)}.shift-strip{position:absolute;top:0;height:100%;min-width:7%;border-radius:6px;background:#0878bd;color:white;display:flex;align-items:center;justify-content:space-between;overflow:hidden;font-size:12px;white-space:nowrap}.shift-strip span{overflow:hidden;text-overflow:ellipsis;padding:0 4px}.shift-handle{width:9px;min-width:9px;height:100%;border:0;background:#075b8f;padding:0;cursor:ew-resize}.shift-handle-start{border-radius:6px 0 0 6px}.shift-handle-end{border-radius:0 6px 6px 0}
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.shift-timeline').forEach(function (timeline) {
        var bar = timeline.querySelector('.shift-strip');
        var startInput = document.getElementById(timeline.dataset.startInput);
        var endInput = document.getElementById(timeline.dataset.endInput);
        var min = new Date(timeline.dataset.min + ':00Z').getTime();
        var max = new Date(timeline.dataset.max + ':00Z').getTime();
        var hour = 60 * 60 * 1000;
        function update(start, end) {
            startInput.value = new Date(start).toISOString().slice(0, 16);
            endInput.value = new Date(end).toISOString().slice(0, 16);
            bar.style.left = ((start - min) / (max - min) * 100) + '%';
            bar.style.width = ((end - start) / (max - min) * 100) + '%';
        }
        timeline.addEventListener('pointerdown', function (event) {
            var handle = event.target.closest('.shift-handle');
            var mode = handle ? (handle.classList.contains('shift-handle-start') ? 'start' : 'end') : 'move';
            var rect = timeline.getBoundingClientRect();
            var originalStart = new Date(startInput.value + ':00Z').getTime();
            var originalEnd = new Date(endInput.value + ':00Z').getTime();
            var initialX = event.clientX;
            timeline.setPointerCapture(event.pointerId);
            function move(e) {
                var delta = Math.round(((e.clientX - initialX) / rect.width * (max - min)) / hour) * hour;
                var start = originalStart;
                var end = originalEnd;
                if (mode === 'start') start = Math.max(min, Math.min(originalEnd - hour, originalStart + delta));
                else if (mode === 'end') end = Math.min(max, Math.max(originalStart + hour, originalEnd + delta));
                else { start = Math.max(min, Math.min(max - (originalEnd - originalStart), originalStart + delta)); end = start + originalEnd - originalStart; }
                update(start, end);
            }
            function done() { timeline.removeEventListener('pointermove', move); timeline.removeEventListener('pointerup', done); }
            timeline.addEventListener('pointermove', move);
            timeline.addEventListener('pointerup', done);
        });
        function inputChanged() {
            var start = new Date(startInput.value + ':00Z').getTime();
            var end = new Date(endInput.value + ':00Z').getTime();
            if (start >= min && end <= max && end > start) update(start, end);
        }
        startInput.addEventListener('change', inputChanged);
        endInput.addEventListener('change', inputChanged);
    });
});
</script>
@endsection
