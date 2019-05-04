<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AtcResource;

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
            'description' => 'required'
        ]);

        $resource = new AtcResource();
        $resource->title = $request->get('title');
        $resource->description = $request->get('description');
        if ($request->file('file'))
        {
            $uploadedFile = $request->file('file');
            $filename = $uploadedFile->getClientOriginalName();
            Storage::disk('local')->putFileAs(
                'public/files/atcresources/',
                $uploadedFile,
                $filename
            );
            $resource->url = Storage::url('public/files/atcreosurces/'.$filename);
        }
        else if ($request->get('link'))
        {
            $resource->url = $request->get('link');
        }
        else
        {
            return redirect()->back()->withInput()->with('error', 'Neither a file or link was provided');
        }

        return $resource;
    }
}
