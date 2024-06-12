<?php

use App\Models\Training\Instructing\Students\StudentStatusLabel;
use Illuminate\Database\Seeder;

class StudentStatusLabelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $notReady = new StudentStatusLabel([
            'name'        => 'Awaiting Exam',
            'colour'      => 'grey',
            'fa_icon'     => 'far fa-pause-circle',
            'description' => 'Student yet to complete entry exam.',
            'restricted'  => false,
        ]);
        $notReady->save();

        $readyForPickUp = new StudentStatusLabel([
            'name'        => 'Ready For Pick-Up',
            'colour'      => 'orange',
            'fa_icon'     => 'far fa-clock',
            'description' => 'Student ready to be picked up by instructor.',
            'restricted'  => false,
        ]);
        $readyForPickUp->save();

        $inProgress = new StudentStatusLabel([
            'name'        => 'In Progress',
            'colour'      => 'blue',
            'fa_icon'     => 'fas fa-forward',
            'description' => 'Student training is in progress.',
            'restricted'  => false,
        ]);
        $inProgress->save();

        $soloCert = new StudentStatusLabel([
            'name'        => 'Solo Certification',
            'colour'      => 'purple',
            'fa_icon'     => 'fas fa-user',
            'description' => 'Student has active solo certification.',
            'restricted'  => false,
        ]);
        $soloCert->save();

        $readyForAssessment = new StudentStatusLabel([
            'name'        => 'Ready for Assessment',
            'colour'      => 'green',
            'fa_icon'     => 'fa fa-check',
            'description' => 'Student is ready for assessment via OTS.',
            'restricted'  => false,
        ]);
        $readyForAssessment->save();

        $completed = new StudentStatusLabel([
            'name'        => 'Completed',
            'colour'      => 'green darken-4',
            'fa_icon'     => 'fa fa-check-double',
            'description' => 'Student has completed training.',
            'restricted'  => true,
        ]);
        $completed->save();

        $inactive = new StudentStatusLabel([
            'name'        => 'Inactive',
            'colour'      => 'grey',
            'description' => 'Student is inactive.',
            'restricted'  => true,
        ]);
        $inactive->save();
    }
}
