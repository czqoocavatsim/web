<?php

namespace App\Http\Livewire\Training\Instructing;

use App\Models\Training\Instructing\Students\Student;
use Livewire\Component;

class Search extends Component
{
    public $search = '';

    public function render()
    {
        //Create results
        $results = strlen($this->search) > 2 ? Student::where('user_id', 'LIKE', '%' . $this->search . '%')->get()->take(6) : array();

        //Return view
        return view('livewire.training.instructing.search', compact('results'));
    }
}
