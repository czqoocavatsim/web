<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Models\Events\ControllerApplication;
use App\Models\Users\User;
use App\Models\Events\Event;
use App\Models\Events\EventUpdate;
use App\Models\Publications\UploadedImage;
use App\Models\Settings\AuditLogEntry;
use App\Notifications\Events\NewControllerSignUp;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use RestCord\DiscordClient;
use Spatie\Permission\Models\Role;

class EventController extends Controller
{
    /*
    View all events
    */
    public function index()
    {
        $events = Event::cursor()->filter(function ($event) {
            return !$event->event_in_past();
        })->sortBy('start_timestamp');

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

        //Send email notification to Marketing Team
        $marketingTeam = Role::whereName('Marketing Team')->first();
        if ($marketingTeam)
        {
            foreach ($marketingTeam->users as $user) {
                $user->notify(new NewControllerSignUp($application));
            }
        }

        //Discord client
        $discord = new DiscordClient(['token' => config('services.discord.token')]);

        //Send notification to marketing
        $discord->channel->createMessage([
            'channel.id' => intval(config('services.discord.marketing')),
            "content" => "A controller has signed up to control for " . $application->event->name,
            'embed' => [
                "title" => $application->user->fullName('FLC'),
                "url" => route('events.admin.view', $application->event->slug),
                "timestamp" => date('Y-m-d H:i:s'),
                "color" => hexdec( "2196f3" ),
                "fields" => [
                    [
                        "name" => "Timing",
                        "value" => "From " . $application->start_availability_timestamp . " to " . $application->end_availability_timestamp,
                        "inline" => false
                    ],
                    [
                        "name" => "Comments",
                        "value" => $application->comments ? $application->comments : "No comments given",
                        "inline" => true
                    ],
                ]
            ]
        ]); 

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
            $path = Storage::disk('digitalocean')->put('staff_uploads/events' . Carbon::now()->toDateString(), $request->file('image'), 'public');
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
        if ($request->get('openControllerApps') == 'on') {
            $event->controller_applications_open = true;
        }

        //Save it
        $event->save();

        //Announce it on Discord?
        if ($request->get('announceDiscord') == 'on') {
            //Discord client
            $discord = new DiscordClient(['token' => config('services.discord.token')]);

            //Send notification to marketing
            $discord->channel->createMessage([
                'channel.id' => config('app.env') == 'local' ? intval(config('services.discord.web_logs')) : intval(config('services.discord.announcements')),
                'embed' => [
                    'title' => "Upcoming event: {$event->name}",
                    'description' => "Starting {$event->start_timestamp_pretty()}",
                    'color' => 0x80c9,
                    "image" => [
                        "url" => $event->image_url ? url('/').$event->image_url : null
                    ],
                    "url" => route('events.view', $event->slug),
                    "timestamp" => date('Y-m-d H:i:s'),
                ]
            ]);
        }

        //Redirect
        return redirect()->route('events.admin.view', $event->slug)->with('success', 'Event created!');
    }

    public function adminDeleteEvent($slug)
    {
        //Find the event
        $event = Event::where('slug', $slug)->firstOrFail();

        //Delete it
        $event->delete();

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
            $path = Storage::disk('digitalocean')->put('staff_uploads/events' . Carbon::now()->toDateString(), $request->file('image'), 'public');
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

        //Announce it on Discord?
        if ($request->get('announceDiscord') == 'on') {
            //Discord client
            $discord = new DiscordClient(['token' => config('services.discord.token')]);

            //Send notification to marketing
            $discord->channel->createMessage([
                'channel.id' => config('app.env') == 'local' ? intval(config('services.discord.web_logs')) : intval(config('services.discord.announcements')),
                'embed' => [
                    'title' => "Update for event {$update->event->name}",
                    'description' => $update->content,
                    'color' => 0x80c9,
                    "url" => route('events.view', $update->event->slug),
                    "timestamp" => date('Y-m-d H:i:s'),
                    "author" => [
                        "name" => $update->user->fullName('FLC'),
                    ],
                ]
            ]);
        }

        //Redirect
        return redirect()->route('events.admin.view', $event_slug)->with('success', 'Update created!');
    }

    public function adminDeleteControllerApp($event_slug, $cid)
    {
        //Find the controller app
        $app = ControllerApplication::where('user_id', $cid)->where('event_id', Event::where('slug', $event_slug)->firstOrFail()->id)->FirstOrFail();

        //Delete it? Delete it!
        $app->delete();

        //Redirect
        return redirect()->route('events.admin.view', $event_slug)->with('info', 'Controller application from '.$app->user_id. 'deleted.');
    }

    public function adminDeleteUpdate($event_slug, $update_id)
    {
        //Find the update
        $update = EventUpdate::whereId($update_id)->where('event_id', Event::where('slug', $event_slug)->firstOrFail()->id)->FirstOrFail();

        //Delete it? Delete it!
        $update->delete();

        //Redirect
        return redirect()->route('events.admin.view', $event_slug)->with('info', 'Update \''.$update->title. '\'deleted.');
    }
}
