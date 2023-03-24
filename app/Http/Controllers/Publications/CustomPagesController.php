<?php

namespace App\Http\Controllers\Publications;

use App\Http\Controllers\Controller;
use App\Models\Publications\CustomPage;
use App\Models\Publications\CustomPageResponse;
use App\Notifications\CustomPages\ResponseCopy;
use App\Notifications\CustomPages\ResponseReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
        if (count($page->permissions) > 0) {
            if (!Auth::check()) {
                return redirect()->route('auth.connect.login');
            }

            $hasPermission = false;

            foreach ($page->permissions as $perm) {
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

    public function adminCreatePage()
    {
        //Return view
        return view('admin.publications.custom-pages.create');
    }

    public function adminPostCreatePage(Request $request)
    {
        //Define validator messages
        $messages = [
            'title.required'       => 'A title is required.',
            'title.max'            => 'A title may not be more than 50 characters long.',
            'content.required'     => 'Content is required.',
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'title'       => 'required|max:50',
            'content'     => 'required',
        ], $messages);

        //Redirect if fails
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator, 'createCustomPageError');
        }

        $custom_page = new CustomPage();
        $custom_page->name = $request->get('title');
        $custom_page->slug = Str::slug($request->get('title'));
        $custom_page->content = $request->get('content');
        $custom_page->response_form_enabled = $request->get('responses');
        $custom_page->save();

        return redirect()->route('publications.custom-pages')->with('success', $custom_page->name.' was created!');

    }

    public function adminEditPage($page_id)
    {
        //Find page
        $page = CustomPage::where('id', $page_id)->firstOrFail();

        //Return view
        return view('admin.publications.custom-pages.edit', compact('page'));

    }

    public function adminEditPagePost(Request $request, $page_id)
    {
        //Define validator messages
        $messages = [
            'title.required'       => 'A title is required.',
            'title.max'            => 'A title may not be more than 50 characters long.',
            'content.required'     => 'Content is required.',
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'title'       => 'required|max:50',
            'content'     => 'required',
        ], $messages);

        //Redirect if fails
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator, 'editCustomPageError');
        }

        $custom_page = CustomPage::whereId($page_id)->firstOrFail();
        $custom_page->name = $request->get('title');
        $custom_page->slug = Str::slug($request->get('title'));
        $custom_page->content = $request->get('content');
        $custom_page->response_form_enabled = $request->get('responses');
        $custom_page->save();

        $request->session()->flash('custompageedited', 'Custom Page Edited!');

        return redirect()->route('publications.custom-pages')->with('success', $custom_page->name.' was edited!');
    }
    public function deleteCustomPage($page_id){
        $custom_page = CustomPage::whereId($page_id)->firstOrFail();
        $custom_page->delete();

        return redirect()->route('publications.custom-pages');
    }
}
