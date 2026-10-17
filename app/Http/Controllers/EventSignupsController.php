<?php

namespace App\Http\Controllers;

use App\Models\Events\EventSignup;
use App\Models\Events\EventSignupSlot;
use Illuminate\Http\Request;

class EventSignupsController extends Controller
{
    public function show()
    {
        $slots = EventSignupSlot::where('is_open', true)->orderBy('sort_order')->orderBy('id')->get();
        return view('signups.index', compact('slots'));
    }

    public function submit(Request $request)
    {
        $openIds = EventSignupSlot::where('is_open', true)->pluck('id')->map(function ($id) { return (string) $id; })->all();
        $validated = $request->validate([
            'full_name' => 'required|string|max:120',
            'cid' => 'required|string|regex:/^[0-9]{1,10}$/',
            'preferred_position' => 'required|in:Gander,Shanwick,Either position/NAT',
            'availability' => 'required|array|min:1',
            'availability.*' => 'required|in:'.implode(',', $openIds ?: ['0']),
        ], ['availability.required' => 'Please select at least one available time.', 'availability.min' => 'Please select at least one available time.']);

        EventSignup::create([
            'full_name' => $validated['full_name'],
            'cid' => $validated['cid'],
            'preferred_position' => $validated['preferred_position'],
            'availability' => array_values($validated['availability']),
            'submitted_at' => now(),
        ]);

        return redirect()->route('signups.show')->with('success', 'Thanks — your signup has been received.');
    }

    public function admin()
    {
        $slots = EventSignupSlot::orderBy('sort_order')->orderBy('id')->get();
        $signups = EventSignup::latest('submitted_at')->get();
        $slotLabels = EventSignupSlot::pluck('label', 'id');
        return view('admin.signups.index', compact('slots', 'signups', 'slotLabels'));
    }

    public function createSlot(Request $request)
    {
        $validated = $request->validate(['label' => 'required|string|max:120']);
        EventSignupSlot::create(['label' => $validated['label'], 'is_open' => true, 'sort_order' => EventSignupSlot::max('sort_order') + 1]);
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
