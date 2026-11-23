@extends('training.portal.layouts.main')
@section('title', 'Entrance Exam - ')
@section('page-header-title', 'Entrance Exam')
@section('portal-content')
<div class="container py-4">
    @php $score = rtrim(rtrim(number_format($attempt->score_percent, 2), '0'), '.'); @endphp
    @if ($attempt->status === 'passed')
        <div class="list-group-item rounded p-4 z-depth-1 shadow-none white-text green">
            <h3 class="fw-600">You passed!</h3>
            <h5 class="fw-500 mb-0">{{ $attempt->correct_count }}/{{ $attempt->total_questions }} correct ({{ $score }}%). Pass mark {{ $attempt->pass_percentage }}%.</h5>
        </div>
        <p class="mt-4">Next, submit your availability so an instructor can pick you up.</p>
        <a href="{{ route('training.portal.availability') }}" class="btn btn-primary">Submit availability</a>
    @else
        <div class="list-group-item rounded p-4 z-depth-1 shadow-none white-text red">
            <h3 class="fw-600">You did not pass this time</h3>
            <h5 class="fw-500 mb-0">{{ $attempt->correct_count }}/{{ $attempt->total_questions }} correct ({{ $score }}%). Pass mark {{ $attempt->pass_percentage }}%.</h5>
        </div>
        <p class="mt-4">Review the Knowledge Base and try again when the retake period ends.</p>
    @endif
    <a href="{{ route('training.exam.show') }}" class="btn btn-outline-primary mt-2">Back to the exam page</a>
</div>
@endsection
