<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Services\StudentTimeline;
use App\Services\TrainingJourney;

class StudentPageController extends Controller
{
    public function show(TrainingJourney $journey, StudentTimeline $timeline)
    {
        $user = auth()->user();
        $student = $user->studentProfile;
        $assignment = $student ? $student->instructor() : null;

        return view('training.portal.me', [
            'user' => $user,
            'student' => $student,
            'roster' => $user->rosterProfile,
            'instructor' => $assignment ? $assignment->instructor : null,
            'steps' => $journey->steps($user),
            'events' => $timeline->events($user),
            'discord' => config('training.discord_invite'),
        ]);
    }
}
