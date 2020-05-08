<?php

namespace App\Http\Controllers\Feedback;

use App\Http\Controllers\Controller;
use App\Models\Feedback\ControllerFeedback;
use App\Models\Feedback\OperationsFeedback;
use App\Models\Feedback\WebsiteFeedback;
use App\Models\Settings\CoreSettings;
use App\Notifications\Feedback\NewControllerFeedback;
use App\Notifications\Feedback\NewOperationsFeedback;
use App\Notifications\Feedback\NewWebsiteFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    public function create()
    {
        return view('feedback.create');
    }

    public function createPost(Request $request)
    {
        //dd($request->all());
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
            if ($request->get('controllerCid') == null) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('controllerCid', 'You need to provide a controller CID');
                });
            }
        } else /*Otherwise*/ {
            //No subject
            if ($request->get('subject') == null) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('subject', 'You need to fill in the subject field.');
                });
            }
        }
        //dd($validator->errors());
        //Redirect if fails
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator, 'createFeedbackErrors');
        }

        //Otherwise...
        switch ($request->get('feedbackType'))
        {
            case "website":
                $feedback = new WebsiteFeedback([
                    'user_id' => Auth::id(),
                    'subject' => $request->get('subject'),
                    'content' => $request->get('content')
                ]);
                $feedback->save();
                Notification::route('mail', CoreSettings::find(1)->emailwebmaster)->notify(new NewWebsiteFeedback($feedback));
                break;
            case "operations":
                $feedback = new OperationsFeedback([
                    'user_id' => Auth::id(),
                    'subject' => $request->get('subject'),
                    'content' => $request->get('content')
                ]);
                $feedback->save();
                Notification::route('mail', CoreSettings::find(1)->emailfacilitye)->notify(new NewOperationsFeedback($feedback));
                break;
            case "controller":
                $feedback = new ControllerFeedback([
                    'user_id' => Auth::id(),
                    'controller_cid' => $request->get('controllerCid'),
                    'content' => $request->get('content')
                ]);
                $feedback->save();
                Notification::route('mail', CoreSettings::find(1)->emailfirchief)->notify(new NewControllerFeedback($feedback));
                Notification::route('mail', CoreSettings::find(1)->emaildepfirchief)->notify(new NewControllerFeedback($feedback));
                break;
        }
        return view('feedback.sent');
    }
}
