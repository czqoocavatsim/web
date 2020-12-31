<?php

namespace App\Notifications\Training\Instructing;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Discord\DiscordChannel;
use NotificationChannels\Discord\DiscordMessage;

class SessionCancelled extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($session, $type)
    {
        $this->session = $session;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $notifiable->notificationPreferences->training_notifications == 'email+discord' ? ['mail', DiscordChannel::class] : ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if ($this->type == 'training') {
            return (new MailMessage)
                ->subject("Training Session Cancelled")
                ->greeting("Hi {$this->session->student->user->fullName('F')},")
                ->line("{$this->session->instructor->user->fullName('FL')} has cancelled your upcoming training session.")
                ->line("If you have any questions, please contact your Instructor.")
                ->action('View Session', '')
                ->salutation("Gander Oceanic OCA");
        } elseif ($this->type == 'ots') {
            return (new MailMessage)
                ->subject("OTS Session Cancelled")
                ->greeting("Hi {$this->session->student->user->fullName('F')},")
                ->line("{$this->session->instructor->user->fullName('FL')} has cancelled your upcoming OTS session.")
                ->line("If you have any questions, please contact your Assessor.")
                ->action('View Session', '')
                ->salutation("Gander Oceanic OCA");
        }
    }

    /**
     * Get the Discord representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return NotificationChannels\Discord\DiscordMessage
     */
    public function toDiscord($notifiable)
    {
        $message = new DiscordMessage();
        if ($this->type == 'training') {
            $message->embed([
                'title' => 'Training Session Cancelled',
                'description' => "{$this->session->instructor->user->fullName('FL')} has cancelled your upcoming training session. If you have any questions, please contact your Instructor.",
                'color' => 0x80c9,
                "timestamp" => Carbon::now(),
                'footer' => array(
                    'text' => 'You can disable Discord notifications at any time in myCZQO'
                )
            ]);
        } elseif ($this->type == 'ots') {
            $message->embed([
                'title' => 'OTS Session Cancelled',
                'description' => "{$this->session->instructor->user->fullName('FL')} has cancelled your upcoming OTS session. If you have any questions, please contact your Assessor.",
                'color' => 0x80c9,
                "timestamp" => Carbon::now(),
                'footer' => array(
                    'text' => 'You can disable Discord notifications at any time in myCZQO'
                )
            ]);
        }

        return $message;
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
