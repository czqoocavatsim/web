<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\Application;
use App\Models\Training\Instructing\Students\Student;

class TrainingAdminController extends Controller
{
    public function dashboard()
    {
        //Get applications
        $applications = Application::where('status', 0)->get()->sortBy('created_at');

        //Get all students ready for pickup
        $readyForPickup = Student::cursor()->filter(function ($student) {
            $labels = $student->labels;
            foreach ($labels as $label) {
                $label = $label->label();
                if ($label->name == 'Ready For Pick-Up') {
                    return true;
                }
            }

            return false;
        })->sortBy('created_at');

        return view('admin.training.dashboard', compact('applications', 'readyForPickup'));
    }
}
