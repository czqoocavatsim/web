<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingPortalController extends Controller
{
    public function index()
    {
        //Is the user a student who needs to submit availability?
        if (Auth::user()->studentProfile && Auth::user()->studentProfile->current && count(Auth::user()->studentProfile->availability) < 1)
        {
            return view('training.portal.submit-availability');
        }
        return 'sad';
        return view('training.portal.index');
    }
}
