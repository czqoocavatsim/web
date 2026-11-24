@extends('admin.training.layouts.main')
@section('title', 'Exam Centre - Training - ')
@section('training-content')
<h1 class="blue-text font-weight-bold pb-2">Exam Centre</h1>
<p class="lead">{{ $exam->name }} &mdash; {{ $exam->active ? 'Open to students' : 'Closed (students cannot start it)' }}</p>
@php $canEdit = auth()->user()->hasAnyRole(['Senior Staff', 'Administrator']); @endphp

<div class="card p-4 mb-4 z-depth-1 shadow-none">
    <h4 class="blue-text">Settings</h4>
    <form method="POST" action="{{ route('training.admin.exam.settings') }}">
        @csrf
        <div class="row">
            <div class="col-md-3 mb-3"><label>Pass mark (%)</label><input class="form-control" type="number" name="pass_percentage" min="1" max="100" value="{{ $exam->pass_percentage }}" {{ $canEdit ? '' : 'disabled' }}></div>
            <div class="col-md-3 mb-3"><label>Questions to ask (0 = all)</label><input class="form-control" type="number" name="questions_to_ask" min="0" value="{{ $exam->questions_to_ask }}" {{ $canEdit ? '' : 'disabled' }}></div>
            <div class="col-md-3 mb-3"><label>First retake wait (days)</label><input class="form-control" type="number" name="initial_retake_days" min="0" value="{{ $exam->initial_retake_days }}" {{ $canEdit ? '' : 'disabled' }}></div>
            <div class="col-md-3 mb-3"><label>Later retake wait (days)</label><input class="form-control" type="number" name="subsequent_retake_days" min="0" value="{{ $exam->subsequent_retake_days }}" {{ $canEdit ? '' : 'disabled' }}></div>
            <div class="col-md-3 mb-3"><label>Time limit (minutes, blank = none)</label><input class="form-control" type="number" name="time_limit_minutes" min="1" value="{{ $exam->time_limit_minutes }}" {{ $canEdit ? '' : 'disabled' }}></div>
            <div class="col-md-3 mb-3 d-flex align-items-end"><div class="form-check"><input class="form-check-input" type="checkbox" name="active" value="1" id="active" {{ $exam->active ? 'checked' : '' }} {{ $canEdit ? '' : 'disabled' }}><label class="form-check-label" for="active">Exam open</label></div></div>
            <div class="col-md-12 mb-3"><label>Discord channel ID for exam results (the bot must be able to see and post in it)</label><input class="form-control" type="text" name="notify_channel_id" value="{{ $exam->notify_channel_id }}" placeholder="e.g. 123456789012345678" {{ $canEdit ? '' : 'disabled' }}><small class="grey-text">Every pass and fail is posted here. In Discord turn on Developer Mode, right-click the channel and choose Copy Channel ID.</small></div>
            <div class="col-md-12 mb-3"><label>Optional: also DM these staff (VATSIM CIDs, comma separated)</label><input class="form-control" type="text" name="notify_cids" value="{{ $exam->notify_cids }}" placeholder="e.g. 1234567, 7654321" {{ $canEdit ? '' : 'disabled' }}><small class="grey-text">They must have linked their Discord on the website. Passes and fails are both sent.</small></div>
        </div>
        @if ($canEdit)<button class="btn btn-primary" type="submit">Save settings</button>@endif
    </form>
</div>

@if ($canEdit)
<div class="card p-4 mb-4 z-depth-1 shadow-none">
    <h4 class="blue-text">Import questions (CSV)</h4>
    <p class="mb-2">Columns: <code>question, option 1, option 2, option 3, option 4, correct, required</code> (up to 8 options). <code>correct</code> is a letter (A, B, C…) or number (1, 2, 3…). <code>required</code> is optional (yes = always included).</p>
    <form method="POST" action="{{ route('training.admin.exam.questions.import') }}" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" accept=".csv,text/csv" class="mb-2" required><br>
        <button class="btn btn-primary" type="submit">Import</button>
    </form>
</div>

<div class="card p-4 mb-4 z-depth-1 shadow-none">
    <h4 class="blue-text">Add a question</h4>
    <form method="POST" action="{{ route('training.admin.exam.questions.store') }}">
        @csrf
        <div class="mb-3"><label>Question</label><textarea class="form-control" name="question" rows="2" required>{{ old('question') }}</textarea></div>
        @for ($i = 0; $i < 4; $i++)
        <div class="input-group mb-2">
            <div class="input-group-prepend"><div class="input-group-text"><input type="radio" name="correct" value="{{ $i }}" {{ $i === 0 ? 'checked' : '' }} title="Correct answer"></div></div>
            <input type="text" class="form-control" name="options[{{ $i }}]" placeholder="Option {{ $i + 1 }}" value="{{ old('options.'.$i) }}">
        </div>
        @endfor
        <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="required" value="1" id="req"><label class="form-check-label" for="req">Always include in every paper</label></div>
        <button class="btn btn-primary" type="submit">Add question</button>
    </form>
</div>
@endif

<h4 class="blue-text">Question bank ({{ $questions->where('active', true)->count() }} active of {{ $questions->count() }})</h4>
<table class="table table-bordered">
    <thead><tr><th>#</th><th>Question</th><th>Options</th><th></th></tr></thead>
    <tbody>
    @foreach ($questions as $q)
        <tr class="{{ $q->active ? '' : 'text-muted' }}">
            <td>{{ $q->id }}</td>
            <td>{{ $q->question }} @if ($q->required)<span class="badge badge-info">Required</span>@endif @if (! $q->isAskable())<span class="badge badge-danger">Not asked: needs one correct answer</span>@endif @if (! $q->active)<span class="badge badge-secondary">Disabled</span>@endif</td>
            <td>@foreach ($q->options as $o)<div>{!! $o->is_correct ? '<strong>&#10003;</strong>' : '&bull;' !!} {{ $o->option_text }}</div>@endforeach</td>
            <td style="white-space:nowrap;">
                @if ($canEdit)
                <form method="POST" action="{{ route('training.admin.exam.questions.toggle', $q) }}" class="d-inline">@csrf<button class="btn btn-sm btn-outline-secondary">{{ $q->active ? 'Disable' : 'Enable' }}</button></form>
                <form method="POST" action="{{ route('training.admin.exam.questions.delete', $q) }}" class="d-inline" onsubmit="return confirm('Delete this question?');">@csrf<button class="btn btn-sm btn-outline-danger">Delete</button></form>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<h4 class="blue-text mt-4">Recent attempts</h4>
<table class="table table-bordered">
    <thead><tr><th>CID</th><th>Name</th><th>Started (UTC)</th><th>Result</th><th>Score</th></tr></thead>
    <tbody>
    @foreach ($attempts as $a)
        <tr>
            <td>{{ $a->user_id }}</td>
            <td>{{ $a->user ? $a->user->fullName('FL') : '' }}</td>
            <td>{{ $a->started_at->format('j M Y H:i') }}</td>
            <td>{{ $a->status === 'in_progress' ? 'In progress' : ucfirst($a->status) }}</td>
            <td>{{ $a->isFinished() ? $a->correct_count.'/'.$a->total_questions.' ('.rtrim(rtrim(number_format($a->score_percent, 2), '0'), '.').'%)' : '-' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
