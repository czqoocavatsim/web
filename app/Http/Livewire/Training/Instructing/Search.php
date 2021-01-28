<?php

namespace App\Http\Livewire\Training\Instructing;

use App\Models\Training\Instructing\Instructors\Instructor;
use App\Models\Training\Instructing\Records\OTSSession;
use App\Models\Training\Instructing\Records\TrainingSession;
use App\Models\Training\Instructing\Students\Student;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class Search extends Component
{
    public $search = '';

    public function render()
    {
        //Create results for students
        $resultsStudents =
            strlen($this->search) > 2 ?
            Student::whereHas('user', function (Builder $query) {
                $query
                ->where('display_fname', 'like', '%'.$this->search.'%')
                ->orWhere('lname', 'like', '%'.$this->search.'%');
            })
            ->orWhere('user_id', 'LIKE', '%'.$this->search.'%')
            ->get()
            ->take(6)
            : [];

        //Create results for students
        $resultsInstructors =
        strlen($this->search) > 2 ?
        Instructor::whereHas('user', function (Builder $query) {
            $query
            ->where('display_fname', 'like', '%'.$this->search.'%')
            ->orWhere('lname', 'like', '%'.$this->search.'%');
        })
        ->orWhere('user_id', 'LIKE', '%'.$this->search.'%')
        ->get()
        ->take(6)
        : [];

        //Create results for training sessions
        $resultsTrainingSessions =
        strlen($this->search) > 2 ?
        TrainingSession::whereHas('student.user', function (Builder $query) {
            $query
            ->where('display_fname', 'like', '%'.$this->search.'%')
            ->orWhere('lname', 'like', '%'.$this->search.'%')
            ->orWhere('id', 'like', '%'.$this->search.'%');
        })
        ->where('scheduled_time', '>', Carbon::now())
        ->get()
        ->take(6)
        : [];

        //Create results for OTS sessions
        $resultsOtsSessions =
        strlen($this->search) > 2 ?
        OTSSession::whereHas('student.user', function (Builder $query) {
            $query
            ->where('display_fname', 'like', '%'.$this->search.'%')
            ->orWhere('lname', 'like', '%'.$this->search.'%')
            ->orWhere('id', 'like', '%'.$this->search.'%');
        })
        ->where('scheduled_time', '>', Carbon::now())
        ->get()
        ->take(6)
        : [];

        //Return view
        return view('livewire.training.instructing.search', compact('resultsStudents', 'resultsInstructors', 'resultsTrainingSessions', 'resultsOtsSessions'));
    }
}
