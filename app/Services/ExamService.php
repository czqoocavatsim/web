<?php

namespace App\Services;

use App\Models\Training\Exams\Exam;
use App\Models\Training\Exams\ExamAttempt;
use App\Models\Training\Exams\ExamAttemptAnswer;
use App\Models\Training\Instructing\Links\StudentStatusLabelLink;
use App\Models\Training\Instructing\Students\StudentStatusLabel;
use App\Models\Users\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Entrance exam rules, kept in one place so they can be tested without the web layer.
 */
class ExamService
{
    /** Why this user cannot start the exam right now, or null if they can. */
    public function blockedReason(User $user, Exam $exam): ?string
    {
        if (! $exam->active) {
            return 'This exam is not open yet.';
        }
        if (! $user->studentProfile || ! $user->studentProfile->current) {
            return 'The exam is only available to current Gander Oceanic students.';
        }
        if ($exam->askableQuestions()->isEmpty()) {
            return 'This exam has no questions yet. Please contact the training team.';
        }
        if ($exam->attempts()->where('user_id', $user->id)->where('status', 'passed')->exists()) {
            return 'You have already passed this exam.';
        }
        if ($next = $this->nextAttemptAt($user, $exam)) {
            if ($next->isFuture()) {
                return 'You can retake this exam '.$next->diffForHumans().' ('.$next->format('j M Y H:i').' UTC).';
            }
        }

        return null;
    }

    /** When the user may next attempt after failing: initial wait after the first fail, longer after later fails. */
    public function nextAttemptAt(User $user, Exam $exam): ?Carbon
    {
        $failed = $exam->attempts()->where('user_id', $user->id)->where('status', 'failed')->orderBy('submitted_at')->get();
        if ($failed->isEmpty()) {
            return null;
        }
        $days = $failed->count() === 1 ? $exam->initial_retake_days : $exam->subsequent_retake_days;

        return $failed->last()->submitted_at->copy()->addDays($days);
    }

    /** Resume an unfinished attempt or begin a new one. */
    public function start(User $user, Exam $exam): ExamAttempt
    {
        $open = $exam->attempts()->where('user_id', $user->id)->where('status', 'in_progress')->first();
        if ($open) {
            return $open;
        }

        return DB::transaction(function () use ($user, $exam) {
            $questions = $exam->askableQuestions();
            $limit = $exam->questions_to_ask;
            if ($limit > 0 && $questions->count() > $limit) {
                $required = $questions->where('required', true)->shuffle()->take($limit);
                $rest = $questions->where('required', false)->shuffle()->take($limit - $required->count());
                $questions = $required->concat($rest);
            }
            $questions = $questions->shuffle()->values();

            $attempt = ExamAttempt::create([
                'exam_id' => $exam->id,
                'user_id' => $user->id,
                'status' => 'in_progress',
                'started_at' => now(),
                'total_questions' => $questions->count(),
                'pass_percentage' => $exam->pass_percentage,
            ]);
            foreach ($questions as $i => $q) {
                ExamAttemptAnswer::create([
                    'exam_attempt_id' => $attempt->id,
                    'exam_question_id' => $q->id,
                    'position' => $i + 1,
                ]);
            }

            return $attempt;
        });
    }

    public function hasExpired(ExamAttempt $attempt): bool
    {
        $limit = $attempt->exam->time_limit_minutes;

        return $limit && ! $attempt->isFinished() && $attempt->started_at->copy()->addMinutes($limit)->isPast();
    }

    /**
     * Mark an attempt. $choices maps exam_question_id => exam_question_option_id.
     * Unanswered questions count as wrong. An already finished attempt is returned unchanged.
     */
    public function submit(ExamAttempt $attempt, array $choices): ExamAttempt
    {
        if ($attempt->isFinished()) {
            return $attempt;
        }

        DB::transaction(function () use ($attempt, $choices) {
            $correct = 0;
            foreach ($attempt->answers()->with('question.options')->get() as $answer) {
                $optionId = $choices[$answer->exam_question_id] ?? null;
                $option = $optionId ? $answer->question->options->firstWhere('id', (int) $optionId) : null;
                $isCorrect = $option ? (bool) $option->is_correct : false;
                $answer->update([
                    'exam_question_option_id' => $option ? $option->id : null,
                    'is_correct' => $isCorrect,
                ]);
                $correct += $isCorrect ? 1 : 0;
            }
            $total = max(1, (int) $attempt->total_questions);
            $score = round($correct / $total * 100, 2);
            $attempt->update([
                'status' => $score >= $attempt->pass_percentage ? 'passed' : 'failed',
                'submitted_at' => now(),
                'correct_count' => $correct,
                'score_percent' => $score,
            ]);
        });

        $attempt->refresh();
        if ($attempt->status === 'passed') {
            $this->afterPass($attempt);
        }
        $this->notifyStaff($attempt);

        return $attempt;
    }

