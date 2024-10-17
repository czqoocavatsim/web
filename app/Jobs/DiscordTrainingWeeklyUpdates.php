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
use App\Models\Users\User;
use App\Models\Training\Instructing\Students\Student;
use App\Notifications\Training\Instructing\RemovedAsStudent;
use App\Models\Training\Instructing\Students\StudentStatusLabel;
use App\Models\Training\Instructing\Links\StudentStatusLabelLink;
use Carbon\Carbon;

class DiscordTrainingWeeklyUpdates implements ShouldQueue
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
            $to_activate = 0;
            $threads_activated = ["names" => []];

            // Get Active Threads
            $response = $discord->getClient()->get('channels/'.env('DISCORD_TRAINING_FORUM').'/threads/archived/public');
            $all_threads = json_decode($response->getBody(), true);
            $results = ["threads" => []];

            // dd($all_threads);

            // Filter Threads by just Training Threads
            foreach($all_threads['threads'] as $each_thread){
                if(isset($each_thread['parent_id']) && $each_thread['parent_id'] === '1226234767138226338') {
                    $results['threads'][] = $each_thread;
                }
            }

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
                    $student = Student::where('current', 1)->where('user_id', $cid)->first();

                    if($student !== null){

                        $to_activate++;

                        $threads_activated["names"][] = $thread['name'];

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
        }

        // Function for Training Thread Availability Updates
        {
            // Initialize the DiscordClient inside the handle method
            $discord = new DiscordClient();

            // Get Active Threads
            $response = $discord->getClient()->get('guilds/'.env('DISCORD_GUILD_ID').'/threads/active');
            $all_threads2 = json_decode($response->getBody(), true);
            $results2 = ["threads" => []];

            // dd($all_threads2);

            // Filter Threads by just Training Threads
            foreach($all_threads2['threads'] as $each_thread){
                if(isset($each_thread['parent_id']) && $each_thread['parent_id'] === '1226234767138226338') {
                    $results2['threads'][] = $each_thread;
                }
            }

            // dd($results2);

            // Initialise Variables for Loop
            // Number of Messages Sent
            $avail_message = 0;
            $avail_maessage_names = ["names" => []];
            // Exam Not Requested
            $await_exam_count = 0;
            $exam_request_names = ["names" => []];
            // Training Terminated
            $term_training = 0;
            $terminate_names = ["names" => []];

            // Lets go through Each Active Thread Now
            foreach ($results2['threads'] as $thread) {

                // Get the ID of the Active Training Thread
                if (preg_match('/\d+$/', $thread['name'], $matches)) {
                    $cid = $matches[0];
                } else {
                    $cid = null;
                }

                // See if user is still a student
                if($cid !== null){
                    sleep(1);

                    $student = Student::whereCurrent(true)->where('user_id', $cid)->first();

                    // If user is not a student, lets go to the next student
                    if($student == null){
                        continue;
                    }

                    // Weekly Availability Requests
                    {
                        //Is the Applied Tag = "In Progress" or "Ready For Pick-Up"?
                        $tag_completed = (bool) in_array("1271846477966086265", $thread['applied_tags']);
                        $tag_inProgress = (bool) in_array("1271847420631978107", $thread['applied_tags']);
                        $tag_PickUp = (bool) in_array("1271846369510035627", $thread['applied_tags']);

                        // Thread isnt completed, but is In progress or ready for pick up
                        if (!$tag_completed && ($tag_inProgress || $tag_PickUp)) {
                            // Check Sessions Upcoming
                            $upcoming_sessions = TrainingSession::where('student_id', $cid)->whereBetween('scheduled_time', [Carbon::now(), Carbon::now()->addDays(7)])->first();

                            if($upcoming_sessions == null){
                                // There is no sessions within the next week
                                $avail_message++;
                                $avail_maessage_names["names"][] = $thread['name'];

                                // SendEmbed to ask student to send availability
                                $discord->sendEmbedInTrainingThread($cid, "Please Provide Availability", 'Hello, <@'.$student->user->discord_user_id.'>

As we head into the Weekend, we ask you please provide your availability for next week. Please ensure to tag the `@Instructor` role with all times you are available. Please provide these times in Zulu Format.

One of our team will make contact with you to organise a session for next if they have availability matching yours.

*If you have done this in the past few days, or are unable to provide any times for next week, please disregard this message.*');
                            }
                        }
                    }
                    
                    


            // Check 'Awaiting Exam' label students between 31-37 Days after Application
            {
            
            $student_exam = Student::whereBetween('created_at', [Carbon::now()->subDays(37), Carbon::now()->subDays(30)])->where('user_id', $cid)->first();
            // dd($student_exam);

            if($student_exam != null && $student_exam->hasLabel('Awaiting Exam')){
                $await_exam_count++;
                $exam_request_names["names"][] = $thread['name'];
                
                // SendEmbed to ask student to send availability
                $discord->sendEmbedInTrainingThread($cid, "Exam Not Requested", 'Hello, <@'.$student_exam->user->discord_user_id.'>

                Our records indicate that you have not requested, or completed your exam within a month of your Application being approved.
                
                Please read the above message in order to understand how to request the exam.
                
                Should you not request, and pass the exam within 60 days of your application being accepted, your training will be automatically terminated, and you will need to reapply to begin your training once more.
                
                **Kind Regards,
                Gander Oceanic Training Team**');
                }
            }


            // Terminate Training
            {
                $s = Student::where('created_at', '<=', Carbon::now()->subDays(60))->where('user_id', $cid)->first();

                if($s != null && $s->hasLabel('Awaiting Exam')){
                    $term_training++;
                    $terminate_names["names"][] = $thread['name'];

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















                }
            }
        }

        ## DISCORD UPDATE
        {
            // Beginning
            if($avail_message > 0 || $avail_message > 0 ||  $await_exam_count > 0 || $term_training > 0) {
                $update_content = "Full list of functions completed this week for the Discord Threads, <@200426385863344129>";
            } else {
                $update_content = "No Thread Updates for this week.";
            }

            // User Activiations
            if($to_activate > 0){
                $update_content .= "\n\n**__Training Thread Activiation__**\n";
                $update_content .= $to_activate." training threads where activated (7 day auto close issue).";

                // get Thread Names
                foreach ($threads_activated["names"] as $name){
                    $update_content .= "\n - " . $name;
                }
            }

            // Availability Message Updates
            if($avail_message > 0){
                $update_content .= "\n\n**__Student Availability Messages__**\n";
                $update_content .= $avail_message." students asked for availability";

                // get Thread Names
                foreach ($avail_maessage_names["names"] as $name){
                    $update_content .= "\n - " . $name;
                }
            }

            // No Exam Request after 1 Month
            if($await_exam_count > 0){
                $update_content .= "\n\n**__Exam Not Requested__**\n";
                $update_content .= $await_exam_count." students have not requested the exam, they have been in the Training System for 31-37 days.";

                // get Thread Names
                foreach ($exam_request_names["names"] as $name){
                    $update_content .= "\n - " . $name;
                }
            }

            // Terminate Training
            if($term_training > 0){
                $update_content .= "\n\n**__Training Terminated__**\n";
                $update_content .= $term_training." students have been terminated as they have not completed the exam within 60 days.";

                // get Thread Names
                foreach ($terminate_names["names"] as $name){
                    $update_content .= "\n - " . $name;
                }
            }

            // Send Message
            $discord->sendMessageWithEmbed(env('DISCORD_WEB_LOGS'), 'WEEKLY: Discord Training Thread Updates', $update_content);
        }
    }
}
