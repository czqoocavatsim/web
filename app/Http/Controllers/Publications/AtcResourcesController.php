<?php

namespace App\Http\Controllers\Publications;

use App\Http\Controllers\Controller;
use App\Models\Publications\AtcResource;
use Illuminate\Http\Request;

class AtcResourcesController extends Controller
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
}
