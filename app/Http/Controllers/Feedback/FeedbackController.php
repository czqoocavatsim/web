<?php

namespace App\Http\Controllers\Feedback;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            'feedbackType.required' => 'You need to select a type of feedback.'
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'feedbackType' => 'required',
            'content' => 'required',
        ], $messages);

        //If it's controller feedback then...
        if ($request->get('feedbackType') == 'controller') {
            //If they dont have the controller CID
            if (!$request->get('controllerCid')) {
                $validator->errors()->add('controllerCid', 'You need to provide a controller CID');
            }
        } else /*Otherwise*/ {
            //No subject
            if (!$request->get('subject')) {
                $validator->errors()->add('subject', 'You need to fill in the subject field.');
            }
        }

        //Redirect if fails
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator, 'createFeedbackErrors');
        }
    }
}
