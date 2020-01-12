<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Models\Events\ControllerApplication;
use App\Models\Users\User;
use App\Models\Events\Event;
use App\Models\Settings\AuditLogEntry;
use Illuminate\Http\Request;
use Auth;

class EventController extends Controller
{
    /*
    View all events
    */
    public function index()
    {
        $events = Event::all()->sortByDesc('start_timestamp');
        return view('events.index', compact('events'));
    }

    public function viewEvent($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        $updates = $event->updates;
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
        return view('dashboard.events.index', compact('events'));
    }

    public function adminViewEvent($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        $applications = $event->controllerApplications;
        $updates = $event->updates;
        return view('dashboard.events.view', compact('event','applications', 'updates'));
    }
}
