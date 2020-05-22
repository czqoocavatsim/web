<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Models\Events\ControllerApplication;
use App\Models\Users\User;
use App\Models\Events\Event;
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
        return view('admin.events.create');
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

        //Redirect
        return redirect()->route('events.admin.view', $event->slug)->with('success', 'Event created!');
    }

    public function adminDeleteEvent($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        $event->delete();
        return redirect()->route('events.admin.index')->with('info', 'Event deleted.');
    }
}
