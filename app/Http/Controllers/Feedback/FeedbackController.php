<?php

namespace App\Http\Controllers\Feedback;

use App\Http\Controllers\Controller;
use App\Models\Feedback\FeedbackSubmission;
use App\Models\Feedback\FeedbackType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use LasseRafn\Initials\Initials;

class FeedbackController extends Controller
{
    /**
     * GET request for starting a new feedback submission.
     *
     * @return \Illuminate\View\View
     */
    public function newFeedback()
    {
        //Get all feedback types
        $types = FeedbackType::all()->sortBy('name');

        //Return view
        return view('my.feedback.new', compact('types'));
    }

    /**
     * GET request for starting a new feedback submission of a specific type.
     *
     * @param string $type_slug Slug for the type of feedback.
     * @return \Illuminate\View\View
     */
    public function newFeedbackWrite($type_slug)
    {
        //Get feedback type
        $type = FeedbackType::whereSlug($type_slug)->firstOrFail();

        //Return view
        return view('my.feedback.new-write', compact('type'));
    }

    public function newFeedbackWritePost(Request $request, $type_slug)
    {
        //Get feedback type
        $type = FeedbackType::whereSlug($type_slug)->firstOrFail();

        //Define validator messages
        $messages = [
            'submission_content.required' => 'Please write your feedback.'
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'submission_content' => 'required'
        ], $messages);

        //Redirect if it fails
        if ($validator->fails()) {
            return redirect()->route('my.feedback.new.write', $type->slug)->withInput()->withErrors($validator, 'newFeedbackErrors');
        }

        //Create feedback
        $feedback = new FeedbackSubmission([
            'user_id' => Auth::id(),
            'type_id' => $type->id,
            'submission_content' => $request->get('submission_content'),
            'permission_to_publish' => $request->get('publishPermission') == 'on' ? true : false,
            'slug' => Str::slug(new Initials(Auth::user()->fullName('FL')) . '-' . Carbon::now()->toDayDateTimeString()),
        ]);
        $feedback->save();

        dd($feedback);
    }
}
