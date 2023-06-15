<?php

namespace App\Notifications\Training\Instructing;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SessionScheduledTimeChanged extends Notification implements ShouldQueue
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
                ->subject('New Time For Your Training Session')
                ->line("The scheduled time for your training session with {$this->session->instructor->user->fullName('FL')} has been changed to {$this->session->scheduled_time->toDayDateTimeString()}.")
                ->line('If you have any questions, please contact your Instructor.')
                ->action('View Session', route('training.portal.sessions.view-training-session', $this->session))
                ->salutation('Gander Oceanic OCA');
        } elseif ($this->type == 'ots') {
            return (new MailMessage())
                ->subject('New Time For Your OTS Session')
                ->line("The scheduled time for your OTS session with {$this->session->instructor->user->fullName('FL')} has been changed to {$this->session->scheduled_time->toDayDateTimeString()}.")
                ->line('If you have any questions, please contact your Assessor.')
                ->action('View Session', route('training.portal.sessions.view-ots-session', $this->session))
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
