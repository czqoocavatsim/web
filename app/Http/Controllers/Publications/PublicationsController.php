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
    public function atcResources()
    {
        $resources = AtcResource::all();

        return view('publications.atcresources', compact('resources'));
    }

    public function policies()
    {
        //Get the policies in alphabetical order
        $policies = Policy::all()->sortBy('title');

        //Return the view
        return view('publications.policies', compact('policies'));
    }

    public function adminPolicies()
    {
        //Get the policies and meeting minutes in relevant order
        $policies = Policy::all()->sortBy('title');
        $minutes = MeetingMinutes::all()->sortByDesc('created_at');

        //Return the view
        return view('admin.publications.policies.index', compact('policies', 'minutes'));
    }

    public function createPolicyPost(Request $request)
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
            return redirect()->route('publications.policies', ['createPolicyModal' => 1])->withInput()->withErrors($validator, 'createPolicyErrors');
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
        return redirect()->route('publications.policies')->with('success', 'Policy created!');
    }

    public function editPolicyPost(Request $request, $id)
    {
        //Get polic
        $policy = Policy::whereId($id)->firstOrFail();

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
            return redirect()->route('publications.policies', ["editPolicy{$policy->id}Modal" => 1])->withInput()->withErrors($validator, 'editPolicyErrors');
        }

        //Edit policy object
        $policy->title = $request->get('title');
        $policy->description = $request->get('description');
        $policy->url = $request->get('url');

        //Save it
        $policy->save();

        //Redirect
        return redirect()->route('publications.policies')->with('success', 'Policy edited!');
    }

    public function deletePolicy($id)
    {
        //Find policy
        $policy = Policy::whereId($id)->firstOrFail();

        //Delete it
        $policy->delete();

        //Return
        return redirect()->route('publications.policies')->with('info', 'Policy deleted');
    }

    public function adminAtcResources()
    {
        //Find ATC Resources
        $atcResources = AtcResource::all()->sortBy('id');

        //Return view
        return view('admin.publications.atc-resources.index', compact('atcResources'));
    }

    public function createAtcResourcePost(Request $request)
    {
        //Define validator messages
        $messages = [
            'title.required' => 'A title is required.',
            'title.max' => 'A title may not be more than 100 characters long.',
            'description.required' => 'A description is required.',
            'visibility.required' => 'A visibility option is required.',
            'url.required' => 'A PDF URL is required.',
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:100',
            'description' => 'required',
            'visibility' => 'required',
            'url' => 'required',
        ], $messages);

        //Redirect if fails
        if ($validator->fails()) {
            return redirect()->route('publications.atc-resources', ['createAtcResourceModal' => 1])->withInput()->withErrors($validator, 'createAtcResourceErrors');
        }

        //Create object
        $atcResource = new AtcResource([
            'user_id' => Auth::id(),
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'atc_only' => $request->get('visibility'),
            'url' => $request->get('url')
        ]);

        //Save it
        $atcResource->save();

        //Redirect
        return redirect()->route('publications.atc-resources')->with('success', 'ATC resource created!');
    }

    public function editAtcResourcePost(Request $request, $id)
    {
        //Get polic
        $atcResource = AtcResource::whereId($id)->firstOrFail();

        //Define validator messages
        $messages = [
            'title.required' => 'A title is required.',
            'title.max' => 'A title may not be more than 100 characters long.',
            'description.required' => 'A description is required.',
            'visibility.required' => 'A visibility option is required.',
            'url.required' => 'A PDF URL is required.',
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:100',
            'description' => 'required',
            'visibility' => 'required',
            'url' => 'required',
        ], $messages);


        //Redirect if fails
        if ($validator->fails()) {
            return redirect()->route('publications.atc-resources', ["editAtcResource{$atcResource->id}Modal" => 1])->withInput()->withErrors($validator, 'editAtcResourceErrors');
        }

        //Edit object
        $atcResource->title = $request->get('title');
        $atcResource->description = $request->get('description');
        $atcResource->atc_only = $request->get('visibility');
        $atcResource->url = $request->get('url');

        //Save it
        $atcResource->save();

        //Redirect
        return redirect()->route('publications.atc-resources')->with('success', 'ATC resource edited!');
    }

    public function deleteAtcResource($id)
    {
        //Find resource
        $atcResource = AtcResource::whereId($id)->firstOrFail();

        //Delete it
        $atcResource->delete();

        //Return
        return redirect()->route('publications.atc-resources')->with('info', 'ATC resource deleted');
    }
}
