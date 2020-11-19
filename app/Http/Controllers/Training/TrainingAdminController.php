<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\Application;
use Illuminate\Http\Request;

class TrainingAdminController extends Controller
{
    public function dashboard()
    {
        //Get applications
        $applications = Application::where('status', 0)->get()->sortBy('created_at');

        return view('admin.training.dashboard', compact('applications'));
    }
}
