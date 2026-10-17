<?php

namespace App\Http\Controllers;

use App\Models\Events\EventSignup;
use App\Models\Events\EventSignupEvent;
use App\Models\Events\EventSignupSlot;
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
            'availability' => 'required|array|min:1',
        ], ['availability.required' => 'Please select at least one available time.', 'availability.min' => 'Please select at least one available time.']);

        $event = EventSignupEvent::where('is_open', true)->findOrFail($validated['event_id']);
        $openIds = $event->slots()->where('is_open', true)->pluck('id')->map(function ($id) {
            return (string) $id;
        })->all();

        $request->validate([
            'availability.*' => 'required|in:'.implode(',', $openIds ?: ['0']),
        ]);

        EventSignup::create([
            'event_id' => $event->id,
            'full_name' => $validated['full_name'],
            'cid' => $validated['cid'],
            'preferred_position' => $validated['preferred_position'],
            'availability' => array_values($validated['availability']),
            'submitted_at' => now(),
        ]);

        return redirect()->route('signups.show', ['event_id' => $event->id])->with('success', 'Thanks — your signup has been received.');
    }

    public function admin()
    {
        $events = EventSignupEvent::with('slots')->orderBy('event_date')->orderBy('sort_order')->orderBy('id')->get();
        $signups = EventSignup::with('event')->latest('submitted_at')->get();
        $slotLabels = EventSignupSlot::pluck('label', 'id');

        return view('admin.signups.index', compact('events', 'signups', 'slotLabels'));
    }

    public function createEvent(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'event_date' => 'nullable|date',
        ]);

        EventSignupEvent::create([
            'name' => $validated['name'],
            'event_date' => $validated['event_date'] ?? null,
            'is_open' => true,
            'sort_order' => EventSignupEvent::max('sort_order') + 1,
        ]);

        return redirect()->route('signups.admin.index')->with('success', 'Event added. You can now add its available times.');
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
}
