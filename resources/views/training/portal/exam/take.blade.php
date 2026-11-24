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

        {{-- Review screen: shown after the last question, before anything is submitted --}}
        <div id="examReview" class="list-group-item rounded p-4 mb-3 z-depth-1 shadow-none" style="display:none">
            <h5 class="mb-1">Review your answers</h5>
            <p class="grey-text mb-3" id="examReviewSummary"></p>
            <div id="examReviewList"></div>
        </div>

        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-outline-primary" id="examPrev">Previous</button>
            <div>
                <button type="button" class="btn btn-primary" id="examNext">Next</button>
                <button type="submit" class="btn btn-success" id="examSubmit" style="display:none" onclick="return confirm(examSubmitMessage());">Submit exam</button>
            </div>
        </div>
    </form>
    <script>
        (function () {
            var qs = Array.prototype.slice.call(document.querySelectorAll('.exam-q'));
            var cur = 0, total = qs.length, reviewing = false;
            var prev = document.getElementById('examPrev'), next = document.getElementById('examNext'), sub = document.getElementById('examSubmit');
            var review = document.getElementById('examReview');
            function answered() { return qs.filter(function (q) { return q.querySelector('input:checked'); }).length; }
            function renderReview() {
                var left = total - answered(), list = document.getElementById('examReviewList');
                document.getElementById('examReviewSummary').textContent = left
                    ? left + ' of ' + total + ' questions are unanswered. Click a question to go back and answer it.'
                    : 'All ' + total + ' questions are answered. Click a question to change your answer, or submit when you are ready.';
                list.innerHTML = '';
                qs.forEach(function (q, i) {
                    var c = q.querySelector('input:checked'), row = document.createElement('div');
                    row.className = 'd-flex justify-content-between align-items-start py-2 border-bottom';
                    var txt = document.createElement('div');
                    var h = document.createElement('div'); h.className = 'font-weight-bold'; h.textContent = (i + 1) + '. ' + q.querySelector('h5').textContent.replace(/^\s*\d+\.\s*/, '');
                    var ans = document.createElement('div');
                    ans.textContent = c ? c.nextElementSibling.textContent : 'Not answered';
                    ans.className = c ? 'grey-text' : 'red-text';
                    txt.appendChild(h); txt.appendChild(ans);
                    var btn = document.createElement('button'); btn.type = 'button'; btn.className = 'btn btn-sm btn-outline-primary ml-3'; btn.textContent = 'Change';
                    btn.addEventListener('click', function () { cur = i; reviewing = false; render(); });
                    row.appendChild(txt); row.appendChild(btn); list.appendChild(row);
                });
            }
            function render() {
                qs.forEach(function (q, i) { q.style.display = (!reviewing && i === cur) ? '' : 'none'; });
                review.style.display = reviewing ? '' : 'none';
                document.getElementById('examCounter').textContent = reviewing ? 'Review' : 'Question ' + (cur + 1) + ' of ' + total;
                document.getElementById('examAnswered').textContent = answered() + ' of ' + total + ' answered';
                document.getElementById('examBar').style.width = (reviewing ? 100 : (cur + 1) / total * 100) + '%';
                prev.style.visibility = (!reviewing && cur === 0) ? 'hidden' : 'visible';
                var last = cur === total - 1;
                next.style.display = reviewing ? 'none' : '';
                next.textContent = last ? 'Review answers' : 'Next';
                sub.style.display = reviewing ? '' : 'none';
                if (reviewing) renderReview();
                window.scrollTo(0, 0);
            }
            window.examSubmitMessage = function () {
                var left = total - answered();
                return (left ? left + ' question(s) are unanswered. ' : '') + 'Submit your exam? You cannot change your answers afterwards.';
            };
            prev.addEventListener('click', function () {
                if (reviewing) { reviewing = false; cur = total - 1; } else if (cur > 0) { cur--; }
                render();
            });
            next.addEventListener('click', function () {
                if (cur < total - 1) { cur++; } else { reviewing = true; }
                render();
            });
            qs.forEach(function (q) { q.addEventListener('change', function () { document.getElementById('examAnswered').textContent = answered() + ' of ' + total + ' answered'; }); });
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
