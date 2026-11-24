@extends('training.portal.layouts.main')
@section('title', 'Entrance Exam - ')
@section('page-header-title', 'Entrance Exam')
@section('portal-content')
<div class="container py-4">
    <h3 class="blue-text mb-3">{{ $exam->name }}</h3>
    <div class="list-group-item rounded p-4 mb-4 z-depth-1 shadow-none">
        <ul class="mb-0">
            <li>{{ $exam->questions_to_ask > 0 ? $exam->questions_to_ask : 'All' }} multiple-choice questions, one correct answer each.</li>
            <li>Pass mark: {{ $exam->pass_percentage }}%.</li>
            @if ($exam->time_limit_minutes)<li>Time limit: {{ $exam->time_limit_minutes }} minutes once you start.</li>@endif
            <li>You will see your result when you submit. Answers are not shown afterwards.</li>
            <li>If you do not pass, you can retake after {{ $exam->initial_retake_days }} day(s), and after {{ $exam->subsequent_retake_days }} days for any later retake.</li>
        </ul>
    </div>

    @if ($open)
        <a href="{{ route('training.exam.take', $open) }}" class="btn btn-primary">Continue your exam</a>
    @elseif ($blocked)
        <div class="alert alert-info">{{ $blocked }}</div>
    @else
        <form method="POST" action="{{ route('training.exam.start') }}">
            @csrf
            <button class="btn btn-primary" type="submit">Start the exam</button>
        </form>
    @endif

    @if ($attempts->count())
        <h4 class="blue-text mt-5 mb-3">Your attempts</h4>
        <table class="table table-bordered">
            <thead><tr><th>Date</th><th>Result</th><th>Score</th></tr></thead>
            <tbody>
            @foreach ($attempts as $a)
                <tr>
                    <td>{{ $a->started_at->format('j M Y H:i') }} UTC</td>
                    <td>{{ $a->status === 'in_progress' ? 'In progress' : ucfirst($a->status) }}</td>
                    <td>{{ $a->isFinished() ? $a->correct_count.'/'.$a->total_questions.' ('.rtrim(rtrim(number_format($a->score_percent, 2), '0'), '.').'%)' : '-' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
