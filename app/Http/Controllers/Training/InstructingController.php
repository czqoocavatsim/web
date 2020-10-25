<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InstructingController extends Controller
{
    public function calendar()
    {
        return view('admin.training.instructing.calendar');
    }
}
