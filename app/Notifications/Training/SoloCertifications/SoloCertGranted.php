<?php

namespace App\Notifications\Training\SoloCertifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SoloCertGranted extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($cert)
    {
        $this->cert = $cert;
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
            ->subject("Solo Certification Granted")
            ->greeting("Hi there,")
            ->line("You have been granted a solo certification.")
            ->line("Expiry: {$this->cert->expires->toFormattedDateString()}")
            ->line("Granted by: {$this->cert->instructor->fullName('FLC')}")
            ->line("Your use of this solo certification is bound to our policies and VATSIM's GRP. Your instructor will give you more information.")
            ->line("If you believe this is a mistake or have any questions, please email the Chief Instructor.")
            ->line("*You were sent this email as your training status with Gander Oceanic has been updated.*")
            ->salutation("Gander Oceanic OCA");
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
