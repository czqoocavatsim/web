@extends('admin.training.layouts.main')
@section('training-content')
<h1 class="font-weight-bold blue-text pb-2">Calendar</h1>
<h4 id="instructing-sessions-calendar-range">Loading date...</h4>
<div class="d-flex flex-row mb-2">
    <button id="instructing-sessions-calendar-prev-button" class="btn px-3 btn-light btn-sm">
        <i class="fas fa-chevron-left"></i>
    </button>
    <button id="instructing-sessions-calendar-next-button" class="btn px-3 btn-light btn-sm">
        <i class="fas fa-chevron-right"></i>
    </button>
</div>
<div id="instructing-sessions-calendar">

</div>
@endsection
