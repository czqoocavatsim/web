<?php

namespace App\Notifications\Events;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewControllerSignUp extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($signUp)
    {
        $this->signUp = $signUp;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
    */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject("Sign Up For Event {$this->signUp->event->name}")
                    ->line("{$this->signUp->user->fullName('FLC')} has signed up for the event {$this->signUp->event->name}.")
                    ->line("Availability: {$this->signUp->start_availability_timestamp} to {$this->signUp->end_availability_timestamp}")
                    ->action('View All Sign Ups', route('events.admin.view', $this->signUp->event->slug))
                    ->salutation('Gander Oceanic OCA');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
