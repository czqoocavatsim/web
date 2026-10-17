@extends('layouts.primary')

@section('title', 'Controller Sign-up | ')
@section('content')
<div class="container py-5" style="max-width: 860px;">
    <div class="text-center mb-4">
        <span class="badge badge-info mb-2">GANDER OCEANIC OCA</span>
        <h1 class="font-weight-bold">Event Controller Sign-up</h1>
        <p class="text-muted">Share your availability and preferred position for our upcoming event.</p>
    </div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger">Please check the form and try again.</div>@endif

    <div class="card shadow-sm"><div class="card-body p-4 p-md-5">
        @if($slots->isEmpty())
            <div class="alert alert-info mb-0">Signup times are not available yet. Please check back later.</div>
        @else
        <form method="POST" action="{{ route('signups.submit') }}">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-7"><label for="full_name">Full name</label><input id="full_name" name="full_name" class="form-control" maxlength="120" required value="{{ old('full_name') }}">@error('full_name')<small class="text-danger">{{ $message }}</small>@enderror</div>
                <div class="form-group col-md-5"><label for="cid">Controller CID</label><input id="cid" name="cid" class="form-control" inputmode="numeric" pattern="[0-9]{1,10}" maxlength="10" required value="{{ old('cid') }}">@error('cid')<small class="text-danger">Enter a numeric CID.</small>@enderror</div>
            </div>
            <fieldset class="form-group mt-3"><legend class="h5">Times available</legend><p class="text-muted small">Select every time you can control.</p>
                @foreach($slots as $slot)<div class="custom-control custom-checkbox mb-2"><input class="custom-control-input" type="checkbox" id="slot-{{ $slot->id }}" name="availability[]" value="{{ $slot->id }}" {{ in_array((string) $slot->id, old('availability', [])) ? 'checked' : '' }}><label class="custom-control-label" for="slot-{{ $slot->id }}">{{ $slot->label }}</label></div>@endforeach
                @error('availability')<small class="text-danger">{{ $message }}</small>@enderror
            </fieldset>
            <fieldset class="form-group mt-4"><legend class="h5">Preferred position</legend>
                @foreach(['Gander', 'Shanwick', 'Either position/NAT'] as $position)<div class="custom-control custom-radio mb-2"><input class="custom-control-input" type="radio" id="position-{{ $loop->index }}" name="preferred_position" value="{{ $position }}" required {{ old('preferred_position') === $position ? 'checked' : '' }}><label class="custom-control-label" for="position-{{ $loop->index }}">{{ $position }}</label></div>@endforeach
                @error('preferred_position')<small class="text-danger">{{ $message }}</small>@enderror
            </fieldset>
            <button type="submit" class="btn btn-primary btn-lg mt-3">Submit signup</button>
        </form>
        @endif
    </div></div>
</div>
@endsection
