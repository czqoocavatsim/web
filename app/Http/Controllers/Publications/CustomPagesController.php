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
            if (!Auth::check()) {
                return redirect()->route('auth.connect.login');
            }

            $hasPermission = false;

            foreach ($page->permissions as $perm)
            {
                $role = $perm->role;
                if (Auth::user()->hasRole($role) || Auth::user()->hasAnyRole('Senior Staff|Administrator')) {
                    $hasPermission = true;
                }
            }

            if (!$hasPermission) {
                abort(403, 'Insufficent role to access page. If you believe you should have access, contact the Webmaster.');
            }
        }

        //Return the view
        return view('publications/custom-page', compact('page'));
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

    /*
    Admin
    */
    public function admin()
    {
        //Get all custom pages
        $pages = CustomPage::all();

        //Return view
        return view('admin.publications.custom-pages.index', compact('pages'));
    }

    public function adminViewPage($page_slug)
    {
        //Find page
        $page = CustomPage::where('slug', $page_slug)->firstOrFail();

        //Return view
        return view('admin.publications.custom-pages.view', compact('page'));
    }
}
