<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Discord\DiscordChannel;
use NotificationChannels\Discord\DiscordMessage;
use Auth;

class DiscordWelcome extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [DiscordChannel::class];
    }

    /**
     * @param $notifiable
     * @return DiscordMessage
     */
    public function toDiscord($notifiable)
    {
        return DiscordMessage::create("Hi " . Auth::user()->fullName('F') . ", welcome to the Gander Oceanic Discord server!\n\nPlease keep in mind the following rules for the server:\n```\n1. The VATSIM Code of Conduct applies.\n2. Always show respect and common decency to fellow members.\n3. Do not send server invites to servers unrelated to VATSIM without staff permission. Do not send ANY invites via DMs unless asked to.\n4. Do not send spam in the server, including images, text, or emotes.\n```\n\nFailure to comply with this rules can result in your removal from the server.\n\nEnjoy, and if you have any concerns, please feel free to message a staff member or create a ticket!");
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
