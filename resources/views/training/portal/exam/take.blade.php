@extends('training.portal.layouts.main')
@section('title', 'Entrance Exam - ')
@section('page-header-title', 'Entrance Exam')
@section('portal-content')
<div class="container py-4">
    @if ($attempt->exam->time_limit_minutes)
        <div class="alert alert-warning">
            Time limit {{ $attempt->exam->time_limit_minutes }} minutes. Started {{ $attempt->started_at->format('H:i') }} UTC; it is submitted automatically when time runs out.
        </div>
    @endif
    <form method="POST" action="{{ route('training.exam.submit', $attempt) }}" id="examForm">
        @csrf
        @foreach ($answers as $a)
            <div class="list-group-item rounded p-4 mb-3 z-depth-1 shadow-none">
                <h5 class="mb-3"><span class="blue-text">{{ $a->position }}.</span> {{ $a->question->question }}</h5>
                {{-- Option order is stable per attempt but differs between attempts --}}
                @foreach ($a->question->options->sortBy(fn ($o) => crc32($attempt->id.'-'.$o->id)) as $o)
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="answers[{{ $a->exam_question_id }}]" id="o{{ $a->id }}_{{ $o->id }}" value="{{ $o->id }}">
                        <label class="form-check-label" for="o{{ $a->id }}_{{ $o->id }}">{{ $o->option_text }}</label>
                    </div>
                @endforeach
            </div>
        @endforeach
        <button type="submit" class="btn btn-primary" onclick="return confirm('Submit your exam? You cannot change your answers afterwards.');">Submit exam</button>
    </form>
    @if ($attempt->exam->time_limit_minutes)
    <script>
        setTimeout(function () { document.getElementById('examForm').submit(); },
            {{ max(1, $attempt->started_at->copy()->addMinutes($attempt->exam->time_limit_minutes)->diffInSeconds(now(), false) * -1) }} * 1000);
    </script>
    @endif
</div>
@endsection
