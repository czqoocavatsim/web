<?php

namespace App\Notifications\DIscord;

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
        $message = new DiscordMessage;

        $message->embed([
            'title' => 'Welcome to the Gander Oceanic Discord, '.Auth::user()->fullName('F').'!',
            'color' => 0x80c9,
            'fields' => array(
                [
                    'name' => 'Rules',
                    'value' => 'Please read and abide by the rules set out at the top of <#752761970826018877>. Failure to comply with this rules could result in your removal from the server and/or VATSIM disciplinary action.',
                    'inline' => false
                ],
                [
                    'name' => 'Chat with our Gander Oceanic controller and pilot community                    ',
                    'value' => '<#479250337048297485> is where the action happens. Talk to other pilots and controllers as you make your oceanic crossing or ask questions.',
                    'inline' => false
                ],
                [
                    'name' => 'Get the latest CZQO and VATSIM news, and other relevant updates',
                    'value' => 'Check out <#488265136696459292> for news from Gander Oceanic\'s staff team and <#752755987743768657> for the latest from the VATSIM network.',
                    'inline' => false
                ]
            )
        ]);

        return $message;
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
