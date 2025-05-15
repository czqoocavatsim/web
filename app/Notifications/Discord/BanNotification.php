<?php

namespace App\Notifications\Discord;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Discord\DiscordMessage;

class BanNotification extends Notification
{
    use Queueable;
    protected $user, $ban;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $ban)
    {
        $this->user = $user;
        $this->ban = $ban;
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
     * @param $notifiable
     *
     * @return DiscordMessage
     */
    public function toDiscord($notifiable)
    {
        return DiscordMessage::create(
            "
            Hi {$this->user->fullName('FLC')},\n\nYou have been banned from the Gander Oceanic Discord server.\n\nReason:\n```{$this->ban->reason}```\nThe ban will expire on {$this->ban->end_time->toDayDateTimeString()}.\n\nTo appeal, email the FIR Director.
            "
        );
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
            'emails.discord.bannotification',
            ['user' => $this->user, 'ban' => $this->ban]
        )->subject('Ban from Gander Oceanic Discord server');
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
