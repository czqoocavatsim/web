<?php

namespace Tests\Unit;

use App\Models\Training\Exams\Exam;
use App\Models\Training\Exams\ExamQuestion;
use App\Models\Users\User;
use App\Services\ExamService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ExamServiceTest extends TestCase
{
    private ExamService $service;
    private Exam $exam;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        require_once __DIR__.'/../../database/migrations/2026_11_23_000000_create_exam_centre_tables.php';
        (new \CreateExamCentreTables())->up();

        $this->service = new ExamService();
        $this->exam = Exam::first();
        $this->exam->update(['active' => true, 'questions_to_ask' => 3]);
        $this->user = new User();
        $this->user->id = 1234567;

        for ($i = 1; $i <= 5; $i++) {
            $q = $this->exam->questions()->create(['question' => "Q$i", 'required' => $i === 1, 'active' => true]);
            foreach (['right', 'wrong a', 'wrong b'] as $n => $text) {
                $q->options()->create(['option_text' => $text, 'is_correct' => $n === 0]);
            }
        }
        // Never asked: no correct answer set.
        $bad = $this->exam->questions()->create(['question' => 'Broken', 'required' => true, 'active' => true]);
        $bad->options()->create(['option_text' => 'a', 'is_correct' => false]);
        $bad->options()->create(['option_text' => 'b', 'is_correct' => false]);
    }

    private function answerAll($attempt, bool $correct, int $howMany = 99): array
    {
        $choices = [];
        foreach ($attempt->answers()->with('question.options')->get() as $i => $a) {
            if ($i >= $howMany) {
                break;
            }
            $choices[$a->exam_question_id] = $a->question->options->firstWhere('is_correct', $correct)->id;
        }

        return $choices;
    }

    public function test_paper_has_requested_size_includes_required_and_skips_unanswerable_questions()
    {
        $attempt = $this->service->start($this->user, $this->exam);
        $ids = $attempt->answers->pluck('exam_question_id');
        $this->assertCount(3, $ids);
        $this->assertTrue($ids->contains(ExamQuestion::where('question', 'Q1')->first()->id));
        $this->assertFalse($ids->contains(ExamQuestion::where('question', 'Broken')->first()->id));
    }

    public function test_starting_twice_resumes_the_same_attempt()
    {
        $a = $this->service->start($this->user, $this->exam);
        $b = $this->service->start($this->user, $this->exam);
        $this->assertSame($a->id, $b->id);
    }

    public function test_pass_mark_is_80_percent()
    {
        $this->exam->update(['questions_to_ask' => 5]);
        $attempt = $this->service->start($this->user, $this->exam);
        // 4 of 5 right = 80% = pass
        $choices = $this->answerAll($attempt, true, 4);
        $done = $this->service->submit($attempt, $choices);
        $this->assertSame('passed', $done->status);
        $this->assertEquals(80, $done->score_percent);
    }

    public function test_below_pass_mark_fails_and_unanswered_counts_as_wrong()
    {
        $this->exam->update(['questions_to_ask' => 5]);
        $attempt = $this->service->start($this->user, $this->exam);
        $done = $this->service->submit($attempt, $this->answerAll($attempt, true, 3));
        $this->assertSame('failed', $done->status);
        $this->assertSame(3, $done->correct_count);
    }

    public function test_finished_attempt_cannot_be_resubmitted()
    {
        $attempt = $this->service->start($this->user, $this->exam);
        $this->service->submit($attempt, $this->answerAll($attempt, false));
        $again = $this->service->submit($attempt, $this->answerAll($attempt, true));
        $this->assertSame('failed', $again->status);
    }

    public function test_retake_waits_one_day_after_first_fail_then_seven()
    {
        Carbon::setTestNow('2026-11-23 12:00:00');
        $a = $this->service->start($this->user, $this->exam);
        $this->service->submit($a, []);
        $this->assertEquals('2026-11-24 12:00:00', $this->service->nextAttemptAt($this->user, $this->exam)->toDateTimeString());

        Carbon::setTestNow('2026-11-24 12:00:01');
        $b = $this->service->start($this->user, $this->exam);
        $this->service->submit($b, []);
        $this->assertEquals('2026-12-01 12:00:01', $this->service->nextAttemptAt($this->user, $this->exam)->toDateTimeString());
        Carbon::setTestNow();
    }

    public function test_exam_is_seeded_with_a_60_minute_limit_and_expires_after_it()
    {
        $this->assertSame(60, (int) $this->exam->time_limit_minutes);
        $this->exam->update(['active' => true]);
        $attempt = $this->service->start($this->user, $this->exam);
        $this->assertFalse($this->service->hasExpired($attempt));
        $attempt->started_at = Carbon::now()->subMinutes(61);
        $attempt->save();
        $this->assertTrue($this->service->hasExpired($attempt->fresh()));
    }

    public function test_staff_notification_never_breaks_a_result()
    {
        // No recipients set: nothing happens
        $this->exam->update(['active' => true, 'notify_cids' => null]);
        $a = $this->service->start($this->user, $this->exam);
        $done = $this->service->submit($a, []);
        $this->assertSame('failed', $done->status);

        // Recipients set but no Discord token/linked accounts: still returns the result, no exception
        $this->exam->update(['notify_cids' => '1000001,1000002']);
        Carbon::setTestNow(Carbon::now()->addDays(2));
        $b = $this->service->start($this->user, $this->exam);
        $this->assertSame('failed', $this->service->submit($b, [])->status);
        Carbon::setTestNow();
    }
}
