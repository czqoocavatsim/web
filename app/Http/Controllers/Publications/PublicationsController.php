<?php

namespace App\Http\Controllers\Publications;

use App\Http\Controllers\Controller;
use App\Models\Publications\AtcResource;
use App\Models\Publications\MeetingMinutes;
use App\Models\Publications\Policy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PublicationsController extends Controller
{
    public function index()
    {
        $resources = AtcResource::all();

        return view('atcresources', compact('resources'));
    }

    public function uploadResource(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'url' => 'required|url',
        ]);

        $resource = new AtcResource();
        $resource->title = $request->get('title');
        $resource->description = $request->get('description');
        $resource->url = $request->get('url');

        if ($request->get('atc_only') == 'yes') {
            $resource->atc_only = true;
        }

        $resource->save();

        return redirect()->route('atcresources.index')->with('success', 'Resource uploaded!');
    }

    public function deleteResource($id)
    {
        $resource = AtcResource::whereId($id)->firstOrFail();

        $resource->delete();

        return redirect()->back()->with('info', 'Resource deleted.');
    }

    public function policiesIndex()
    {
        //Get the policies in alphabetical order
        $policies = Policy::all()->sortBy('title');

        //Return the view
        return view('policies', compact('policies'));
    }

    public function adminIndex()
    {
        //Get the policies and meeting minutes in relevant order
        $policies = Policy::all()->sortBy('title');
        $minutes = MeetingMinutes::all()->sortByDesc('created_at');

        //Return the view
        return view('admin.publications.index', compact('policies', 'minutes'));
    }

    public function adminCreatePolicy()
    {
        return view('admin.publications.policies.create');
    }

    public function adminCreatePolicyPost(Request $request)
    {
        //Define validator messages
        $messages = [
            'title.required' => 'A title is required.',
            'title.max' => 'A title may not be more than 100 characters long.',
            'description.required' => 'A description is required.',
            'url.required' => 'A PDF URL is required.',
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:100',
            'description' => 'required',
            'url' => 'required',
        ], $messages);

        //Redirect if fails
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator, 'createPolicyErrors');
        }

        //Create policy object
        $policy = new Policy([
            'user_id' => Auth::id(),
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'url' => $request->get('url')
        ]);

        //Save it
        $policy->save();

        //Redirect
        return redirect()->route('publications.policies.view', $policy->id)->with('success', 'Policy created!');
    }

    public function adminViewPolicy($id)
    {
        //Get policy
        $policy = Policy::whereId($id)->firstOrFail();

        // Return view
        return view('admin.publications.policies.view', compact('policy'));
    }
}