    /** Discord DM to the staff listed in the exam settings (e.g. the Training Manager) for every result. Never affects the result. */
    public function notifyStaff(ExamAttempt $attempt): void
    {
        try {
            $exam = $attempt->exam;
            $cids = array_filter(array_map('intval', explode(',', (string) $exam->notify_cids)));
            if (! $cids) {
                return;
            }
            $user = $attempt->user;
            $passed = $attempt->status === 'passed';
            $number = ExamAttempt::where('exam_id', $exam->id)->where('user_id', $attempt->user_id)->where('id', '<=', $attempt->id)->count();
            $mins = $attempt->started_at && $attempt->submitted_at ? max(1, (int) round($attempt->started_at->diffInSeconds($attempt->submitted_at) / 60)) : null;
            $title = 'Entrance exam '.($passed ? 'PASSED' : 'FAILED').': '.$user->fullName('FLC');
            $body = 'Score: '.rtrim(rtrim(number_format($attempt->score_percent, 2), '0'), '.').'% ('.$attempt->correct_count.'/'.$attempt->total_questions.', pass mark '.$attempt->pass_percentage.'%)'
                ."\nAttempt: ".$number.($mins ? "\nTime taken: ".$mins.' min' : '')
                .($passed ? "\nStatus moved to Ready For Pick-Up." : "\nThey can retake from ".optional($this->nextAttemptAt($user, $exam))->format('j M Y').'.');
            $discord = new DiscordClient();
            foreach (User::whereIn('id', $cids)->whereNotNull('discord_user_id')->get() as $staff) {
                $discord->sendDM($staff->discord_user_id, $title, $body);
            }
        } catch (\Throwable $e) {
            Log::warning('Exam result: staff Discord notification failed: '.$e->getMessage());
        }
    }

    /** Awaiting Exam -> Ready For Pick-Up, then tell Discord. Failures here never undo the result. */
    public function afterPass(ExamAttempt $attempt): void
    {
        try {
            $user = $attempt->user;
            $student = $user->studentProfile;
            $awaiting = StudentStatusLabel::whereName('Awaiting Exam')->first();
            $ready = StudentStatusLabel::whereName('Ready For Pick-Up')->first();
            if (! $student || ! $awaiting || ! $ready || ! $student->hasLabel('Awaiting Exam')) {
                return;
            }
            StudentStatusLabelLink::where('student_id', $student->id)->where('student_status_label_id', $awaiting->id)->delete();
            if (! $student->hasLabel('Ready For Pick-Up')) {
                $student->assignStatusLabel($ready);
            }
        } catch (\Throwable $e) {
            Log::warning('Exam pass: could not update status label: '.$e->getMessage());

            return;
        }

        try {
            if ($user->hasDiscord() && $user->member_of_czqo) {
                $discord = new DiscordClient();
                $discord->EditThreadTag('Ready For Pick-Up', $user->id);
                $discord->sendEmbedInTrainingThread($user->id, 'Entrance exam passed', 'Passed the entrance exam with '.rtrim(rtrim(number_format($attempt->score_percent, 2), '0'), '.').'%. Please submit your availability so an instructor can pick you up.');
                $discord->sendMessageWithEmbed(intval(config('services.discord.instructors')), 'A new student is available for pick-up by an Instructor', $user->fullName('FLC').' has passed the entrance exam and is available to be picked up!');
            }
        } catch (\Throwable $e) {
            Log::warning('Exam pass: Discord update failed: '.$e->getMessage());
        }
    }
}
