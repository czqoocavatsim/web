<?php

namespace App\Notifications\News;

use Illuminate\Bus\Queueable;
use Illuminate\Support\HtmlString;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class Announcement extends Notification implements ShouldQueue
{
    use Queueable;
    protected $user, $announcement;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $announcement)
    {
        $this->user = $user;
        $this->announcement = $announcement;
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
            ->greeting($this->announcement->title)
            ->line(new Htmlstring($this->announcement->html()))
            ->line("This announcement was sent to you for the following reason: {$this->announcement->reason_for_sending}")
            ->subject($this->announcement->title)
            ->salutation(new HtmlString("Sent by <b>{$this->announcement->user->fullName('FLC')} (".$this->announcement->user->staffProfile->position.')</b>' ?? 'No staff position found'.')</b>'));
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
