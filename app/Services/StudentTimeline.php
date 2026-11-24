<?php

namespace App\Services;

use App\Models\Training\Application;
use App\Models\Training\Exams\ExamAttempt;
use App\Models\Training\Instructing\Records\StudentNote;
use App\Models\Training\Instructing\Records\TrainingSession;
use App\Models\Users\User;
use Carbon\Carbon;

/**
 * Builds the list of everything that has happened on a person's path, newest first.
 * Each event is ['at' => Carbon, 'icon' => fontawesome class, 'colour' => class, 'title' => string, 'detail' => string].
 * Only the student's own records are used, and staff-only notes are never included.
 */
class StudentTimeline
{
    public function events(User $user): array
    {
        $e = [];
        $student = $user->studentProfile;

        foreach (Application::where('user_id', $user->id)->get() as $app) {
            $e[] = $this->ev($app->created_at, 'fa-file-signature', 'blue-text', 'Application submitted', 'Reference #'.$app->reference_id);
            if ((int) $app->status === 1) {
                $e[] = $this->ev($app->updated_at, 'fa-check-circle', 'green-text', 'Application accepted', 'Reviewed and accepted by senior staff.');
            } elseif ((int) $app->status === 2) {
                $e[] = $this->ev($app->updated_at, 'fa-times-circle', 'red-text', 'Application refused', 'You can reapply when you are ready.');
            } elseif ((int) $app->status === 3) {
                $e[] = $this->ev($app->updated_at, 'fa-ban', 'text-muted', 'Application withdrawn', '');
            }
        }

        if ($student) {
            $e[] = $this->ev($student->created_at, 'fa-graduation-cap', 'blue-text', 'Became a student', 'Your training started.');

            foreach ($student->labels as $link) {
                $name = optional($link->label())->name;
                if ($name) {
                    $e[] = $this->ev($link->created_at, 'fa-tag', 'text-muted', 'Status: '.$name, '');
                }
            }

            $assignment = $student->instructor();
            if ($assignment) {
                $who = optional(optional($assignment->instructor)->user)->fullName('FL');
                $e[] = $this->ev($assignment->created_at, 'fa-user-tie', 'blue-text', 'Instructor assigned', $who ?: '');
            }

            foreach ($student->availability as $a) {
                $e[] = $this->ev($a->created_at, 'fa-clock', 'text-muted', 'Availability submitted', '');
            }

            foreach ($student->trainingSessions as $s) {
                $past = $s->scheduled_time->isPast();
                $who = optional(optional($s->instructor)->user)->fullName('FL');
                $e[] = $this->ev($s->scheduled_time, 'fa-headset', $past ? 'green-text' : 'blue-text',
                    $past ? 'Live session' : 'Live session booked', $who ? 'With '.$who : '');
            }

            foreach (StudentNote::where('student_id', $student->id)->where('staff_only', false)->get() as $n) {
                $e[] = $this->ev($n->created_at, 'fa-sticky-note', 'text-muted', 'Note from your instructor', '');
            }
        }

        foreach (ExamAttempt::where('user_id', $user->id)->whereIn('status', ['passed', 'failed'])->get() as $a) {
            $pass = $a->status === 'passed';
            $e[] = $this->ev($a->submitted_at ?: $a->started_at, $pass ? 'fa-clipboard-check' : 'fa-clipboard',
                $pass ? 'green-text' : 'red-text', $pass ? 'Entrance exam passed' : 'Entrance exam attempted',
                round($a->score_percent).'% (pass mark '.$a->pass_percentage.'%)');
        }

        $roster = $user->rosterProfile;
        if ($roster && strtolower((string) $roster->certification) === 'certified') {
            $e[] = $this->ev($roster->getRawOriginal('date_certified') ? Carbon::parse($roster->getRawOriginal('date_certified')) : $roster->updated_at,
                'fa-award', 'green-text', 'Oceanic endorsement awarded', 'You are on the Gander Oceanic roster.');
        }

        return $this->compose($e);
    }

    /** Drops events with no date and sorts newest first. */
    public function compose(array $events): array
    {
        $events = array_values(array_filter($events, fn ($x) => $x['at'] !== null));
        usort($events, fn ($a, $b) => $b['at'] <=> $a['at']);

        return $events;
    }

    private function ev($at, string $icon, string $colour, string $title, string $detail): array
    {
        return ['at' => $at ? Carbon::parse($at) : null, 'icon' => $icon, 'colour' => $colour, 'title' => $title, 'detail' => $detail];
    }
}
