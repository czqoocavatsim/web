<?php

namespace App\Notifications\Training\Applications;

use Illuminate\Bus\Queueable;
use App\Models\Training\Application;
use Illuminate\Notifications\Notification;
use App\Models\Training\ApplicationComment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewCommentStaff extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Application $application, ApplicationComment $comment)
    {
        $this->application = $application;
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())->view(
            'emails.training.applications.newcommentstaff',
            ['application' => $this->application, 'comment' => $this->comment]
        )->subject('#'.$this->application->reference_id.' - New Comment From Applicant');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
