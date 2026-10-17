<?php

namespace App\Http\Controllers;

use App\Models\Events\EventSignup;
use App\Models\Events\EventSignupEvent;
use App\Models\Events\EventSignupPosition;
use App\Models\Events\EventSignupSlot;
use App\Models\Events\EventSignupAssignment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventSignupsController extends Controller
{
    public function show(Request $request)
    {
        $events = EventSignupEvent::where('is_open', true)
            ->orderByRaw('event_date is null')
            ->orderBy('event_date')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
        $selectedEvent = $events->firstWhere('id', (int) $request->query('event_id'));
        $slots = $selectedEvent
            ? $selectedEvent->slots()->where('is_open', true)->orderBy('sort_order')->orderBy('id')->get()
            : collect();

        return view('signups.index', compact('events', 'selectedEvent', 'slots'));
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|integer|exists:event_signup_events,id',
            'full_name' => 'required|string|max:120',
            'cid' => 'required|string|regex:/^[0-9]{1,10}$/',
            'preferred_position' => 'required|in:Gander,Shanwick,Either position/NAT',
            'availability_windows' => 'required|array|min:1|max:5',
            'availability_windows.*.starts_at' => 'required|date_format:Y-m-d\\TH:i',
            'availability_windows.*.ends_at' => 'required|date_format:Y-m-d\\TH:i',
        ]);

        $event = EventSignupEvent::where('is_open', true)->findOrFail($validated['event_id']);
        abort_unless($event->starts_at && $event->ends_at, 422, 'This event is not accepting availability yet.');
        $windows = [];
        foreach ($validated['availability_windows'] as $window) {
            $start = Carbon::createFromFormat('Y-m-d\\TH:i', $window['starts_at'], 'UTC');
            $end = Carbon::createFromFormat('Y-m-d\\TH:i', $window['ends_at'], 'UTC');
            if ($end->lte($start) || $start->minute !== 0 || $end->minute !== 0 || $start->lt($event->starts_at) || $end->gt($event->ends_at)) {
                return back()->withErrors(['availability_windows' => 'Use whole UTC hours within the event schedule, with the finish time after the start.'])->withInput();
            }
            $windows[] = ['starts_at' => $start->toDateTimeString(), 'ends_at' => $end->toDateTimeString()];
        }

        EventSignup::create([
            'event_id' => $event->id,
            'full_name' => $validated['full_name'],
            'cid' => $validated['cid'],
            'preferred_position' => $validated['preferred_position'],
            'availability' => [],
            'availability_windows' => $windows,
            'submitted_at' => now(),
        ]);

        return redirect()->route('signups.show', ['event_id' => $event->id])->with('success', 'Thanks — your signup has been received.');
    }

    public function admin()
    {
        $events = EventSignupEvent::with(['slots', 'positions', 'assignments.position', 'assignments.signup', 'signups.assignments.position'])->orderBy('event_date')->orderBy('sort_order')->orderBy('id')->get();
        $signups = EventSignup::with('event')->latest('submitted_at')->get();
        $slotLabels = EventSignupSlot::pluck('label', 'id');
        foreach ($events as $event) {
            $event->planner_hours = $this->eventHours($event);
        }

        return view('admin.signups.index', compact('events', 'signups', 'slotLabels'));
    }

    public function createEvent(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'starts_at' => 'required|date_format:Y-m-d\\TH:i',
            'ends_at' => 'required|date_format:Y-m-d\\TH:i|after:starts_at',
        ]);

        $startsAt = Carbon::createFromFormat('Y-m-d\\TH:i', $validated['starts_at'], 'UTC');
        $endsAt = Carbon::createFromFormat('Y-m-d\\TH:i', $validated['ends_at'], 'UTC');
        if ($endsAt->diffInHours($startsAt) > 24 || $startsAt->minute !== 0 || $endsAt->minute !== 0) {
            return back()->withErrors(['ends_at' => 'Use whole-hour start and finish times; an event can be up to 24 hours long.'])->withInput();
        }

        EventSignupEvent::create([
            'name' => $validated['name'],
            'event_date' => $startsAt->toDateString(),
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'is_open' => true,
            'sort_order' => EventSignupEvent::max('sort_order') + 1,
        ]);

        return redirect()->route('signups.admin.index')->with('success', 'Event added. Set its hourly staffing needs and positions below.');
    }

    public function toggleEvent(EventSignupEvent $event)
    {
        $event->is_open = !$event->is_open;
        $event->save();

        return redirect()->route('signups.admin.index')->with('success', 'Event signup status updated.');
    }

    public function createSlot(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|integer|exists:event_signup_events,id',
            'label' => 'required|string|max:120',
        ]);

        EventSignupSlot::create([
            'event_id' => $validated['event_id'],
            'label' => $validated['label'],
            'is_open' => true,
            'sort_order' => EventSignupSlot::where('event_id', $validated['event_id'])->max('sort_order') + 1,
        ]);

        return redirect()->route('signups.admin.index')->with('success', 'Availability time added.');
    }

    public function toggleSlot(EventSignupSlot $slot)
    {
        $slot->is_open = !$slot->is_open;
        $slot->save();

        return redirect()->route('signups.admin.index')->with('success', 'Availability time updated.');
    }

    public function deleteSlot(EventSignupSlot $slot)
    {
        $slot->delete();

        return redirect()->route('signups.admin.index')->with('success', 'Availability time removed.');
    }

    public function saveRequirements(Request $request, EventSignupEvent $event)
    {
        $validated = $request->validate([
            'requirements' => 'required|array',
            'requirements.*.gander' => 'required|integer|min:0|max:20',
            'requirements.*.shanwick' => 'required|integer|min:0|max:20',
        ]);
        $hours = $this->eventHours($event);
        $requirements = [];
        foreach ($hours as $index => $hour) {
            $row = $validated['requirements'][$index] ?? ['gander' => 0, 'shanwick' => 0];
            $requirements[$hour->toDateTimeString()] = [
                'Gander' => (int) $row['gander'],
                'Shanwick' => (int) $row['shanwick'],
            ];
        }
        $event->staffing_requirements = $requirements;
        $wasPublished = $event->roster_published;
        $event->roster_published = false;
        $event->save();

        return back()->with('success', $wasPublished ? 'Hourly staffing requirements saved. The roster is unpublished until you review and publish it again.' : 'Hourly staffing requirements saved.');
    }

    public function createPosition(Request $request, EventSignupEvent $event)
    {
        $validated = $request->validate([
            'sector' => 'required|in:Gander,Shanwick',
            'callsign' => 'required|string|max:120',
        ]);
        $event->positions()->create([
            'sector' => $validated['sector'],
            'callsign' => $validated['callsign'],
            'is_open' => true,
            'sort_order' => $event->positions()->max('sort_order') + 1,
        ]);
        $this->unpublishRoster($event);

        return back()->with('success', 'Roster position added.');
    }

    public function togglePosition(EventSignupPosition $position)
    {
        $position->is_open = !$position->is_open;
        $position->save();
        $this->unpublishRoster($position->event);
        return back()->with('success', 'Roster position updated.');
    }

    public function saveAssignment(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|integer|exists:event_signup_events,id',
            'signup_id' => 'required|integer|exists:event_signups,id',
            'position_id' => 'required|integer|exists:event_signup_positions,id',
            'starts_at' => 'required|date_format:Y-m-d\\TH:i',
            'ends_at' => 'required|date_format:Y-m-d\\TH:i|after:starts_at',
            'assignment_id' => 'nullable|integer|exists:event_signup_assignments,id',
        ]);
        $event = EventSignupEvent::findOrFail($validated['event_id']);
        $signup = EventSignup::where('event_id', $event->id)->findOrFail($validated['signup_id']);
        $position = EventSignupPosition::where('event_id', $event->id)->where('is_open', true)->findOrFail($validated['position_id']);
        $start = Carbon::createFromFormat('Y-m-d\\TH:i', $validated['starts_at'], 'UTC');
        $end = Carbon::createFromFormat('Y-m-d\\TH:i', $validated['ends_at'], 'UTC');
        if ($start->minute !== 0 || $end->minute !== 0 || $end->diffInHours($start) < 1) {
            return back()->withErrors(['assignment' => 'Roster shifts must be at least one whole UTC hour.']);
        }
        $fitsAvailability = collect($signup->availability_windows ?? [])->contains(function ($window) use ($start, $end) {
            return $start->gte(Carbon::parse($window['starts_at'], 'UTC')) && $end->lte(Carbon::parse($window['ends_at'], 'UTC'));
        });
        if (!$fitsAvailability) {
            return back()->withErrors(['assignment' => 'The assigned shift must stay within the controller’s availability window.']);
        }
        if ($signup->preferred_position !== 'Either position/NAT' && $signup->preferred_position !== $position->sector) {
            return back()->withErrors(['assignment' => 'This controller selected a different preferred sector.']);
        }
        $conflict = EventSignupAssignment::where('signup_id', $signup->id)
            ->when($validated['assignment_id'] ?? null, function ($query, $id) { $query->where('id', '!=', $id); })
            ->where('starts_at', '<', $end)->where('ends_at', '>', $start)->exists();
        if ($conflict) {
            return back()->withErrors(['assignment' => 'This controller already has an overlapping shift.']);
        }
        $positionConflict = EventSignupAssignment::where('position_id', $position->id)
            ->when($validated['assignment_id'] ?? null, function ($query, $id) { $query->where('id', '!=', $id); })
            ->where('starts_at', '<', $end)->where('ends_at', '>', $start)->exists();
        if ($positionConflict) {
            return back()->withErrors(['assignment' => 'That position already has a controller assigned during this time.']);
        }

        $assignment = ($validated['assignment_id'] ?? null)
            ? EventSignupAssignment::where('event_id', $event->id)->findOrFail($validated['assignment_id'])
            : new EventSignupAssignment();
        $wasPublished = $event->roster_published;
        $assignment->fill([
            'event_id' => $event->id,
            'signup_id' => $signup->id,
            'position_id' => $position->id,
            'starts_at' => $start,
            'ends_at' => $end,
        ])->save();
        $this->unpublishRoster($event);

        return back()->with('success', $wasPublished ? 'Roster shift saved. Review and publish the roster again when it is ready.' : 'Roster shift saved.');
    }

    public function deleteAssignment(EventSignupAssignment $assignment)
    {
        $event = $assignment->signup->event;
        $wasPublished = $event->roster_published;
        $assignment->delete();
        $this->unpublishRoster($event);
        return back()->with('success', $wasPublished ? 'Roster shift removed. Review and publish the roster again when it is ready.' : 'Roster shift removed.');
    }

    public function togglePublished(EventSignupEvent $event)
    {
        if (!$event->roster_published && !$event->assignments()->exists()) {
            return back()->withErrors(['roster' => 'Assign at least one controller before publishing the roster.']);
        }
        $event->roster_published = !$event->roster_published;
        $event->save();
        return back()->with('success', $event->roster_published ? 'Roster published.' : 'Roster unpublished.');
    }

    public function publicRoster(EventSignupEvent $event)
    {
        abort_unless($event->roster_published, 404);
        $assignments = $event->assignments()->with(['signup', 'position'])->orderBy('starts_at')->get();
        return view('signups.roster', compact('event', 'assignments'));
    }

    private function eventHours(EventSignupEvent $event)
    {
        if (!$event->starts_at || !$event->ends_at) {
            return collect();
        }
        $hours = collect();
        $hour = $event->starts_at->copy()->startOfHour();
        while ($hour->lt($event->ends_at)) {
            $hours->push($hour->copy());
            $hour->addHour();
        }
        return $hours;
    }

    private function unpublishRoster(EventSignupEvent $event)
    {
        if ($event && $event->roster_published) {
            $event->roster_published = false;
            $event->save();
        }
    }
}
