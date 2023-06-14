<?php

namespace App\Http\Controllers\TrainingCalendar;

use App\Models\Network\MonitoredPosition;
use App\Models\Training\Instructing\Records\OTSSession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Training\Instructing\Records\TrainingSession;

class TrainingController extends Controller
{
    public function index() {
        return view('trainingcalendar.index');
    }

    public function getTrainingSessions(Request $request) {
        $trainingsessions = TrainingSession::whereDate('scheduled_time', '>=', Carbon::createFromFormat('Y-m-d', explode('T',$request->start)[0]))   
                ->whereDate('scheduled_time', '<=', Carbon::createFromFormat('Y-m-d', explode('T',$request->end)[0]))
                ->whereNotNull('position_id')
                ->get(['scheduled_time AS start', 'position_id']);
        
        foreach($trainingsessions->all() as $ts){
            $ts->title = MonitoredPosition::whereId($ts->position_id)->first()->identifier;
        }
        
        return response()->json($trainingsessions);
    }

    public function getOtsSessions(Request $request) {
        $otssessions = OTSSession::whereDate('scheduled_time', '>=', Carbon::createFromFormat('Y-m-d', explode('T',$request->start)[0]))   
                ->whereDate('scheduled_time', '<=', Carbon::createFromFormat('Y-m-d', explode('T',$request->end)[0]))
                ->whereNotNull('position_id')
                ->get(['scheduled_time AS start', 'position_id']);
        
        foreach($otssessions->all() as $os){
            $os->title = MonitoredPosition::whereId($os->position_id)->first()->identifier;
        }
        
        return response()->json($otssessions);
    }

    public function getTrainingSessionsAdmin(Request $request) {
        $trainingsessions = TrainingSession::whereDate('scheduled_time', '>=', Carbon::createFromFormat('Y-m-d', explode('T',$request->start)[0]))   
                ->whereDate('scheduled_time', '<=', Carbon::createFromFormat('Y-m-d', explode('T',$request->end)[0]))
                ->get(['scheduled_time AS start', 'position_id']);
        
        foreach($trainingsessions->all() as $ts){
            $ts->title = 'Training Session';
        }
        
        return response()->json($trainingsessions);
    }

    public function getOtsSessionsAdmin(Request $request) {
        $otssessions = OTSSession::whereDate('scheduled_time', '>=', Carbon::createFromFormat('Y-m-d', explode('T',$request->start)[0]))   
                ->whereDate('scheduled_time', '<=', Carbon::createFromFormat('Y-m-d', explode('T',$request->end)[0]))
                ->get(['scheduled_time AS start', 'position_id']);
        
        foreach($otssessions->all() as $os){
            $os->title = 'OTS Session';
        }
        
        return response()->json($otssessions);
    }
}
