<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use GuzzleHttp\Client;
use App\Services\DiscordClient;
use App\Models\Training\Instructing\Records\TrainingSession;
use App\Models\Training\Instructing\Students\Student;
use App\Notifications\Training\Instructing\RemovedAsStudent;
use App\Models\Training\Instructing\Students\StudentStatusLabel;
use App\Models\Training\Instructing\Links\StudentStatusLabelLink;
use Carbon\Carbon;

class DiscordTrainingUpdates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Check all Training Threads are open and dont expire for one week
        {
            // Initialize the DiscordClient inside the handle method
            $discord = new DiscordClient();

            // Number of Messages Sent
            $counter = 0;

            // Get Active Threads
            $response = $discord->getClient()->get('channels/'.env('DISCORD_TRAINING_FORUM').'/threads/archived/public');
            $results = json_decode($response->getBody(), true);

            // dd($results);

            foreach($results['threads'] as $thread){
                $archiveTimestamp = Carbon::parse($thread['thread_metadata']['archive_timestamp']);
                
                // Thread was closed within the last 10 days
                if ($archiveTimestamp >= Carbon::now()->subDays(10) && $archiveTimestamp <= Carbon::now()) {
                    // Your code to handle the condition
                    // dd($archiveTimestamp);

                    // Get the ID of the Training Thread Recently Closed
                    if (preg_match('/\d+$/', $thread['name'], $matches)) {
                        $cid = $matches[0];
                    } else {
                        $cid = null;
                    }

                    // See if CID is still a student
                    $student = Student::where('user_id', $cid)->first();
                    if($student !== null){

                        // Thread should be active, so lets activate it.
                        $discord = new DiscordClient();
                        $data = $discord->getClient()->patch('channels/'.$thread['id'], [
                            'json' => [
                                'locked' => false,
                                'archived' => false,
                            ]
                        ]);
                    }
                }
            }

            $discord->sendMessageWithEmbed(env('DISCORD_WEB_LOGS'), 'AUTO: Training Thread Opened',$counter. ' Threads were automatically reopended as they had expired (more than 1 week since last activity)');
        }

        // Function for Training Thread Availability Updates
        {
            // Initialize the DiscordClient inside the handle method
            $discord = new DiscordClient();

            // Number of Messages Sent
            $counter = 0;

            // Get Active Threads
            $response = $discord->getClient()->get('guilds/'.env('DISCORD_GUILD_ID').'/threads/active');
            $results = json_decode($response->getBody(), true);

            foreach ($results['threads'] as $thread) {

                // Get the ID of the Active Training Thread
                if (preg_match('/\d+$/', $thread['name'], $matches)) {
                    $cid = $matches[0];
                } else {
                    $cid = null;
                }

                $student = Student::where('user_id', $cid)->first();

                // Check Lable is 'In Progress' or 'Ready For Pick-Up'
                if($student->hasLabel('In Progress') || $student->hasLabel('Ready For Pick-Up')){

                    // Check Sessions Upcoming
                    $upcoming_sessions = TrainingSession::where('student_id', $student->id)->whereBetween('scheduled_time', [Carbon::now(), Carbon::now()->addDays(7)])->first();

                    if($upcoming_sessions == null){
                        // There is no sessions within the next week
                        $counter++; //Add 1 to the $counter variable

                        // SendEmbed to ask student to send availability
                        $discord->sendEmbedInTrainingThread($cid, "Your Availability", 'Hello, <@'.$student->user->discord_user_id.'>

Please provide your availability for the next 7-14 days. Please ensure to tag the `@Instructor` role with all times you are available. Please provide these times in Zulu Format.

One of our team will make contact with you to organise a session if they have availability matching yours.

*If you have done this in the past few days, please disregard this message.*');
                }
            }
            }

            // Tell the log chat
            $discord->sendMessageWithEmbed(env('DISCORD_WEB_LOGS'), 'AUTO: Training Thread Availability Requests',$counter. ' Training Threads have been messaged asking for their weekly availability. This is only completed if a student has no scheduled session within the next 7 days.');
        }

        // Check 'Awaiting Exam' label students between 31-37 Days after Application
        {
            $count = 0;
            
            $student = Student::whereBetween('created_at', [Carbon::now()->subDays(37), Carbon::now()->subDays(30)])->where('current', true)->get();

            foreach($student as $s){
                if ($s->hasLabel('Awaiting Exam')) {
                    // Add one to the count
                    $count++;
                    // SendEmbed to ask student to send availability
                    $discord->sendEmbedInTrainingThread($cid, "Exam Not Requested", 'Hello, <@'.$s->user->discord_user_id.'>

Our records indicate that you have not requested, or completed your exam within a month of your Application being approved.

Please read the above message in order to understand how to request the exam.

Should you not request, and pass the exam within 60 days of your application being accepted, your training will be automatically terminated, and you will need to reapply to begin your training once more.

**Kind Regards,
Gander Oceanic Training Team**');
                }
            }

            // Tell the log chat
            $discord->sendMessageWithEmbed(env('DISCORD_WEB_LOGS'), 'AUTO: Training Thread Exam Requests Reminder',$count. ' Students are between 30-37 days past application without completing the exam. They have been notified of this.');
        }


        // Check 'Awaiting-Exam' label students 60 Days after Application and Terminate Training Automatically
        {
            $count2 = 0;

            $student = Student::where('created_at', '<=', Carbon::now()->subDays(60))->where('current', true)->get();

            foreach($student as $s){
                if ($s->hasLabel('Awaiting Exam')) {
                    // Add one to the count
                    $count2++;

                    // dd($s);

                    //Make as not current
                    $s->current = false;

                    //Remove role
                    $s->user->removeRole('Student');

                    //Discord Updates
                    if ($s->user->hasDiscord() && $s->user->member_of_czqo) {
                        //Get Discord client
                        $discord = new DiscordClient();

                        //remove student discord role
                        $discord->removeRole($s->user->discord_user_id, 482824058141016075);

                        $discord->EditThreadTag('Inactive', $s->user->id);

                        //close training Thread
                        $discord->closeTrainingThread($s->user->id, $s->user->discord_user_id, 'terminate');

                        // Notify Senior Team that new training has been terminated.
                        $discord->sendMessageWithEmbed(config('app.env') == 'local' ? intval(config('services.discord.web_logs')) : intval(config('services.discord.instructors')), 'Training Terminated', $s->user->fullName('FLC').' has had their training terminated. `Exam not completed within 60 days.`', 'error');
                    
                    } else {
                        //Get Discord client
                        $discord = new DiscordClient();
                        
                        // Notify Senior Team that training has been terminated
                        $discord->sendMessageWithEmbed(config('app.env') == 'local' ? intval(config('services.discord.web_logs')) : intval(config('services.discord.instructors')), 'Training Terminated', $s->user->fullName('FLC').' has had their training terminated. `Exam not completed within 60 days.`', 'error');
                    }

                    foreach ($s->labels as $label) {
                        if (!in_array($label->label()->name, ['inactive'])) {
                            $label->delete();
                        }
                    }

                    //Remove labels and instructor links and availability
                    $label = new StudentStatusLabelLink([
                        'student_status_label_id' => StudentStatusLabel::whereName('Inactive')->first()->id,
                        'student_id'              => $s->id,
                    ]);
                    $label->save();
                    

                    if ($s->instructor()) {
                        $s->instructor()->delete();
                    }
                    foreach ($s->availability as $a) {
                        $a->delete();
                    }

                    //notify
                    $s->user->notify(new RemovedAsStudent());

                    //Save
                    $s->save();
                }
            }

            // Tell the log chat
            $discord->sendMessageWithEmbed(env('DISCORD_WEB_LOGS'), 'AUTO: Training Termination',$count2. ' Students have been terminated automatically. Exam not completed within 60 days.');

        }
    }
}