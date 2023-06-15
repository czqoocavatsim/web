<?php

namespace App\Notifications\Network;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TwoWeekInactivityReminder extends Notification implements ShouldQueue
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
            ->line('**Two Weeks Left - Activity Reminder**')
            ->line('According to our records, you have not yet fulfilled our currency requirement. You require **6 hours** online controlling on Gander or Shanwick or Bandbox by the end of the quarter, otherwise you will be marked inactive.')
            ->line('There are two weeks remaining in the current quarter, so this email serves only as a reminder in case you may have forgotten.')
            ->line("Please don’t hesitate to contact us should you have any concerns, or if you need us to make an accommodation. We're here to help!")
            ->line('*You received this email as there is important information in regard to your status with Gander Oceanic.*')
            ->salutation('Sent automatically through ActivityBot.')
            ->subject('[NOTICE] Two Weeks To Fulfil Activity Requirement');
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
