<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Models\Events\ControllerApplication;
use App\Models\Users\User;
use App\Models\Events\Event;
use App\Models\Events\EventUpdate;
use App\Models\Publications\UploadedImage;
use App\Models\Settings\AuditLogEntry;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /*
    View all events
    */
    public function index()
    {
        $events = Event::cursor()->filter(function ($event) {
            return !$event->event_in_past();
        })->sortByDesc('start_timestamp');

        $pastEvents = Event::cursor()->filter(function ($event) {
            return $event->event_in_past();
        })->sortByDesc('start_timestamp');

        return view('events.index', compact('events', 'pastEvents'));
    }

    public function viewEvent($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        $updates = $event->updates;
        if (Auth::check() && ControllerApplication::where('user_id', Auth::id())->where('event_id', $event->id))
        {
            $app = ControllerApplication::where('user_id', Auth::id())->where('event_id', $event->id)->first();
            return view('events.view', compact('event', 'updates', 'app'));
        }
        return view('events.view', compact('event', 'updates'));
    }

    public function controllerApplicationAjaxSubmit(Request $request)
    {
        $this->validate($request, [
            'availability_start' => 'required',
            'availability_end' => 'required'
        ]);
        $application = new ControllerApplication([
            'user_id' => Auth::id(),
            'event_id' => $request->get('event_id'),
            'start_availability_timestamp' => $request->get('availability_start'),
            'end_availability_timestamp' => $request->get('availability_end'),
            'comments' => $request->get('comments'),
            'submission_timestamp' => date('Y-m-d H:i:s'),
        ]);
        $application->save();
        $webhook = $application->discord_webhook();
        if (!$webhook) {
            AuditLogEntry::insert(Auth::user(), 'Webhook failed', Auth::user(), 0);
        }
        return redirect()->back()->with('success', 'Submitted!');
    }

    public function adminIndex()
    {
        $events = Event::all()->sortByDesc('created_at');
        return view('admin.events.index', compact('events'));
    }

    public function adminViewEvent($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        $applications = $event->controllerApplications;
        $updates = $event->updates;
        return view('admin.events.view', compact('event','applications', 'updates'));
    }

    public function adminCreateEvent()
    {
        $uploadedImgs = UploadedImage::all()->sortByDesc('id');
        return view('admin.events.create', compact('uploadedImgs'));
    }

    public function adminCreateEventPost(Request $request)
    {
        //Define validator messages
        $messages = [
            'name.required' => 'A name is required.',
            'name.max' => 'A name may not be more than 100 characters long.',
            'image.mimes' => 'An image file must be in the jpg png or gif formats.',
            'description.required' => 'A description is required.',
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'image' => 'mimes:jpeg,jpg,png,gif',
            'description' => 'required',
            'start' => 'required',
            'end' => 'required'
        ], $messages);

        //Redirect if fails
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator, 'createEventErrors');
        }

        //Create event object
        $event = new Event();

        //Assign name
        $event->name = $request->get('name');

        //Assign start/end date/time
        $event->start_timestamp = $request->get('start');
        $event->end_timestamp = $request->get('end');

        //Assign description
        $event->description = $request->get('description');

        //Assign user
        $event->user_id = Auth::id();

        //Upload image if it exists
        if ($request->file('image')) {
            $basePath = 'public/files/'.Carbon::now()->toDateString().'/'.rand(1000,2000);
            $path = $request->file('image')->store($basePath);
            $event->image_url = Storage::url($path);

            //Add to uploaded images
            $uploadedImg = new UploadedImage();
            $uploadedImg->path = Storage::url($path);
            $uploadedImg->user_id = Auth::id();
            $uploadedImg->save();
        }

        //If there is a uplaoded image selected lets put it on there
        if ($request->get('uploadedImage')) {
            $event->image_url = UploadedImage::whereId($request->get('uploadedImage'))->first()->path;
        }

        //Create slug
        $event->slug = Str::slug($request->get('name').'-'.Carbon::now()->toDateString());

        //Assign departure icao and arrival icao if they exist
        if ($request->get('departure_icao') && $request->get('arrival_icao')) {
            $event->departure_icao = $request->get('departure_icao');
            $event->arrival_icao = $request->get('arrival_icao');
        }

        //If controller apps are open then lets make them open
        if ($request->has('openControllerApps')) {
            $event->controller_applications_open = true;
        }

        //Save it
        $event->save();

        //Audit
        AuditLogEntry::insert(Auth::user(), 'Created event '.$event->name, User::find(1), 0);

        //Redirect
        return redirect()->route('events.admin.view', $event->slug)->with('success', 'Event created!');
    }

    public function adminDeleteEvent($slug)
    {
        //Find the event
        $event = Event::where('slug', $slug)->firstOrFail();

        //Delete it
        $event->delete();

        //Audit it
        AuditLogEntry::insert(Auth::user(), 'Deleted event '.$event->name, User::find(1), 0);

        //Redirect
        return redirect()->route('events.admin.index')->with('info', 'Event deleted.');
    }

    public function adminEditEventPost(Request $request, $event_slug)
    {
        //Define validator messages
        $messages = [
            'name.required' => 'A name is required.',
            'name.max' => 'A name may not be more than 100 characters long.',
            'image.mimes' => 'An image file must be in the jpg png or gif formats.',
            'description.required' => 'A description is required.',
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'image' => 'mimes:jpeg,jpg,png,gif',
            'description' => 'required',
            'start' => 'required',
            'end' => 'required'
        ], $messages);

        //Redirect if fails
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator, 'editEventErrors');
        }

        //Get event object
        $event = Event::where('slug', $event_slug)->firstOrFail();

        //Assign name
        $event->name = $request->get('name');

        //Assign start/end date/time
        $event->start_timestamp = $request->get('start');
        $event->end_timestamp = $request->get('end');

        //Assign description
        $event->description = $request->get('description');

        //Upload image if it exists
        if ($request->file('image')) {
            $basePath = 'public/files/'.Carbon::now()->toDateString().'/'.rand(1000,2000);
            $path = $request->file('image')->store($basePath);
            $event->image_url = Storage::url($path);

            //Add to uploaded images
            $uploadedImg = new UploadedImage();
            $uploadedImg->path = Storage::url($path);
            $uploadedImg->user_id = Auth::id();
            $uploadedImg->save();
        }

        //If there is a uplaoded image selected lets put it on there
        if ($request->get('uploadedImage')) {
            $event->image_url = UploadedImage::whereId($request->get('uploadedImage'))->first()->path;
        }

        //Assign departure icao and arrival icao if they exist
        if ($request->get('departure_icao') && $request->get('arrival_icao')) {
            $event->departure_icao = $request->get('departure_icao');
            $event->arrival_icao = $request->get('arrival_icao');
        }

        //If controller apps are open then lets make them open
        if ($request->has('openControllerApps')) {
            $event->controller_applications_open = true;
        }

        //Save it
        $event->save();

        //Audit it
        AuditLogEntry::insert(Auth::user(), 'Edited event '.$event->name, User::find(1), 0);

        //Redirect
        return redirect()->route('events.admin.view', $event->slug)->with('success', 'Event edited!');
    }

    public function adminCreateUpdatePost(Request $request, $event_slug)
    {
        //Define validator messages
        $messages = [
            'updateTitle.required' => 'A title is required.',
            'updateTitle.max' => 'A title may not be more than 100 characters long.',
            'updateContent.required' => 'Content is required.',
        ];

        //Validate
        $validator = Validator::make($request->all(), [
            'updateTitle' => 'required|max:100',
            'updateContent' => 'required'
        ], $messages);

        //Redirect if fails
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator, 'createUpdateErrors');
        }

        //Create update object
        $update = new EventUpdate([
            'event_id' => Event::where('slug', $event_slug)->firstOrFail()->id,
            'user_id' => Auth::id(),
            'title' => $request->get('updateTitle'),
            'content' => $request->get('updateContent'),
            'created_timestamp' => Carbon::now(),
            'slug' => Str::slug($request->get('updateTitle').'-'.Carbon::now()->toDateString()),
        ]);

        //Save it
        $update->save();

        //Audit it
        AuditLogEntry::insert(Auth::user(), 'Created event update for '.Event::where('slug', $event_slug)->firstOrFail()->name, User::find(1), 0);

        //Redirect
        return redirect()->route('events.admin.view', $event_slug)->with('success', 'Update created!');
    }

    public function adminDeleteControllerApp($event_slug, $cid)
    {
        //Find the controller app
        $app = ControllerApplication::where('user_id', $cid)->where('event_id', Event::where('slug', $event_slug)->firstOrFail()->id)->FirstOrFail();

        //Delete it? Delete it!
        $app->delete();

        //Audit it
        AuditLogEntry::insert(Auth::user(), 'Deleted event controller app '.$app->id, User::find(1), 0);

        //Redirect
        return redirect()->route('events.admin.view', $event_slug)->with('info', 'Controller application from '.$app->user_id. 'deleted.');
    }

    public function adminDeleteUpdate($event_slug, $update_id)
    {
        //Find the update
        $update = EventUpdate::whereId($update_id)->where('event_id', Event::where('slug', $event_slug)->firstOrFail()->id)->FirstOrFail();

        //Delete it? Delete it!
        $update->delete();

        //Audit it
        AuditLogEntry::insert(Auth::user(), 'Deleted event update '.$update->id, User::find(1), 0);

        //Redirect
        return redirect()->route('events.admin.view', $event_slug)->with('info', 'Update \''.$update->title. '\'deleted.');
    }
}
