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
        {{-- One question is shown at a time. All answers are still posted together at the end. --}}
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="grey-text" id="examCounter"></span>
            <span class="grey-text" id="examAnswered"></span>
        </div>
        <div class="progress mb-3" style="height:6px"><div class="progress-bar" id="examBar" role="progressbar" style="width:0%"></div></div>

        @foreach ($answers as $a)
            <div class="exam-q list-group-item rounded p-4 mb-3 z-depth-1 shadow-none" data-index="{{ $loop->index }}" style="{{ $loop->first ? '' : 'display:none' }}">
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

        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-outline-primary" id="examPrev">Previous</button>
            <div>
                <button type="button" class="btn btn-primary" id="examNext">Next</button>
                <button type="submit" class="btn btn-success" id="examSubmit" style="display:none" onclick="return confirm(examSubmitMessage());">Submit exam</button>
            </div>
        </div>
        <p class="mt-3 mb-0 grey-text" id="examUnanswered" style="display:none"></p>
    </form>
    <script>
        (function () {
            var qs = Array.prototype.slice.call(document.querySelectorAll('.exam-q'));
            var cur = 0, total = qs.length;
            var prev = document.getElementById('examPrev'), next = document.getElementById('examNext'), sub = document.getElementById('examSubmit');
            function answered() { return qs.filter(function (q) { return q.querySelector('input:checked'); }).length; }
            function render() {
                qs.forEach(function (q, i) { q.style.display = i === cur ? '' : 'none'; });
                document.getElementById('examCounter').textContent = 'Question ' + (cur + 1) + ' of ' + total;
                document.getElementById('examAnswered').textContent = answered() + ' answered';
                document.getElementById('examBar').style.width = ((cur + 1) / total * 100) + '%';
                prev.style.visibility = cur === 0 ? 'hidden' : 'visible';
                var last = cur === total - 1;
                next.style.display = last ? 'none' : '';
                sub.style.display = last ? '' : 'none';
                var left = total - answered(), note = document.getElementById('examUnanswered');
                note.style.display = last && left ? '' : 'none';
                note.textContent = left + ' question' + (left === 1 ? ' is' : 's are') + ' still unanswered. Use Previous to go back to them.';
                window.scrollTo(0, 0);
            }
            window.examSubmitMessage = function () {
                var left = total - answered();
                return (left ? left + ' question(s) are unanswered. ' : '') + 'Submit your exam? You cannot change your answers afterwards.';
            };
            prev.addEventListener('click', function () { if (cur > 0) { cur--; render(); } });
            next.addEventListener('click', function () { if (cur < total - 1) { cur++; render(); } });
            qs.forEach(function (q) { q.addEventListener('change', function () { document.getElementById('examAnswered').textContent = answered() + ' answered'; }); });
            render();
        })();
    </script>
    @if ($attempt->exam->time_limit_minutes)
    <script>
        setTimeout(function () { document.getElementById('examForm').submit(); },
            {{ max(1, $attempt->started_at->copy()->addMinutes($attempt->exam->time_limit_minutes)->diffInSeconds(now(), false) * -1) }} * 1000);
    </script>
    @endif
</div>
@endsection
