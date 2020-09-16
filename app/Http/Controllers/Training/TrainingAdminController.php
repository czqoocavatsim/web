<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TrainingAdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.training.dashboard');
    }
}
