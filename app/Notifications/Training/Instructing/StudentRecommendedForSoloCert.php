<?php

namespace App\Notifications\Training\Instructing;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class StudentRecommendedForSoloCert extends Notification implements ShouldQueue
{
    use Queueable;
    protected $student, $instructor;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($student, $instructor)
    {
        $this->student = $student;
        $this->instructor = $instructor;
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
        return (new MailMessage())
            ->subject("{$this->student->user->fullName('FLC')} Recommended For Solo Certification")
            ->line("{$this->instructor->user->fullName('FL')} has recommended student {$this->student->user->fullName('FLC')} for a solo certification.")
            ->action('View Student', route('training.admin.instructing.students.view', $this->student->user_id))
            ->salutation('Gander Oceanic OCA');
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
