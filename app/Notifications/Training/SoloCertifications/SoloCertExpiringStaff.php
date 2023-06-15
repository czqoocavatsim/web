<?php

namespace App\Notifications\Training\SoloCertifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SoloCertExpiringStaff extends Notification implements ShouldQueue
{
    use Queueable;
    protected $cert;
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
            ->subject("Solo Certification Expiring ({$this->cert->rosterMember->user->id})")
            ->greeting('Hi there,')
            ->line('A solo certification is about to expire.')
            ->line("Expiry: {$this->cert->expires->toFormattedDateString()}")
            ->line("Granted by: {$this->cert->instructor->fullName('FLC')}")
            ->line("Student: {$this->cert->rosterMember->user->fullName('FLC')}")
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
