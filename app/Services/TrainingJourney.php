<?php

namespace App\Services;

use App\Models\Training\Application;
use App\Models\Training\Exams\ExamAttempt;
use App\Models\Users\User;

/**
 * Works out where a person is on the path from application to Oceanic endorsement.
 * Each step is ['key', 'title', 'state' => done|current|todo|failed, 'note'].
 */
class TrainingJourney
{
    public function steps(User $user): array
    {
        $app = Application::where('user_id', $user->id)->whereIn('status', [0, 1, 2])->latest('created_at')->first();
        $student = $user->studentProfile;
        $isStudent = $student && $student->current;
        $roster = $user->rosterProfile;
        $certified = $roster && strtolower((string) $roster->certification) === 'certified';
        $refused = $app && (int) $app->status === 2 && ! $isStudent && ! $certified;

        $accepted = $certified || $isStudent || ($app && (int) $app->status === 1);
        $examPassed = $certified
            || ExamAttempt::where('user_id', $user->id)->where('status', 'passed')->exists()
            // Students who sat the old VATCAN exam have no attempt here; they have moved past "Awaiting Exam".
            || ($isStudent && ! $student->hasLabel('Awaiting Exam'));

        $s = [];
        $s[] = ['key' => 'application', 'title' => 'Application', 'state' => $app || $accepted ? 'done' : 'current',
            'note' => $app || $accepted ? 'Submitted' : 'Apply for Gander Oceanic certification.'];
        $s[] = ['key' => 'review', 'title' => 'Reviewed by senior staff', 'state' => ! ($app || $accepted) ? 'todo' : ($accepted || $refused ? 'done' : 'current'),
            'note' => ($app && (int) $app->status === 0) ? 'Waiting for a decision.' : ''];
        $s[] = ['key' => 'decision', 'title' => $refused ? 'Refused' : 'Accepted', 'state' => $refused ? 'failed' : ($accepted ? 'done' : 'todo'),
            'note' => $refused ? 'You can reapply when you are ready.' : ''];
        $s[] = ['key' => 'exam', 'title' => 'Entrance exam', 'state' => $refused || ! $accepted ? 'todo' : ($examPassed ? 'done' : 'current'),
            'note' => ''];
        $s[] = ['key' => 'session', 'title' => 'Live session with an instructor', 'state' => $refused || ! $examPassed ? 'todo' : ($certified ? 'done' : 'current'),
            'note' => ''];
        $s[] = ['key' => 'endorsement', 'title' => 'Oceanic endorsement', 'state' => $certified ? 'done' : 'todo', 'note' => ''];

        return $s;
    }
}
