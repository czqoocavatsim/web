<?php

namespace App\Http\Controllers\Feedback;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function create()
    {
        return view('feedback.create');
    }

    public function createPost(Request $request)
    {
        //Define validator messages
        $messages = [
            'feedbackType.required' => 'You need to select a type of feedback.',
            'subject.required' => 
        ]
    }
}
