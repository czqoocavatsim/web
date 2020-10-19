<?php

namespace App\Http\Controllers\Publications;

use App\Http\Controllers\Controller;
use App\Models\Publications\CustomPage;
use App\Models\Publications\CustomPageResponse;
use App\Notifications\CustomPages\ResponseCopy;
use App\Notifications\CustomPages\ResponseReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class CustomPagesController extends Controller
{
    /*
    Public
    */
    public function viewPage($page_slug)
    {
        //Get the page
        $page = CustomPage::where('slug', $page_slug)->firstOrFail();

        //Permissions
        if (count($page->permissions) > 0)
        {
            dd($page->permissions);
        }
        else
        {
            //Return the view
            return view('publications/custom-page', compact('page'));
        }
    }

    //Repsonse submission
    public function submitResponse($page_slug, Request $request)
    {
        //Get the page
        $page = CustomPage::where('slug', $page_slug)->firstOrFail();

        //Validate
        $validator = Validator::make($request->all(), [
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed'], 400);
        }

        //Create response object
        $response = new CustomPageResponse();
        $response->page_id = $page->id;
        $response->user_id = Auth::id();
        $response->content = $request->get('content');
        $response->save();

        //Send copy to user
        $response->user->notify(new ResponseCopy($response));
        Notification::route('mail', $page->response_form_email)->notify(new ResponseReceived($response));

        //Response
        return response()->json(['message' => 'Sent'], 200);
    }
}
