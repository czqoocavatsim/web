<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\Exams\Exam;
use App\Models\Training\Exams\ExamAttempt;
use App\Services\ExamService;
use Illuminate\Http\Request;

/** Student side of the entrance exam. */
class ExamController extends Controller
{
    private const SLUG = 'gander-oca-entrance-exam';

    private function exam(): Exam
    {
        return Exam::where('slug', self::SLUG)->firstOrFail();
    }

    private function ownAttempt(ExamAttempt $attempt): ExamAttempt
    {
        abort_unless($attempt->user_id === auth()->id(), 403);

        return $attempt;
    }

    public function show(ExamService $service)
    {
        $exam = $this->exam();
        $user = auth()->user();
        $attempts = $exam->attempts()->where('user_id', $user->id)->latest('started_at')->get();

        return view('training.portal.exam.show', [
            'exam' => $exam,
            'attempts' => $attempts,
            'blocked' => $service->blockedReason($user, $exam),
            'open' => $attempts->firstWhere('status', 'in_progress'),
        ]);
    }

    public function start(ExamService $service)
    {
        $exam = $this->exam();
        $user = auth()->user();
        $open = $exam->attempts()->where('user_id', $user->id)->where('status', 'in_progress')->first();
        if (! $open && ($reason = $service->blockedReason($user, $exam))) {
            return redirect()->route('training.exam.show')->with('error', $reason);
        }

        return redirect()->route('training.exam.take', $service->start($user, $exam));
    }

    public function take(ExamAttempt $attempt, ExamService $service)
    {
        $this->ownAttempt($attempt);
        if ($attempt->isFinished()) {
            return redirect()->route('training.exam.result', $attempt);
        }
        if ($service->hasExpired($attempt)) {
            $service->submit($attempt, []);

            return redirect()->route('training.exam.result', $attempt)->with('info', 'Your time ran out, so the exam was submitted.');
        }

        $answers = $attempt->answers()->with('question.options')->get();

        return view('training.portal.exam.take', compact('attempt', 'answers'));
    }

    public function submit(Request $request, ExamAttempt $attempt, ExamService $service)
    {
        $this->ownAttempt($attempt);
        $choices = collect($request->input('answers', []))->filter(fn ($v) => is_numeric($v))->all();
        $service->submit($attempt, $choices);

        return redirect()->route('training.exam.result', $attempt);
    }

    /** Only the outcome is shown: no answers or correct options, so the question bank stays private. */
    public function result(ExamAttempt $attempt)
    {
        $this->ownAttempt($attempt);
        if (! $attempt->isFinished()) {
            return redirect()->route('training.exam.take', $attempt);
        }

        return view('training.portal.exam.result', compact('attempt'));
    }
}
