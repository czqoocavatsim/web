<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Services\TrainingJourney;

class TrainingHubController extends Controller
{
    public function index(TrainingJourney $journey)
    {
        $user = auth()->user();

        return view('training.portal.hub', [
            'steps' => $journey->steps($user),
            'links' => config('training.links'),
            'videos' => config('training.videos'),
            'isStudent' => $user->studentProfile && $user->studentProfile->current,
        ]);
    }
}
