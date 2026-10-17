@extends('layouts.primary')

@section('title', 'Controller Sign-up | ')
@section('content')
<div class="container py-5" style="max-width: 860px;">
    <div class="text-center mb-4">
        <span class="badge badge-info mb-2">GANDER OCEANIC OCA</span>
        <h1 class="font-weight-bold">Event Controller Sign-up</h1>
        <p class="text-muted">Choose an event, then share your availability and preferred position.</p>
    </div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger">Please check the form and try again.</div>@endif

    <div class="card shadow-sm"><div class="card-body p-4 p-md-5">
        @if($events->isEmpty())
            <div class="alert alert-info mb-0">There are no events open for signup yet. Please check back later.</div>
        @else
            <form method="GET" action="{{ route('signups.show') }}" class="form-group mb-4">
                <label for="event_id" class="h5">Choose an event</label>
                <div class="form-row align-items-center">
                    <div class="col-md-9 mb-2">
                        <select id="event_id" name="event_id" class="form-control" required>
                            <option value="">Select an event</option>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}" {{ $selectedEvent && $selectedEvent->id === $event->id ? 'selected' : '' }}>
                                    {{ $event->name }}{{ $event->event_date ? ' — '.$event->event_date->format('D j M Y') : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2"><button class="btn btn-outline-primary btn-block">Show times</button></div>
                </div>
            </form>

            @if(!$selectedEvent)
                <div class="alert alert-light border mb-0">Select an event above to see its available times.</div>
            @elseif(!$selectedEvent->starts_at || !$selectedEvent->ends_at)
                <div class="alert alert-info mb-0">This event is not accepting signups yet. Please check back later.</div>
            @else
                <form method="POST" action="{{ route('signups.submit') }}">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $selectedEvent->id }}">
                    <h2 class="h5 mb-3">{{ $selectedEvent->name }}{{ $selectedEvent->event_date ? ' — '.$selectedEvent->event_date->format('D j M Y') : '' }}</h2>
                    <div class="form-row">
                        <div class="form-group col-md-7"><label for="full_name">Full name</label><input id="full_name" name="full_name" class="form-control" maxlength="120" required value="{{ old('full_name') }}">@error('full_name')<small class="text-danger">{{ $message }}</small>@enderror</div>
                        <div class="form-group col-md-5"><label for="cid">Controller CID</label><input id="cid" name="cid" class="form-control" inputmode="numeric" pattern="[0-9]{1,10}" maxlength="10" required value="{{ old('cid') }}">@error('cid')<small class="text-danger">Enter a numeric CID.</small>@enderror</div>
                    </div>
                    <fieldset class="form-group mt-3"><legend class="h5">When are you available?</legend><p class="text-muted small">Enter one or more availability windows in UTC. The roster team will schedule a shift within the times you give.</p>
                        <div id="availability-windows">
                            <div class="form-row align-items-end availability-window mb-2">
                                <div class="form-group col-md-5"><label>Available from (UTC)</label><input type="datetime-local" step="3600" name="availability_windows[0][starts_at]" class="form-control" min="{{ $selectedEvent->starts_at->format('Y-m-d\\TH:i') }}" max="{{ $selectedEvent->ends_at->format('Y-m-d\\TH:i') }}" required></div>
                                <div class="form-group col-md-5"><label>Available until (UTC)</label><input type="datetime-local" step="3600" name="availability_windows[0][ends_at]" class="form-control" min="{{ $selectedEvent->starts_at->format('Y-m-d\\TH:i') }}" max="{{ $selectedEvent->ends_at->format('Y-m-d\\TH:i') }}" required></div>
                                <div class="form-group col-md-2"><button type="button" class="btn btn-outline-danger btn-block remove-window" disabled>Remove</button></div>
                            </div>
                        </div>
                        <button type="button" id="add-window" class="btn btn-sm btn-outline-primary">Add another window</button>
                        @error('availability_windows')<small class="d-block text-danger mt-2">{{ $message }}</small>@enderror
                    </fieldset>
                    <fieldset class="form-group mt-4"><legend class="h5">Preferred position</legend>
                        @foreach(['Gander', 'Shanwick', 'Either position/NAT'] as $position)<div class="custom-control custom-radio mb-2"><input class="custom-control-input" type="radio" id="position-{{ $loop->index }}" name="preferred_position" value="{{ $position }}" required {{ old('preferred_position') === $position ? 'checked' : '' }}><label class="custom-control-label" for="position-{{ $loop->index }}">{{ $position }}</label></div>@endforeach
                        @error('preferred_position')<small class="text-danger">{{ $message }}</small>@enderror
                    </fieldset>
                    <button type="submit" class="btn btn-primary btn-lg mt-3">Submit signup</button>
                </form>
            @endif
        @endif
    </div></div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var list = document.getElementById('availability-windows');
    var add = document.getElementById('add-window');
    if (!list || !add) return;
    var nextIndex = list.querySelectorAll('.availability-window').length;
    var start = @json($selectedEvent && $selectedEvent->starts_at ? $selectedEvent->starts_at->format('Y-m-d\\TH:i') : '');
    var end = @json($selectedEvent && $selectedEvent->ends_at ? $selectedEvent->ends_at->format('Y-m-d\\TH:i') : '');
    add.addEventListener('click', function () {
        if (list.querySelectorAll('.availability-window').length >= 5) return;
        var index = nextIndex++;
        var row = document.createElement('div');
        row.className = 'form-row align-items-end availability-window mb-2';
        row.innerHTML = '<div class="form-group col-md-5"><label>Available from (UTC)</label><input type="datetime-local" step="3600" name="availability_windows[' + index + '][starts_at]" class="form-control" min="' + start + '" max="' + end + '" required></div>' +
            '<div class="form-group col-md-5"><label>Available until (UTC)</label><input type="datetime-local" step="3600" name="availability_windows[' + index + '][ends_at]" class="form-control" min="' + start + '" max="' + end + '" required></div>' +
            '<div class="form-group col-md-2"><button type="button" class="btn btn-outline-danger btn-block remove-window">Remove</button></div>';
        list.appendChild(row);
        row.querySelector('.remove-window').addEventListener('click', function () { row.remove(); });
    });
});
</script>
@endsection
