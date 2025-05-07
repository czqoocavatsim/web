<?php

namespace App\Notifications\Network;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EnrouteRatingUpgrade extends Notification
{
    use Queueable;
    protected $u;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($u)
    {
        $this->u = $u;
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
            'emails.network.newc1rating',
            ['u' => $this->u]
        )->subject('Congratulations on your new C1 Rating!');
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
