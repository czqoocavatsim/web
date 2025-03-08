<?php

namespace App\Notifications\Training\Instructing;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SessionAssignedToYou extends Notification implements ShouldQueue
{
    use Queueable;
    protected $session, $type;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($session, $type)
    {
        $this->session = $session;
        $this->type = $type;
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
        if ($this->type == 'training') {
            return (new MailMessage())
                ->subject("You've Been Assigned To A Training Session")
                ->line("You have been assigned to a training session with {$this->session->student->user->fullName('FLC')}, scheduled for {$this->session->scheduled_time->toDayDateTimeString()}.")
                ->line('If you have any questions, please contact the Events & Training Director.')
                ->action('View Session', route('training.admin.instructing.training-sessions.view', $this->session))
                ->salutation('Gander Oceanic OCA');
        } elseif ($this->type == 'ots') {
            return (new MailMessage())
                ->subject("You've Been Assigned To A OTS Session")
                ->line("You have been assigned to a OTS session with {$this->session->student->user->fullName('FLC')}, scheduled for {$this->session->scheduled_time->toDayDateTimeString()}.")
                ->line('If you have any questions, please contact the Events & Training Director.')
                ->action('View Session', route('training.admin.instructing.training-sessions.view', $this->session))
                ->salutation('Gander Oceanic OCA');
        }
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
