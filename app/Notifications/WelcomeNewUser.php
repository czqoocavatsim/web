<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class WelcomeNewUser extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
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
                    ->subject("Welcome to Gander Oceanic, {$this->user->fullName('FL')}!")
                    ->from('chief@ganderoceanic.ca', 'David Solesvik')
                    ->line("Welcome to Gander Oceanic, we're very excited that you're here!")
                    ->line("On our site you can find various resources relating to Oceanic operations in the North Atlantic for both pilots and controllers. Please don't hesitate to contact me should you have any questions about us!")
                    ->salutation(new HtmlString('Kind regards,<br>David Solesvik<br>OCA Chief'));
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
