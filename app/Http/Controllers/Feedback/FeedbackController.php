<?php

namespace App\Http\Controllers\Feedback;

use App\Http\Controllers\Controller;
use App\Models\Feedback\FeedbackSubmission;
use App\Models\Feedback\FeedbackType;
use App\Models\Feedback\FeedbackTypeFieldSubmission;
use App\Notifications\Feedback\NewFeedbackStaff;
use Carbon\Carbon;
use App\Services\DiscordClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
     *
     * @return \Illuminate\View\View
     */
    public function newFeedbackWrite($type_slug)
    {
        //Get feedback type
        $type = FeedbackType::whereSlug($type_slug)->firstOrFail();

        //Return view
        return view('my.feedback.new-write', compact('type'));
    }

    /**
     * POST request for submitting new feedback of a speific type.
     *
     * @param Request $request
     * @param string  $type_slug
     *
     * @return redirect
     */
    public function newFeedbackWritePost(Request $request, $type_slug)
    {

        //Get feedback type
        $type = FeedbackType::whereSlug($type_slug)->firstOrFail();

        //Define validator messages
        $messages = [
            'submission_content.required' => 'Please write your feedback.',
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'submission_content' => 'required',
        ], $messages);

        //Redirect if it fails
        if ($validator->fails()) {
            return redirect()->route('my.feedback.new.write', $type->slug)->withInput()->withErrors($validator, 'newFeedbackErrors');
        }

        // Create a unique Slug that is not in the DB yet
        do {
            $slug = Str::random(10);
            $check_slug = FeedbackSubmission::where('slug', $slug)->first();
        } while ($check_slug);

        //Create feedback
        $feedback = new FeedbackSubmission([
            'user_id'               => Auth::id(),
            'type_id'               => $type->id,
            'submission_content'    => $request->get('submission_content'),
            'permission_to_publish' => $request->get('publishPermission') == 'on' ? true : false,
            'slug'                  => $slug,
            'status'                => 0,
        ]);
        $feedback->save();

        //Deal with fields
        foreach ($type->fields as $field) {
            $fieldSubmission = new FeedbackTypeFieldSubmission([
                'type_id'       => $type->id,
                'submission_id' => $feedback->id,
                'name'          => $field->name,
                'content'       => $request->get($field->id),
            ]);
            $fieldSubmission->save();
        }

        //Notify those in role
        if ($type->role->users) {
            $staffToNotify = $type->role->users;
            foreach ($staffToNotify as $user) {
                $user->notify(new NewFeedbackStaff($feedback));
            }
        }

        // Discord Notification Section
        $discord = new DiscordClient();
        $main_feedback = FeedbackSubmission::all()->where('slug', $feedback->slug)->first();
        $fields_feedback = FeedbackTypeFieldSubmission::all()->where('submission_id', $main_feedback->id);
        
        // Compose the message
        $message_content = "New feedback has just been received! \n";

        if($fields_feedback !== null){
            foreach($fields_feedback as $ff){
                $message_content .= "## ".$ff->name."\n";
                $message_content .= " - ".$ff->content."\n";
            }
        }

        $message_content .= "## Feedback Information\n";
        $message_content .= " - " . (strlen($main_feedback->submission_content) > 200 ? substr($main_feedback->submission_content, 0, 200) . "..." : $main_feedback->submission_content) . "\n\n";
        
        $message_content .= "## Submitter & Feedback Link\n";
        $message_content .= " - ".$main_feedback->user->fullName('FLC')."\n";
        $message_content .= " - [Feedback Link](".route('my.feedback.submission', $main_feedback->slug).")";

        // Send the Announcement
        $discord->sendMessageWithEmbed(1324401086592978955, 'New Feedback Recieved! - '.$type->name, $message_content);

        //Return
        return redirect()->route('my.feedback.submission', $feedback->slug)->with('success', 'Feedback submitted! Thank you for helping improve Gander Oceanic.');
    }

    /**
     * GET request to view a feedback submission.
     *
     * @param string $slug
     *
     * @return view
     */
    public function viewSubmission($slug)
    {
        //Get feedback submission
        $submission = FeedbackSubmission::whereSlug($slug)->firstOrFail();

        //Return view
        return view('my.feedback.submission', compact('submission'));
    }

    public function myFeedback()
    {
        //Get all submissions belonging to user
        $submissions = FeedbackSubmission::where('user_id', Auth::id())->get()->sortByDesc('created_at');

        //Return view
        return view('my.feedback.index', compact('submissions'));
    }
}
