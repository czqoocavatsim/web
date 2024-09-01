<?php

namespace App\Notifications\Network;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OneMonthInactivityReminder extends Notification implements ShouldQueue
{
    use Queueable;
    protected $rosterMember, $cycle;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($rosterMember, $cycle)
    {
        $this->rosterMember = $rosterMember;
        $this->cycle = $cycle;
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
            ->greeting("Hi {$this->rosterMember->user->fullName('FLC')}")
            ->line('**One Month - Activity Reminder**')
            ->line('According to our records, you have not yet fulfilled our currency requirement. You require **1 hour** online controlling on EGGX, CZQO or NAT in a 12 Month Period.')
            ->line('This email serves only as a reminder in case you may have forgotten.')
            ->line("Please don’t hesitate to contact us should you have any concerns.")
            ->line('*You received this email as there is important information in regard to your status with Gander Oceanic.*')
            ->salutation('Sent automatically through ActivityBot.')
            ->subject('[NOTICE] One Month To Fulfil Activity Requirement');
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
