<?php

namespace App\Notifications\Roster;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SevenDaysFromRemoval extends Notification
{
    use Queueable;
    protected $user;
    protected $currency;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $currency)
    {
        $this->user = $user;
        $this->currency = $currency;
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
            'emails.roster.sevendaystillremoval',
            ['user' => $this->user, 'currency' => $this->currency]
        )->subject('One Week to Fulfill Activity Requirements');
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
