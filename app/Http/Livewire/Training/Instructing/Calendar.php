<?php

namespace App\Http\Livewire\Training\Instructing;

use App\Models\Training\Instructing\Records\OTSSession;
use App\Models\Training\Instructing\Records\TrainingSession;
use Asantibanez\LivewireCalendar\LivewireCalendar;
use Illuminate\Support\Collection;
use Livewire\Component;

class Calendar extends LivewireCalendar
{
    public function events() : Collection
    {
        $trainingSessions = TrainingSession::query()
            ->whereDate('scheduled_time', '>=', $this->gridStartsAt)
            ->whereDate('scheduled_time', '<=', $this->gridEndsAt)
            ->get()
            ->map(function (TrainingSession $session) {
                return [
                    'id' => 'training-' . $session->id,
                    'title' => 'Training Session -' . $session->student->user->fullName('FLC'),
                    'description' => 'Instructor: ' . $session->instructor->user->fullName('FL'),
                    'date' => $session->scheduled_time
                ];
            });
        $otsSessions = OTSSession::query()
            ->whereDate('scheduled_time', '>=', $this->gridStartsAt)
            ->whereDate('scheduled_time', '<=', $this->gridEndsAt)
            ->get()
            ->map(function (OTSSession $session) {
                return [
                    'id' => 'ots-' . $session->id,
                    'title' => 'OTS Session Session -' . $session->student->user->fullName('FLC'),
                    'description' => 'Instructor: ' . $session->instructor->user->fullName('FL'),
                    'date' => $session->scheduled_time
                ];
            });
        return $trainingSessions->merge($otsSessions);
    }

    public function onEventClick($eventId)
    {
        $explode = explode('-', $eventId);
        switch ($explode[0]) {
            case 'training':
                return redirect()->route('training.admin.instructing.training-sessions.view', $explode[1]);
                break;
            case 'ots':
                return redirect()->route('training.admin.instructing.ots-sessions.view', $explode[1]);
                break;
            default:
                throw new \Exception();
        }
    }
}
